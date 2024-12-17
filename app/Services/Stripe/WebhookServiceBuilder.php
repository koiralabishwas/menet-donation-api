<?php

namespace App\Services\Stripe;

use App\Mail\DonationRegardMailable;
use App\Repositories\DonationRepository;
use App\Repositories\SubscriptionRepository;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Stripe\Event;
use Stripe\Exception\SignatureVerificationException;
use Stripe\Exception\UnexpectedValueException;
use Stripe\Webhook;

class WebhookServiceBuilder
{
    private Request $request;

    private string $webhookSecret;

    private Event $webhookEvent;

    private object $metaData;

    public function __construct(Request $request, string $webhookSecret)
    {
        $this->request = $request;
        $this->webhookSecret = $webhookSecret;
    }

    /**
     * @throws SignatureVerificationException
     * @throws UnexpectedValueException
     */
    public function constructWebhookEvent(): WebhookServiceBuilder
    {
        $payload = $this->request->getContent();
        $sig_header = $this->request->header('Stripe-Signature');
        $endpoint_secret = $this->webhookSecret;

        $this->webhookEvent = Webhook::constructEvent(
            $payload,
            $sig_header,
            $endpoint_secret,
        );

        return $this;
    }

    /**
     * @throws Exception
     */
    public function storeOneTimeDonation(): WebhookServiceBuilder
    {
        $paymentIntent = $this->webhookEvent->data->object;
        $this->metaData = $paymentIntent->metadata;
        DonationRepository::storeDonation($this->metaData, $paymentIntent);

        return $this;
    }

    public function storeMonthlyDonation(): WebhookServiceBuilder
    {
        $invoice = $this->webhookEvent->data->object;
        $this->metaData = $invoice->subscription_details->metadata;
        DonationRepository::storeDonation($this->metaData, $invoice);

        return $this;
    }

    public function storeSubscription(): WebhookServiceBuilder
    {
        $subscription = $this->webhookEvent->data->object;
        $this->metaData = $subscription->metadata;
        SubscriptionRepository::storeSubscription($subscription);

        return $this;
    }

    // TODO Send EMAIL FUNCTION
    // メールノ内容とテンプレートをparameterでわたして、送信させるのいいかも？
    public function sendEmail(string $mailView): void
    {
        $receipt = $this->metaData->donor_email;
        $metaData = $this->metaData;

        Mail::to($receipt)->send(new DonationRegardMailable($metaData, $mailView));
    }
}
