<?php

namespace App\Repositories;

use App\Models\Donor;
use Stripe\Customer;

class DonorRepository
{
    /**
     * Store a new Donor record in the database.
     */
    public static function storeDonor(array $donorData, object $stripeCustomer): Donor
    {
        // NOTE :リーピータ客の場合、 上書きしたい項目は $donorData　から渡す
        // NOTE : 上書き必要ないものは一回$stripeCustomerのままで
        return Donor::updateOrCreate(
            [
                'email' => $stripeCustomer->email, // If email exists, update it
            ],
            [
                'donor_external_id' => $stripeCustomer->metadata->donor_external_id,  // Custom external ID
                'stripe_customer_id' => $stripeCustomer->id, // Stripe customer ID
                'type' => $stripeCustomer->metadata->type,   // Customer type from Stripe response
                'name' => $stripeCustomer->name,             // Customer name from Stripe response
                'email' => $stripeCustomer->email,           // Customer email from Stripe response
                'phone' => $stripeCustomer->phone,           // Customer phone from Stripe response
                'country_code' => $stripeCustomer->address->country,  // Country code from Stripe address
                'postal_code' => $stripeCustomer->address->postal_code, // Postal code from Stripe address
                'address' => implode(', ', [
                    $stripeCustomer->address->city,        // City from Stripe address
                    $stripeCustomer->address->line1,       // Line 1 from Stripe address
                    $stripeCustomer->address->line2,       // Line 2 from Stripe address
                ]),
                'is_public' => $donorData['customer']['is_public'], // Is public from request data
                'public_name' => $donorData['customer']['public_name'], // Display name from request data
                'corporate_no' => $donorData['customer']['corporate_no'], // Corporate number from request data
                'message' => $donorData['customer']['message'],         // Message from request data
                'stripe_customer_object' => json_encode($stripeCustomer), // Entire Stripe customer object as JSON
            ]
        );
    }

    public static function getDonorByEmail(string $email)
    {
        $donor = Donor::query()->where('email', $email)->first();

        if (! empty($donor)) {
            return json_decode($donor['stripe_customer_object']);
        }

        return null;

    }

    public static function getDonorByExternalId(string $externalId)
    {
        return Donor::query()->where('donor_external_id', $externalId)->first();
    }
}
