<?php

namespace App\Repositories;

use App\Models\Subscription;

class SubscriptionRepository
{
    public static function StoreOrUpdate(object $stripeSubscriptionObject, bool $cancel_at_period_end): Subscription
    {
        return Subscription::updateOrCreate(
            ['subscription_external_id' => $stripeSubscriptionObject->metada->subscription_external_id],
            [
                'subscription_external_id' => $stripeSubscriptionObject->metadata->subscription_external_id,
                'stripe_subscription_id' => $stripeSubscriptionObject->id,
                'donor_id' => $stripeSubscriptionObject->metadata->donor_id,
                'donor_external_id' => $stripeSubscriptionObject->metadata->donor_external_id,
                'donation_project' => $stripeSubscriptionObject->metadata->donation_project,
                'amount' => $stripeSubscriptionObject->metadata->amount,
                'currency' => $stripeSubscriptionObject->metadata->currency,
                'is_cancelled' => $cancel_at_period_end ? 1 : 0,
            ]
        );
    }

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

    public static function setIsCancelled(string $subscription_external_id, bool $cancel_at_period_end): int
    {
        //        NOTE : true の　場合 isCancelled が　1 , false の　場合　0
        return Subscription::query()->where('subscription_external_id', $subscription_external_id)->update([
            'is_cancelled' => $cancel_at_period_end ? 1 : 0,
        ]);
    }
}
