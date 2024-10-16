<?php

namespace App\Repositories;

use App\Helpers\Helpers;
use App\Models\Donation;

class DonationRepository
{
    /**
     *  store donation record in database
     *
     * @param object $DonationData
     * @param object $StripePaymentIntentObject
     * @return Donation
     *
     *
     */
    public static function storeDonation(object $DonationData , object $StripePaymentIntentObject) : Donation
    {
        return Donation::create(
            [
                "donation_external_id" => Helpers::CreateExternalIdfromDate(),
                "donor_id" => $DonationData['donor_id'],
                "donor_external_id" => $DonationData['donor_external_id'],
                "subscription_external_id" => $DonationData['subscription_external_id'],
                "stripe_subscription_id" => $DonationData['stripe_subscription_id'],
                "donation_project" => $DonationData['donation_project'],
                "amount" => $DonationData['amount'],
                "currency" => $DonationData['currency'],
                "type" => $DonationData['type'],
                "tax_deduction_certificate_url" => $DonationData['tax_deduction_certificate_url'],
                "stripe_donation_object" => json_encode($StripePaymentIntentObject),
            ]
        );
    }

    public static function getDonationsByUserExternalId(string $donor_external_id) : array
    {
        return Donation::query()->where('donor_external_id', $donor_external_id)->get()->toArray();
    }

    public static function getDonationCertificate(string $donor_external_id , string $year) : array
    {
        return Donation::query()->where('donor_external_id' , $donor_external_id)->whereyear('created_at', $year)->get()->toArray();
    }
}
