<?php

namespace App\Http\Controllers;

use App\Enums\WebhookSecret;
use App\Helpers\EnvHelpers;
use App\Mail\DonationRegardMailable;
use App\Repositories\DonationRepository;
use App\Repositories\SubscriptionRepository;
use App\Services\Stripe\WebhookServices;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Stripe\Exception\SignatureVerificationException;
use Stripe\Exception\UnexpectedValueException;

class WebhookController extends Controller
{
    public function paymentIntentSucceed(Request $request): JsonResponse // for dev and prd use
    {
        try {
            $job = new WebhookServices(
                $request,
                WebhookSecret::PAYMENT_INTENT_SUCCEED_SECRET
            );
            $data = $job->paymentIntentSucceed();

            return response()->json($data);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }

        // TODO:ここのtrycatch みたいなもの実装しなければならないから、一旦おいておく。
        //        try {
        //            $event = WebhookServices::constructWebhookEvent(
        //                $request,
        //                EnvHelpers::getWebhookSecret(WebhookSecret::PAYMENT_INTENT_SUCCEED_SECRET)
        //            );
        //        } catch (UnexpectedValueException $e) {
        //            Log::error('Stripe webhook error: Invalid payload', ['exception' => $e]);
        //
        //            return response()->json([
        //                'message' => 'Invalid payload',
        //                'url' => $request->getRequestUri(),
        //                'request' => ['headers' => $request->headers->all(), 'body' => $request->getContent()],
        //                'error' => $e->getMessage(),
        //            ], 400);
        //        } catch (SignatureVerificationException $e) {
        //            Log::error('Stripe webhook error: Invalid signature', ['exception' => $e]);
        //
        //            return response()->json([
        //                'message' => 'Invalid signature',
        //                'url' => $request->getRequestUri(),
        //                'request' => ['headers' => $request->headers->all(), 'body' => $request->getContent()],
        //                'error' => $e->getMessage(),
        //            ], 400);
        //        }
        //
        //        if ($event->type !== 'payment_intent.succeeded' || $event->data['object']->metadata->type !== 'ONE_TIME') {
        //            return response()->json([
        //                'message' => 'Invalid payload',
        //                'url' => $request->getRequestUri(),
        //                'request' => ['headers' => $request->headers->all(), 'body' => $request->getContent()],
        //            ], 400);
        //        }
        //
        //        $paymentIntent = $event->data;
        //        $metaData = $paymentIntent['object']->metadata;
        //
        //        DonationRepository::storeDonation($metaData, $paymentIntent['object']);
        //
        //        try {
        //            Mail::to($paymentIntent['object']->receipt_email)->send(new DonationRegardMailable($metaData));
        //        } catch (Exception $e) {
        //            Log::error('Stripe webhook error: Failed to send email', ['exception' => $e]);
        //
        //            return response()->json([
        //                'message' => 'Failed to send email',
        //                'url' => $request->getRequestUri(),
        //                'request' => ['headers' => $request->headers->all(), 'body' => $request->getContent()],
        //                'error' => $e->getMessage(),
        //            ], 400);
        //        }
        //
        //        return response()->json([
        //            'message' => 'Success',
        //            'url' => $request->getRequestUri(),
        //            'request' => ['headers' => $request->headers->all(), 'body' => $request->getContent()],
        //        ]); // default status  が　200だから、わざわざ書かなくていいみたい
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

        //        try {
        //            $event = WebhookServices::constructWebhookEvent(
        //                $request,
        //                EnvHelpers::getWebhookSecret(WebhookSecret::CUSTOMER_SUBSCRIPTION_CREATED_SECRET)
        //            );
        //        } catch (UnexpectedValueException $e) {
        //            Log::error('Stripe webhook error: Invalid payload', ['exception' => $e]);
        //
        //            return response()->json([
        //                'message' => 'Invalid payload',
        //                'url' => $request->getRequestUri(),
        //                'request' => ['headers' => $request->headers->all(), 'body' => $request->getContent()],
        //                'error' => $e->getMessage(),
        //            ], 400);
        //        } catch (SignatureVerificationException $e) {
        //            Log::error('Stripe webhook error: Invalid signature', ['exception' => $e]);
        //
        //            return response()->json([
        //                'message' => 'Invalid signature',
        //                'url' => $request->getRequestUri(),
        //                'request' => ['headers' => $request->headers->all(), 'body' => $request->getContent()],
        //                'error' => $e->getMessage(),
        //            ], 400);
        //        }
        //
        //        if ($event->type !== 'customer.subscription.created') {
        //            Log::error('Stripe webhook error: Invalid payload');
        //
        //            return response()->json([
        //                'message' => 'Invalid payload',
        //                'url' => $request->getRequestUri(),
        //                'request' => ['headers' => $request->headers->all(), 'body' => $request->getContent()],
        //            ], 400);
        //        }
        //
        //        $subscriptionData = $event->data;
        //        SubscriptionRepository::storeSubscription($subscriptionData['object']);
        //        Log::info($subscriptionData['object']);
        //
        //        // TODO : send notification mail
        //
        //        return response()->json([
        //            'message' => 'Success',
        //            'url' => $request->getRequestUri(),
        //            'request' => ['headers' => $request->headers->all(), 'body' => $request->getContent()],
        //        ]);
    }
}
