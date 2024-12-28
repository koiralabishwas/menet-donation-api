<?php

namespace App\Services\Stripe;

use App\Enums\PaymentSchedule;
use App\Enums\StripeProductID;
use App\Helpers\Helpers;
use App\Models\Donor;
use App\Providers\StripeProvider;
use App\Repositories\DonorRepository;
use Stripe\Exception\ApiErrorException;
use Stripe\Exception\InvalidRequestException;

class PaymentServiceBuilder
{
    private array $request;

    private Donor $donor;

    private object $stripeCustomer;

    private object $stripePrice;

    private array $paymentIntentMetaData;

    private array $subscriptionMetaData;

    public function __construct(array $request)
    {
        $this->request = $request;
    }

    /**
     * @throws ApiErrorException
     * @throws InvalidRequestException
     */
    public function createCustomer(): PaymentServiceBuilder
    {
        $this->stripeCustomer = StripeProvider::createCustomer($this->request['customer']);

        return $this;
    }

    public function storeDonor(): PaymentServiceBuilder
    {
        $this->donor = DonorRepository::storeDonor($this->request, $this->stripeCustomer);

        return $this;
    }

    /**
     * @throws ApiErrorException
     * @throws InvalidRequestException
     */
    public function creatOneTimePrice(): PaymentServiceBuilder
    {
        $this->stripePrice = StripeProvider::createOneTimePrice(
            StripeProductID::getValueByLowerCaseKey($this->request['product']),
            $this->request['price']
        );

        return $this;
    }

    /**
     * @throws ApiErrorException
     * @throws InvalidRequestException
     */
    public function createSubscriptionPrice(): PaymentServiceBuilder
    {
        $this->stripePrice = StripeProvider::createSubscriptionPrice(
            StripeProductID::getValueByLowerCaseKey($this->request['product']),
            $this->request['price']
        );

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
                StripeProductID::getValueByLowerCaseKey($this->request['product'])
            ),
            'amount' => $this->request['price'],
            'currency' => 'jpy',
            'payment_schedule' => $paymentSchedule,
        ];

        return $this;
    }

    public function createSubscriptionMetadata(): PaymentServiceBuilder
    {
        $this->donor['stripe_customer_object'] = json_decode($this->donor['stripe_customer_object']);

        $this->subscriptionMetaData = [
            'donor_id' => $this->donor['donor_id'],
            'donor_name' => $this->donor['name'],
            'donor_external_id' => $this->donor['donor_external_id'],
            'donor_type' => $this->donor['type'],
            'donor_email' => $this->donor['email'],
            'donation_project' => StripeProvider::getProductNameFromId(
                StripeProductID::getValueByLowerCaseKey($this->request['product'])
            ),
            'amount' => $this->request['price'],
            'currency' => 'jpy',
            'payment_schedule' => PaymentSchedule::MONTHLY,
            'subscription_external_id' => Helpers::CreateExternalIdfromDate(),
        ];

        return $this;
    }

    /**
     * @throws ApiErrorException
     * @throws InvalidRequestException
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

    /**
     * @throws ApiErrorException
     * @throws InvalidRequestException
     */
    public function createSubscriptionSession(): array
    {
        $checkoutSession = StripeProvider::createSubscriptionSession(
            $this->stripeCustomer->id,
            $this->stripePrice->id,
            $this->subscriptionMetaData
        );

        return [
            'donor' => $this->donor,
            'stripe_checkout_session' => $checkoutSession,
            'stripe_price' => $this->stripePrice,
            'stripe_customer' => $this->stripeCustomer,
        ];
    }
}
