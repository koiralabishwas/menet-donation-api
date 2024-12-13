<?php

namespace App\Http\Controllers;

use App\Enums\WebhookSecret;
use App\Services\Stripe\WebhookServices;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    public function paymentIntentSucceed(Request $request): JsonResponse // for dev and prd use
    {
        Log::debug($request);
        try {
            $job = new WebhookServices(
                $request,
                WebhookSecret::PAYMENT_INTENT_SUCCEED_SECRET
            );
            $data = $job->paymentIntentSucceed();

            return response()->json([
                'status' => 201,
                'message' => 'success',
                'data' => $data,
            ]);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function customerSubscriptionCreated(Request $request): JsonResponse
    {
        try {
            $job = new WebhookServices(
                $request,
                WebhookSecret::CUSTOMER_SUBSCRIPTION_CREATED_SECRET
            );
            $data = $job->customerSubscriptionCreated();

        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }

        return response()->json($data);

    }
}
