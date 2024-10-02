<?php

namespace App\Http\Controllers;

use App\Helpers\Helpers;
use App\Models\Donation;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Stripe\Customer;
use Stripe\Exception\ApiErrorException;
use Stripe\Exception\SignatureVerificationException;
use Stripe\Stripe;
use Stripe\Webhook;

class WebhookController extends Controller
{
    /**
     * @throws SignatureVerificationException
     */
    public function create(): JsonResponse
    {
        $endpoint_secret = 'whsec_66d1bc562f01853a93c4c10ab740b0bbd30aa4084a2fd9e5a300473917bc2f8f';

        $payload = @file_get_contents('php://input');
        $sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];

        $event = Webhook::constructEvent(
            $payload, $sig_header, $endpoint_secret
        );

        switch ($event->type) {
            // for one-time payment
            case 'payment_intent.succeeded':
                // Access the payment intent data
                $paymentIntent = $event->data->object;

                // Access customer data from the payment intent
                $customerData = $paymentIntent->customer;

                // Log customer data
                Log::info('Customer Data', ['customer' => $customerData]);

                // Optionally retrieve additional customer info from Stripe (if needed)
                // $customer = Customer::retrieve($customerData);

                // Example of saving to the database
                // Donation::create([
                //     "donation_external_id" => Helpers::CreateExternalIdfromDate(),
                //     "donor_id" => $customer->id, // or $paymentIntent->customer if you don't need to retrieve additional data
                // ]);

                return response()->json(['customer' => $customerData]);

            // ... handle other event types
            default:
                return response()->json($event->type);
        }
    }
}
