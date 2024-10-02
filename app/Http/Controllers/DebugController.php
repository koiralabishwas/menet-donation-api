<?php

namespace App\Http\Controllers;


use App\Providers\StripeProvider;
use Illuminate\Http\JsonResponse;

class DebugController extends Controller
{
    public function index(): JsonResponse
    {
        $customer = StripeProvider::searchCustomerFromEmail("c@gmail.com");

        return response()->json($customer);


    }

    public function deleteAllCustomers() : JsonResponse
    {
        $deleteCustomer = StripeProvider::deleteAllCustomers();

        return response()->json($deleteCustomer);
    }
}
