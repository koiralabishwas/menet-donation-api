<?php

namespace App\Repositories;

use App\Models\Subscription;

class SubscriptionRepository
{
    public static function storeSubscription(object $stripeSubscriptionObject): Subscription
    {
        return Subscription::create([
            'subscription_external_id' => $stripeSubscriptionObject->metadata->subscription_external_id,
            'stripe_subscription_id' => $stripeSubscriptionObject->id,
            'donor_id' => $stripeSubscriptionObject->metadata->donor_id,
            'donor_external_id' => $stripeSubscriptionObject->metadata->donor_external_id,
            'donation_project' => $stripeSubscriptionObject->metadata->donation_project,
            'amount' => $stripeSubscriptionObject->metadata->amount,
            'currency' => $stripeSubscriptionObject->metadata->currency,
        ]);
    }

    public static function getStripeSubscriptionId(string $subscription_external_id)
    {
        return Subscription::query()->where('subscription_external_id', $subscription_external_id)->first();
    }

    public static function putCancelFlag(string $subscription_external_id): bool
    {
        return Subscription::query()->where('subscription_external_id', $subscription_external_id)->update([
            'is_cancelled' => 1,
        ]);
    }

    public static function removeCancelFlag(string $subscription_external_id): bool
    {
        return Subscription::query()->where('subscription_external_id', $subscription_external_id)->update([
            'is_cancelled' => 0,
        ]);
    }
}
