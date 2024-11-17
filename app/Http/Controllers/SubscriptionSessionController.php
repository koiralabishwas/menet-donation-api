<?php

namespace App\Http\Controllers;

use App\Enums\StripeProductID;
use App\Http\Requests\DonationFormRequest;
use App\Providers\StripeProvider;
use App\Repositories\DonorRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SubscriptionSessionController extends Controller
{
    public function create(DonationFormRequest $request): JsonResponse
    {

        $formData = $request->validated();

        try {
            $stripeCustomer = StripeProvider::createCustomer($formData['customer']);
            $donor = DonorRepository::storeDonor($formData, $stripeCustomer);
            $donor['stripe_customer_object'] = json_decode($donor['stripe_customer_object']);
            #TODO : need to implement
            $stripePrice = StripeProvider::createOneTimePrice(StripeProductID::getValueByLowerCaseKey($formData['product']), $formData['price']);

            $paymentIntentMetaData = [
                'donor_id' => $donor['donor_id'],
                'donor_name' => $donor['name'],
                'donor_external_id' => $donor['donor_external_id'],
                'donor_type' => $donor['type'],
                'donor_email' => $donor['email'],
                'donation_project' => StripeProvider::getProductNameFromId(StripeProductID::getValueByLowerCaseKey($formData['product'])),
                'amount' => $formData['price'],
                'currency' => 'jpy',
                'type' => 'ONE_TIME',
            ];

            $checkoutSession = StripeProvider::createCheckoutSession($stripeCustomer->id, $stripePrice->id, $paymentIntentMetaData);
            $formData = [
                'donor' => $donor,
                'stripe_checkout_session' => $checkoutSession,
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
            ], 500);
        }
    }
}
