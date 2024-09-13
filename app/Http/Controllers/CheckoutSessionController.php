<?php

namespace App\Http\Controllers;

use App\Helpers\Helpers;
use App\Models\Donor;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Stripe\Customer;
use Stripe\Stripe;

class CheckoutSessionController extends Controller
{
    /**
     * @throws ValidationException
     */
    public function create(Request $request): JsonResponse
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

        $donor = new Donor();

        // Initialize strip
        try {
            Stripe::setApiKey(env('STRIPE_SECRET_KEY'));

            // Generate a custom external_id for tracking
            $external_id = Helpers::generateUuid();

            // Create the customer in Stripe
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

            // Store the customer information in the database
            $donor = Donor::create([
                'donor_external_id' => $external_id,  // Custom external ID
                'stripe_customer_id' => $customer->id, // Stripe customer ID
                'name' => $customer->name,             // Customer name from Stripe response
                'email' => $customer->email,           // Customer email from Stripe response
                'phone' => $customer->phone,           // Customer phone from Stripe response
                'country_code' => $customer->address['country'],  // Country code from Stripe address
                'postal_code' => $customer->address['postal_code'], // Postal code from Stripe address
                'address' => implode(', ', [
                    $customer->address['city'],        // City from Stripe address
                    $customer->address['line1'],       // Line 1 from Stripe address
                    $customer->address['line2'],       // Line 2 from Stripe address
                ]),
                'is_public' => $data['customer']['is_public'],     // Is public from request data
                'display_name' => $data['customer']['display_name'], // Display name from request data
                'corporate_no' => $data['customer']['corporate_no'], // Corporate number from request data
                'message' => $data['customer']['message'],         // Message from request data
                'stripe_customer_object' => json_encode($customer), // Entire Stripe customer object as JSON
            ]);

            $donor->stripe_customer_object = json_decode($donor->stripe_customer_object , true);




            return response()->json(['donor' => $donor])->setStatusCode(201);
        } catch (Exception $e) {
            // Log the error and return a 500 error response
            Log::error('Error creating donor and Stripe customer: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
