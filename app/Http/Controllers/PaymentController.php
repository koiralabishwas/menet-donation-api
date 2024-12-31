<?php

namespace App\Http\Controllers;

use App\Http\Requests\DonationFormRequest;
use App\Providers\StripeProvider;
use App\Repositories\DonorRepository;
use App\Services\Stripe\PaymentService;
use Illuminate\Http\JsonResponse;
use Stripe\Exception\ApiErrorException;
use Stripe\Exception\InvalidRequestException;

class PaymentController extends Controller
{
    /**
     * @throws ApiErrorException
     * @throws InvalidRequestException
     */
    public function onetime(DonationFormRequest $request): JsonResponse
    {
        $validatedRequest = $request->validated();

        $paymentService = new PaymentService($validatedRequest);
        $data = $paymentService->oneTimePayment();

        return response()->json([
            'status' => 201,
            'message' => 'success',
            'data' => $data,
        ], 201);
    }

    /**
     * @throws ApiErrorException
     * @throws InvalidRequestException
     */
    public function monthly(DonationFormRequest $request): JsonResponse
    {
        $validatedRequest = $request->validated();

        $paymentService = new PaymentService($validatedRequest);
        $data = $paymentService->monthlyPayment();

        return response()->json([
            'status' => 201,
            'message' => 'success',
            'data' => $data,
        ], 201);
    }

    public function managePayments($donor_external_id): JsonResponse
    {
        //TODO ; webhook event for this i.e  customer.subscription.updated
        $stripe_customer_id = DonorRepository::getStripeCustomerId($donor_external_id);
        $portalSession = StripeProvider::createPortalSession($stripe_customer_id);

        return response()->json([
            'status' => 201,
            'message' => 'success',
            'data' => $portalSession,
        ], 201);
    }
}
