<?php

namespace App\Services;

use App\Enums\StripeProductID;
use App\Exceptions\CustomException;
use App\Http\Requests\DonationFormRequest;
use App\Models\Donor;
use App\Providers\StripeProvider;
use App\Repositories\DonorRepository;

class OneTimePayment
{
    private DonationFormRequest $request;

    private array $formData;

    private Donor $donor;

    private object $stripeCustomer;

    private object $stripePrice;

    private array $paymentIntentMetaData;

    public function __construct(DonationFormRequest $request)
    {
        $this->request = $request;
    }

    /**
     * Step 1: Validate the request data
     */
    public function validate(): static
    {
        $this->formData = $this->request->validated();

        return $this;
    }

    /**
     * Step 2: Create the Stripe customer
     *
     * @throws CustomException
     */
    public function createCustomer(): static
    {
        $this->stripeCustomer = StripeProvider::createCustomer($this->formData['customer']);

        return $this;
    }

    /**
     * Step 3: Create the price for the product
     *
     * @throws CustomException
     */
    public function createPrice(): static
    {
        $this->stripePrice = StripeProvider::createOneTimePrice(
            StripeProductID::getValueByLowerCaseKey($this->formData['product']),
            $this->formData['price']
        );

        return $this;
    }

    /**
     * Step 4: Store the donor information
     */
    public function storeDonor(): static
    {
        $this->donor = DonorRepository::storeDonor($this->formData, $this->stripeCustomer);

        return $this;
    }

    /**
     * Step 5: Create metadata for the payment
     *
     * @throws CustomException
     */
    public function createMetadata(): static
    {
        $this->donor['stripe_customer_object'] = json_decode($this->donor['stripe_customer_object']);

        $this->paymentIntentMetaData = [
            'donor_id' => $this->donor['donor_id'],
            'donor_name' => $this->donor['name'],
            'donor_external_id' => $this->donor['donor_external_id'],
            'donor_type' => $this->donor['type'],
            'donor_email' => $this->donor['email'],
            'donation_project' => StripeProvider::getProductNameFromId(
                StripeProductID::getValueByLowerCaseKey($this->formData['product'])
            ),
            'amount' => $this->formData['price'],
            'currency' => 'jpy',
            'type' => 'ONE_TIME',
        ];

        return $this;
    }

    /**
     * Step 6: Create the Stripe checkout session
     *
     * @throws CustomException
     */
    public function createCheckoutSession(): array
    {
        $checkoutSession = StripeProvider::createCheckoutSession(
            $this->stripeCustomer->id,
            $this->stripePrice->id,
            $this->paymentIntentMetaData
        );

        return [
            'donor' => $this->donor,
            'stripe_checkout_session' => $checkoutSession,
            'stripe_price' => $this->stripePrice,
            'stripe_customer' => $this->stripeCustomer,
        ];
    }
}
