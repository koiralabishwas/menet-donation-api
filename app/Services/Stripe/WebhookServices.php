<?php

namespace App\Services\Stripe;

use App\Enums\WebhookSecret;
use Exception;
use Illuminate\Http\Request;

class WebhookServices
{
    private WebhookServiceBuilder $builder;
    // TODO :
    //paymentIntentSucceed function
    //customerSubscrioptionCreated function
    //invoicePaid function
    // make better Exceptions

    public function __construct(Request $request, WebhookSecret $webhookSecret)
    {
        $this->builder = new WebhookServiceBuilder($request, $webhookSecret);
    }

    public function paymentIntentSucceed(): array
    {
        try {
            $this->builder
                ->constructWebhookEvent()
                ->storeDonation()
                ->sendRegardMail();

            return [
                'message' => 'Success',
                'type' => 'payment_intent.succeeded',
            ];
        } catch (Exception $e) {
            return [$e->getMessage()];
        }
    }

    public function customerSubscriptionCreated(): array
    {
        try {
            $this->builder
                ->constructWebhookEvent()
                ->storeSubscription()
                ->sendRegardMail();

            return [
                'message' => 'Success',
                'type' => 'customer.subscription.created',
            ];
        } catch (Exception $e) {
            return [$e->getMessage()];
        }
    }
}
