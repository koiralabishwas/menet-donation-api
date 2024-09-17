<?php

namespace App\Repositories;

use App\Models\Donor;
use Stripe\Customer;
use function MongoDB\BSON\toJSON;

class DonorRepository
{
    /**
     * Store a new Donor record in the database.
     *
     * @param array $DonorData
     * @param Customer $stripeCustomer
     * @param string $externalId
     * @return Donor
     */
    public static function storeDonor(array $DonorData , Customer $stripeCustomer , string $externalId): Donor
    {
        return Donor::create([
            'donor_external_id' => $externalId,  // Custom external ID
            'stripe_customer_id' => $stripeCustomer->id, // Stripe customer ID
            'name' => $stripeCustomer->name,             // Customer name from Stripe response
            'email' => $stripeCustomer->email,           // Customer email from Stripe response
            'phone' => $stripeCustomer->phone,           // Customer phone from Stripe response
            'country_code' => $stripeCustomer->address['country'],  // Country code from Stripe address
            'postal_code' => $stripeCustomer->address['postal_code'], // Postal code from Stripe address
            'address' => implode(', ', [
                $stripeCustomer->address['city'],        // City from Stripe address
                $stripeCustomer->address['line1'],       // Line 1 from Stripe address
                $stripeCustomer->address['line2'],       // Line 2 from Stripe address
            ]),
            'is_public' => $DonorData['customer'] ['is_public'], // Is public from request data
            'display_name' => $DonorData['customer']['display_name'], // Display name from request data
            'corporate_no' => $DonorData['customer']['corporate_no'], // Corporate number from request data
            'message' => $DonorData['customer']['message'],         // Message from request data
            'stripe_customer_object' =>json_encode($stripeCustomer), // Entire Stripe customer object as JSON
        ]);
    }

}
