<?php

namespace App\Services\Stripe;

use Exception;
use Illuminate\Http\Request;
use Stripe\Exception\SignatureVerificationException;
use Stripe\Exception\UnexpectedValueException;

class WebhookService
{
    private WebhookServiceBuilder $builder;
    // TODO :
    // make better Exceptions

    public function __construct(Request $request, string $webhookSecret)
    {
        $this->builder = new WebhookServiceBuilder($request, $webhookSecret);
    }

    /**
     * @throws SignatureVerificationException
     * @throws UnexpectedValueException
     * @throws Exception
     */
    public function paymentIntentSucceed(): array
    { // TODO: subscription型のpaymentの場合はイベントが発生してしまうが、回避しなければならない。
        $this->builder
            ->constructWebhookEvent()
            ->isSubscription()
            ->storeOneTimeDonation()
            ->sendOneTimeDonationEmail('寄付完了のお知らせ', 'mail.donationRegard');

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
            ->sendMonthlyDonationConfirmationEmail('毎月型の寄付設定完了のお知らせ', 'mail.SubscriptionCreatedMail');

        return [
            'message' => 'Success',
            'type' => 'customer.subscription.created',
        ];
    }

    /**
     * @throws Exception
     */
    public function invoicePaid(): array
    {
        $this->builder
            ->constructWebhookEvent()
            ->storeMonthlyDonation()
            ->sendMonthlyDonationEmail('今月分の寄付完了のお知らせ', 'mail.SubscriptionPaidMail');

        return [
            'message' => 'Success',
            'type' => 'invoice.paid',
        ];
    }
}
