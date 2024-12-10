<?php

namespace App\PaymentServices;

use App\Http\Requests\DonationFormRequest;

class OneTimePayment
{
    private OneTimePaymentBuilder $builder;

    public function __construct(DonationFormRequest $request)
    {
        $this->builder = new OneTimePaymentBuilder($request);
    }

    public function processPayment(): array
    {
        return $this->builder
            ->validate()
            ->createCustomer()
            ->createPrice()
            ->storeDonor()
            ->createMetadata()
            ->createCheckoutSession();
    }
}
