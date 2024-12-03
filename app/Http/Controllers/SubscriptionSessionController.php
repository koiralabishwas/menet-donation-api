<?php

namespace App\Http\Controllers;

use App\Enums\StripeProductID;
use App\Http\Requests\DonationFormRequest;
use App\Providers\StripeProvider;
use App\Repositories\DonorRepository;
use Exception;
use Illuminate\Http\JsonResponse;

class SubscriptionSessionController extends Controller
{
    public function create(DonationFormRequest $request): JsonResponse
    {

        $formData = $request->validated();

        try {
            $stripeCustomer = StripeProvider::createCustomer($formData['customer']);
            $donor = DonorRepository::storeDonor($formData, $stripeCustomer);
            $donor['stripe_customer_object'] = json_decode($donor['stripe_customer_object']);
            // searchs for subscription price , if null , creates one
            $stripePrice = StripeProvider::createSubscriptionPrice(StripeProductID::getValueByLowerCaseKey($formData['product']), $formData['price']);

            $subscriptionDataMetaData = [
                'donor_id' => $donor['donor_id'],
                'donor_name' => $donor['name'],
                'donor_external_id' => $donor['donor_external_id'],
                'donor_type' => $donor['type'],
                'donor_email' => $donor['email'],
                'donation_project' => StripeProvider::getProductNameFromId(StripeProductID::getValueByLowerCaseKey($formData['product'])),
                'amount' => $formData['price'],
                'currency' => 'jpy',
                'type' => 'MONTHLY', //TODO :まともな名前かんがえようか
            ];

            $subscriptionSession = StripeProvider::createSubscriptionSession($stripeCustomer->id, $stripePrice->id, $subscriptionDataMetaData);
            $formData = [
                'donor' => $donor,
                'stripe_subscription_session' => $subscriptionSession,
                'stripe_price' => $stripePrice,
                'stripe_customer' => $stripeCustomer,
            ];

            return response()->json([
                'status' => 201,
                'message' => 'success',
                'data' => $formData,
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                $e,
            ], 500);
        }
    }
}
