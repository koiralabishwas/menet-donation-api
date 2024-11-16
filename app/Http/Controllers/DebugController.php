<?php

namespace App\Http\Controllers;

use App\Providers\StripeProvider;
use App\Repositories\DonorRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DebugController extends Controller
{
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
