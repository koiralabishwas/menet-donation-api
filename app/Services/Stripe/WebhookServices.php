<?php

namespace App\Services\Stripe;

use Illuminate\Http\Request;
use Stripe\Event;
use Stripe\Exception\SignatureVerificationException;
use Stripe\Webhook;
use UnexpectedValueException;

class WebhookServices
{
    /**
     * @throws SignatureVerificationException
     * @throws UnexpectedValueException
     */
    public static function constructWebhookEvent(Request $request , string $endpoint_secret) : Event
    {
        $payload = $request->getContent();
        $sig_header = $request->header('Stripe-Signature');
        return Webhook::constructEvent($payload, $sig_header,$endpoint_secret);
    }
}
