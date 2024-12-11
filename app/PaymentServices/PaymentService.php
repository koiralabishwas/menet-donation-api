<?php

namespace App\PaymentServices;

use App\Enums\PaymentSchedule;
use App\Http\Requests\DonationFormRequest;

class PaymentService
{
    private PaymentServiceBuilder $builder;

    public function __construct(DonationFormRequest $request)
    {
        $this->builder = new PaymentServiceBuilder($request);
    }

    public function oneTimePayment(): array
    {
        return $this->builder
            ->validate()
            ->createCustomer()
            ->creatOneTimePrice()
            ->storeDonor()
            ->createMetadata(PaymentSchedule::ONE_TIME)
            ->createCheckoutSession();
    }

    public function monthlyPayment(): array
    {
        return $this->builder
            ->validate()
            ->createCustomer()
            ->createSubscriptionPrice()
            ->storeDonor()
            ->createMetadata(PaymentSchedule::MONTHLY)
            ->createSubscriptionSession();
    }
}
