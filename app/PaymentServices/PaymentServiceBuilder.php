<?php

namespace App\PaymentServices;

use App\Enums\StripeProductID;
use App\Http\Requests\DonationFormRequest;
use App\Models\Donor;
use App\Providers\StripeProvider;
use App\Repositories\DonorRepository;

class PaymentServiceBuilder
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

    public function validate(): PaymentServiceBuilder
    {
        $this->formData = $this->request->validated();

        return $this;
    }

    public function createCustomer(): PaymentServiceBuilder
    {
        $this->stripeCustomer = StripeProvider::createCustomer($this->formData['customer']);

        return $this;
    }

    public function creatOneTimePrice(): PaymentServiceBuilder
    {
        $this->stripePrice = StripeProvider::createOneTimePrice(
            StripeProductID::getValueByLowerCaseKey($this->formData['product']),
            $this->formData['price']
        );

        return $this;
    }

    public function createSubscriptionPrice(): PaymentServiceBuilder
    {
        $this->stripePrice = StripeProvider::createSubscriptionPrice(
            StripeProductID::getValueByLowerCaseKey($this->formData['product']),
            $this->formData['price']
        );

        return $this;
    }

    public function storeDonor(): PaymentServiceBuilder
    {
        $this->donor = DonorRepository::storeDonor($this->formData, $this->stripeCustomer);

        return $this;
    }

    public function createMetadata(string $paymentSchedule): PaymentServiceBuilder
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
            'payment_schedule' => $paymentSchedule,
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

    public function createSubscriptionSession(): array
    {
        $checkoutSession = StripeProvider::createSubscriptionSession(
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
