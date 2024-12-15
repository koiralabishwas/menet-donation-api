<?php

namespace App\Http\Controllers;

use App\Services\Stripe\WebhookServices;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    public function paymentIntentSucceed(Request $request): JsonResponse // for dev and prd use
    {
        try {
            $event = new WebhookServices(
                $request,
                // Localで stripecliで実行する時、.env の　STRIPE_PAYMENT_INTENT_SUCCEED_SECRET　以外の webhook secretをコメントアウトしてください。
                env('STRIPE_PAYMENT_INTENT_SUCCEED_SECRET', env('STRIPE_LOCAL_WEBHOOK_SECRET'))
            );
            $data = $event->paymentIntentSucceed();

            return response()->json([
                'status' => 201,
                'message' => 'success',
                'data' => $data,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ], 400);
        }
    }

    public function customerSubscriptionCreated(Request $request): JsonResponse
    {
        try {
            $event = new WebhookServices(
                $request,
                env('STRIPE_CUSTOMER_SUBSCRIPTION_CREATED_SECRET', env('STRIPE_LOCAL_WEBHOOK_SECRET'))
            );
            $data = $event->customerSubscriptionCreated();
            Log::info($request);

            return response()->json([
                'status' => 201,
                'message' => 'success',
                'data' => $data,
            ]);

        } catch (Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ], 400);
        }

    }

    public function invoicePaid(Request $request): JsonResponse
    {
        Log::info($request);
        return response()->json($request);
    }
}
