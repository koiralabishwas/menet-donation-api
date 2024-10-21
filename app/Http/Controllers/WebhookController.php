<?php

namespace App\Http\Controllers;

use App\Mail\DonationRegardMailable;
use App\Repositories\DonationRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Stripe\Exception\SignatureVerificationException;
use Stripe\Webhook;

class WebhookController extends Controller
{
    /**
     * @throws SignatureVerificationException
     */
    public function create(Request $request): void
    {
        $endpoint_secret = env('STRIPE_WEBHOOK_SECRET');
        $payload = $request->getcontent();
        $sig_header = $request->header('Stripe-Signature');

        $event = Webhook::constructEvent(
            $payload, $sig_header, $endpoint_secret
        );
        switch ($event->type) {
            // for one-time payment
            case 'payment_intent.succeeded':
                $paymentIntent = $event->data;
                $metaData = $paymentIntent['object']->metadata;
//                Log::info($paymentIntent['object']);

                DonationRepository::storeDonation($metaData, $paymentIntent['object']);
                Mail::to($paymentIntent['object']->receipt_email)->send(new DonationRegardMailable($metaData));

                return;

                // ... handle other event types
            default:
        }
    }
}
