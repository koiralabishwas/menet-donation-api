<?php

namespace App\Http\Controllers;

use App\Http\Requests\DonationFormRequest;
use App\Repositories\DonorRepository;
use Exception;
use Illuminate\Http\JsonResponse;
use App\Providers\StripeProvider;

class CheckoutSessionController extends Controller
{

    public function create(DonationFormRequest $request): JsonResponse
    {

        $formData = $request->validated();

        try {
            //TO FIX? : 毎回おなじdonorで新しいexternalID作られるから、DBとmetadataに保存したところでよ、、、、、

            $stripeCustomer = StripeProvider::createCustomer($formData['customer']);

            $donor = DonorRepository::storeDonor($formData, $stripeCustomer);
            $donor['stripe_customer_object'] = json_decode($donor['stripe_customer_object']);

            $stripePrice = StripeProvider::createPrice($formData['product_id'], $formData['price']);


            $paymentIntentMetaData = [
                "donor_id" => $donor['donor_id'],
                "donor_name" => $donor['name'],
                "donor_external_id" => $donor['donor_external_id'],
                "donation_project" => StripeProvider::getProductNameFromId($formData['product_id']) ,
                "amount" => $formData['price'],
                "currency" => "jpy",
                "type" => "ONE_TIME",
                "tax_deduction_certificate_url" => "https://www.google.com//".$donor['donor_external_id'],
            ];


            $checkoutSession = StripeProvider::createCheckoutSession($stripeCustomer->id, $stripePrice->id , $paymentIntentMetaData);

            $formData = [
                "donor" => $donor,
                "stripe_checkout_session" => $checkoutSession,
                'stripe_price' => $stripePrice,
                "stripe_customer" => $stripeCustomer,
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
