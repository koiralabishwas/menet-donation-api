<?php

namespace App\Services\Stripe;

use App\Enums\PaymentSchedule;
use Stripe\Exception\ApiErrorException;
use Stripe\Exception\InvalidRequestException;

class PaymentService
{
    private PaymentServiceBuilder $builder;

    public function __construct(array $request)
    {
        $this->builder = new PaymentServiceBuilder($request);
    }

    /**
     * @throws ApiErrorException
     * @throws InvalidRequestException
     */
    public function oneTimePayment(): array
    {
        return $this->builder
            ->createCustomer()
            ->storeDonor()
            ->creatOneTimePrice()
            ->createMetadata(PaymentSchedule::ONE_TIME)
            ->createCheckoutSession();
    }

    /**
     * @throws ApiErrorException
     * @throws InvalidRequestException
     */
    public function monthlyPayment(): array
    {
        return $this->builder
            ->createCustomer()
            ->storeDonor()
            ->createSubscriptionPrice()
            ->createSubscriptionMetadata()
            ->createSubscriptionSession();
    }
}
