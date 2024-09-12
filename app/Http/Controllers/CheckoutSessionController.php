<?php

namespace App\Http\Controllers;

use App\Helpers\Helpers;
use App\Models\Donor;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Stripe\Customer;
use Stripe\Stripe;

class CheckoutSessionController extends Controller
{
    /**
     * @throws ValidationException
     */
    public function create(Request $request) : JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'customer.name' => 'required|string',
            'customer.email' => 'required|email',
            'customer.phone' => 'required|string|size:11',
            'customer.is_public' => 'required|boolean',
            'customer.display_name' => 'nullable|string',
            'customer.corporate_no' => 'nullable|string',
            'customer.message' => 'required|string',
            'customer.address.country' => 'required|string',
            'customer.address.postal_code' => 'required|string',
            'customer.address.city' => 'required|string',
            'customer.address.line1' => 'required|string',
            'customer.address.line2' => 'required|string',
            'product_id' => 'required|string',
            'price' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $data = $validator->validated();

        // Initialize strip
        try {
            Stripe::setApiKey(env('STRIPE_SECRET_KEY'));
            // first create a custom external_id
            $external_id = Helpers::generateUuid();
            $customer = Customer::create([
                'name' => $data['customer']['name'],
                'email' => $data['customer']['email'],
                'phone' => $data['customer']['phone'],
                'address' => [
                    'country' => $data['customer']['address']['country'],
                    'postal_code' => $data['customer']['address']['postal_code'],
                    'city' => $data['customer']['address']['city'],
                    'line1' => $data['customer']['address']['line1'],
                    'line2' => $data['customer']['address']['line2'],
                ],
                'metadata' => ['donor_external_id' => $external_id],
            ]);

//            $donor = Donor::create([
//                'donor_external_id' => $external_id,
//                'stripe_customer_id' => $customer->id,
//                'name' => $data['customer']['name'],
//                'email' => $data['customer']['email'],
//                'phone' => $data['customer']['phone'],
//                'country_code' => $data['customer']['address']['country'],
//                'postal_code' => $data['customer']['address']['postal_code'],
//                'address' => implode(', ', [
//                    $data['customer']['address']['city'],
//                    $data['customer']['address']['line1'],
//                    $data['customer']['address']['line2'],
//                ]),
//                'is_public' => $data['customer']['is_public'],
//                'display_name' => $data['customer']['display_name'],
//                'corporate_no' => $data['customer']['corporate_no'],
//                'message' => $data['customer']['message'],
//                'stripe_customer_object' => json_encode($customer),
//            ]);




        }catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }


        return response()->json($customer);
    }
}
