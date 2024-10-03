<?php

namespace App\Http\Controllers;


use App\Providers\StripeProvider;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DebugController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $email = $request->query('email');
        $customer = StripeProvider::searchCustomerFromEmail($email);

        return response()->json($customer);


    }

    public function deleteAllCustomers() : JsonResponse
    {
        $deleteCustomer = StripeProvider::deleteAllCustomers();

        return response()->json($deleteCustomer);
    }
}
