<?php

namespace App\PaymentServices;

use App\Enums\StripeProductID;
use App\Http\Requests\DonationFormRequest;
use App\Models\Donor;
use App\Providers\StripeProvider;
use App\Repositories\DonorRepository;

class OneTimePaymentBuilder
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

    public function validate(): OneTimePaymentBuilder
    {
        $this->formData = $this->request->validated();

        return $this;
    }

    public function createCustomer(): OneTimePaymentBuilder
    {
        $this->stripeCustomer = StripeProvider::createCustomer($this->formData['customer']);

        return $this;
    }

    public function createPrice(): OneTimePaymentBuilder
    {
        $this->stripePrice = StripeProvider::createOneTimePrice(
            StripeProductID::getValueByLowerCaseKey($this->formData['product']),
            $this->formData['price']
        );

        return $this;
    }

    public function storeDonor(): OneTimePaymentBuilder
    {
        $this->donor = DonorRepository::storeDonor($this->formData, $this->stripeCustomer);

        return $this;
    }

    public function createMetadata(): OneTimePaymentBuilder
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
