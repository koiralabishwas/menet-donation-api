<?php

namespace App\Http\Controllers;

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
use Stripe\Webhook;

class WebhookController extends Controller
{
    public function paymentIntentSucceed(Request $request): JsonResponse // for dev and prd use
    {
        // TODO: make these endpoints dynamic
        // endpoint secret for dev deploy
        //$endpoint_secret = 'whsec_T9qp3taSDglrSmrfCnHzfqC5laPRqb50'; // this differs in each endpoint
        // local cli webhook secret
        //        $endpoint_secret = env("STRIPE_WEBHOOK_SECRET"); // this differs in each endpoint

        try {
            $event = WebhookServices::constructWebhookEvent($request , env("stripe_webhook_secret"));
        } catch (UnexpectedValueException $e) {
            Log::error('Stripe webhook error: Invalid payload', ['exception' => $e]);

            return response()->json([
                'message' => 'Invalid payload',
                'url' => $request->getRequestUri(),
                'request' => ['headers' => $request->headers->all(), 'body' => $request->getContent()],
                'error' => $e->getMessage(),
            ], 400);
        } catch (SignatureVerificationException $e) {
            Log::error('Stripe webhook error: Invalid signature', ['exception' => $e]);

            return response()->json([
                'message' => 'Invalid signature',
                'url' => $request->getRequestUri(),
                'request' => ['headers' => $request->headers->all(), 'body' => $request->getContent()],
                'error' => $e->getMessage(),
            ], 400);
        }

        if ($event->type !== 'payment_intent.succeeded' || $event->data['object']->metadata->type !== 'ONE_TIME') {
            return response()->json([
                'message' => 'Invalid payload',
                'url' => $request->getRequestUri(),
                'request' => ['headers' => $request->headers->all(), 'body' => $request->getContent()],
            ], 400);
        }

        $paymentIntent = $event->data;
        $metaData = $paymentIntent['object']->metadata;

        DonationRepository::storeDonation($metaData, $paymentIntent['object']);

        try {
            Mail::to($paymentIntent['object']->receipt_email)->send(new DonationRegardMailable($metaData));
        } catch (Exception $e) {
            Log::error('Stripe webhook error: Failed to send email', ['exception' => $e]);

            return response()->json([
                'message' => 'Failed to send email',
                'url' => $request->getRequestUri(),
                'request' => ['headers' => $request->headers->all(), 'body' => $request->getContent()],
                'error' => $e->getMessage(),
            ], 400);
        }

        return response()->json([
            'message' => 'Success',
            'url' => $request->getRequestUri(),
            'request' => ['headers' => $request->headers->all(), 'body' => $request->getContent()],
        ], 200);
    }

    public function customerSubscriptionCreated(Request $request): JsonResponse
    {
        $endpoint_secret = 'whsec_mQjZ7AdLtBAFphXTugFMglLyNdPrbfAY';
        $payload = $request->getContent();
        $sig_header = $request->header('Stripe-Signature');

        try {
            $event = Webhook::constructEvent($payload, $sig_header, $endpoint_secret);
        } catch (UnexpectedValueException $e) {
            Log::error('Stripe webhook error: Invalid payload', ['exception' => $e]);

            return response()->json([
                'message' => 'Invalid payload',
                'url' => $request->getRequestUri(),
                'request' => ['headers' => $request->headers->all(), 'body' => $request->getContent()],
                'error' => $e->getMessage(),
            ], 400);
        } catch (SignatureVerificationException $e) {
            Log::error('Stripe webhook error: Invalid signature', ['exception' => $e]);

            return response()->json([
                'message' => 'Invalid signature',
                'url' => $request->getRequestUri(),
                'request' => ['headers' => $request->headers->all(), 'body' => $request->getContent()],
                'error' => $e->getMessage(),
            ], 400);
        }

        if ($event->type !== 'customer.subscription.created') {
            Log::error('Stripe webhook error: Invalid payload');

            return response()->json([
                'message' => 'Invalid payload',
                'url' => $request->getRequestUri(),
                'request' => ['headers' => $request->headers->all(), 'body' => $request->getContent()],
            ], 400);
        }

        $subscriptionData = $event->data;
        SubscriptionRepository::storeSubscription($subscriptionData['object']);

        // TODO : send notification mail

        return response()->json([
            'message' => 'Success',
            'url' => $request->getRequestUri(),
            'request' => ['headers' => $request->headers->all(), 'body' => $request->getContent()],
        ], 200);
    }
}
