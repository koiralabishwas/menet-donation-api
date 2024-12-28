<?php

namespace App\Http\Controllers;

use App\Providers\StripeProvider;
use Illuminate\Http\JsonResponse;
use Stripe\Exception\ApiErrorException;
use Stripe\Exception\InvalidRequestException;

class SubscriptionController extends Controller
{
    /**
     * @throws ApiErrorException
     * @throws InvalidRequestException
     */
    public function deleteSubscription($subscription_id): JsonResponse
    {
        // TODO: external ID を受け取って、 subscription_ID db から取る。
        // TODO: 撮った subscripton id を stripeProvider::cancelSubscriptionに叩く。
        // TODO: レスポンスを元に, subscription  table で　is_cancelledを1に変える。
        // TODO: レスポンスを元に userにキャンセル完了メールを送る。
        $cancelled_subscription = StripeProvider::cancelSubscription($subscription_id);

        //TODO: このデータを元に、管理者にメールにて通知する。
        return response()->json($cancelled_subscription);
    }
}
