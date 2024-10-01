<?php

namespace App\Repositories;

use App\Models\Donation;
use App\Models\Donor;
use Stripe\Customer;
use Stripe\PaymentIntent;

class DonaionRepository
{
    /**
     * @param array $DonationData
     * @param PaymentIntent $stripePaymentIntent
     * @return void
     */
    public static function storeDonaion(array $DonationData , PaymentIntent $stripePaymentIntent)
    {
        return Donation::create([

        ]);
    }
}
