<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
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
            case 'payment_intent.succeeded':
                $paymentIntent = $event->data;
                Log::info('Payment Intent Succeeded', ['payment_intent' => $paymentIntent]);
                return response()->json($paymentIntent);
            // ... handle other event types
            default:
                echo 'Received unknown event type ' . $event->type;
                return response()->json($event->data);
        };
    }
}
