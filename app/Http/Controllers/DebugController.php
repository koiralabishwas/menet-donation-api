<?php

namespace App\Http\Controllers;

use App\Mail\DonationRegardMailable;
use App\Providers\StripeProvider;
use App\Repositories\DonationRepository;
use App\Repositories\DonorRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Stripe\Exception\SignatureVerificationException;
use Stripe\Webhook;

class DebugController extends Controller
{
    /**
     * @throws SignatureVerificationException
     */
    public function debugPaymentIntentSucceed(Request $request)
    {
        $endpoint_secret = env('STRIPE_WEBHOOK_SECRET');
        $payload = $request->getcontent();
        $sig_header = $request->header('Stripe-Signature');

        $event = Webhook::constructEvent(
            $payload, $sig_header, $endpoint_secret
        );
        switch ($event->type) {
            // for one-time payment checkout session
            case 'payment_intent.succeeded':   // to store one-time payment on db
                $paymentIntent = $event->data;
                $metaData = $paymentIntent['object']->metadata; // onetime のときのみここにmetadata 保存されている。
                if ($metaData->type === 'ONE_TIME') {
                    Log::info($metaData->type);
                    Log::info($paymentIntent['object']); // テスㇳでつかうため　、必要
                    //ここで残りのonetime時の処理
                    DonationRepository::storeDonation($metaData, $paymentIntent['object']);
                    Mail::to($paymentIntent['object']->receipt_email)->send(new DonationRegardMailable($metaData));
                }
                // catch and store subscription on db

                return;

            default:
        }
    }

    public function getStripeProductNameFromProductId(Request $request): JsonResponse
    {
        $productId = $request->query('product-id');
        $productName = StripeProvider::getProductNameFromId($productId);

        return response()->json($productName);
    }

    public function getStrpePriceByID(Request $request): JsonResponse
    {
        return response()->json(StripeProvider::searchPriceByPriceId($request->query('price-id')
        ));
    }

    public function createSubscriptionPrice(Request $request): JsonResponse
    {
        $productId = $request->query('product-id');
        $amount = $request->query('amount');

        return response()->json(StripeProvider::createSubscriptionPrice($productId, $amount));
    }

    public function getStripeCustomerFromEmail(Request $request): JsonResponse
    {
        $email = $request->query('email');
        $customer = StripeProvider::searchCustomerFromEmail($email);

        return response()->json($customer->data[0]);
    }

    public function checkCreateCustomer(Request $request): JsonResponse
    {
        $customer = StripeProvider::createCustomer($request['customer']);

        return response()->json($customer);
    }

    public function getDbCustomerObjFromEmail(Request $request): JsonResponse
    {
        $email = $request->query('email');
        $customer = DonorRepository::getDonorByEmail($email);

        return response()->json($customer);

    }

    public function deleteAllCustomers(): JsonResponse
    {
        $deleteCustomer = StripeProvider::deleteAllCustomers();

        return response()->json($deleteCustomer);
    }
}
