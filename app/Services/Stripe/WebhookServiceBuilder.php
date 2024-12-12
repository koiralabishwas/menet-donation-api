<?php

namespace App\Services\Stripe;

use App\Enums\WebhookSecret;
use App\Mail\DonationRegardMailable;
use App\Models\Donation;
use App\Models\Subscription;
use App\Repositories\DonationRepository;
use App\Repositories\SubscriptionRepository;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Stripe\Exception\SignatureVerificationException;
use Stripe\Exception\UnexpectedValueException;
use Stripe\Webhook;
use Stripe\Event;

class WebhookServiceBuilder
{
    private Request $request;
    private WebhookSecret $webhookSecret;
    private object $webhookEvent;
    private Donation $donation;
    private Subscription $subscription;
    private object $metaData;
    private object $paymentIntentObject;

    public function __construct(Request $request , WebhookSecret $webhookSecret)
    {
        $this->request = $request;
        $this->webhookSecret = $webhookSecret;
    }


    /**
     * @throws SignatureVerificationException
     * @throws UnexpectedValueException
     */

    public function constructWebhookEvent() : WebhookServiceBuilder
    {
        $payload = $this->request->getContent();
        $sig_header = $this->request->header('Stripe-Signature');
        $endpoint_secret = $this->webhookSecret->value;

        $this->webhookEvent = Webhook::constructEvent(
            $payload,
            $sig_header,
            $endpoint_secret,
        );
        return $this;
    }

    public function storeDonation() : WebhookServiceBuilder
    {
        $paymentIntent = $this->webhookEvent->data['object'];
        $metadata = $paymentIntent->metadata;
        DonationRepository::storeDonation($metadata , $paymentIntent);

        Log::info($paymentIntent);
        Log::info($metadata);

        // TODO
        // mail execption処理も欠かさず
        return $this;
    }

    public function storeSubscription() : WebhookServiceBuilder
    {
        $subscription = $this->webhookEvent->data['object'];
        SubscriptionRepository::storeSubscription($subscription);
        return $this;
    }


    public function sendRegardMail()
    {
        $receipt = $this->webhookEvent->data['object']->receipt_email;
        $metaData = $this->webhookEvent->data['object']->metadata;

        Mail::to($receipt)->send(new DonationRegardMailable($metaData));
        return $this;
    }
}
