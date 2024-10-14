<?php

namespace App\Http\Controllers;

use App\Mail\DonationRegardMailable;
use App\Repositories\DonationRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Stripe\Exception\SignatureVerificationException;
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
//            case 'checkout.session.completed':
                // Access the payment intent data
                $paymentIntent = $event->data;
                $metaData = $paymentIntent['object']->metadata;

                // Access customer data from the payment intent
                // Log customer data
//                Log::info($paymentIntent["object"]->metadata);
                Log::info($paymentIntent["object"]);
                DonationRepository::storeDonation($metaData , $paymentIntent["object"]);
                Mail::to($paymentIntent['object']->receipt_email)->send(new DonationRegardMailable($metaData));



                return response()->json(['data' => $paymentIntent]);

            // ... handle other event types
            default:
                return response()->json($event->type);
        }
    }
}
