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

    /**
     * @throws Exception
     */
    public function paymentIntentSucceed(): array
    {
        $this->builder
            ->constructWebhookEvent()
            ->storeDonation()
            ->sendRegardMail();

        return [
            'message' => 'Success',
            'type' => 'payment_intent.succeeded',
        ];
    }

    /**
     * @throws Exception
     */
    public function customerSubscriptionCreated(): array
    {
        $this->builder
            ->constructWebhookEvent()
            ->storeSubscription()
            ->sendRegardMail();

        return [
            'message' => 'Success',
            'type' => 'customer.subscription.created',
        ];
    }
}
