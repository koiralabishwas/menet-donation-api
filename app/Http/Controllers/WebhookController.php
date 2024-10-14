<?php

namespace App\Http\Controllers;

use App\Mail\DonationRegardMailable;
use App\Repositories\DonationRepository;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Stripe\Exception\SignatureVerificationException;
use Stripe\Webhook;

class WebhookController extends Controller
{
    /**
     * @throws SignatureVerificationException
     */
    public function create(): void
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
                $paymentIntent = $event->data;
                $metaData = $paymentIntent['object']->metadata;
                Log::info($paymentIntent["object"]);

                DonationRepository::storeDonation($metaData , $paymentIntent["object"]);
                Mail::to($paymentIntent['object']->receipt_email)->send(new DonationRegardMailable($metaData));
                return;


            // ... handle other event types
            default:
        }
    }
}
