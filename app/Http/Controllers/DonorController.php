<?php

namespace App\Http\Controllers;

use App\Models\Donor;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DonorController extends Controller
{
    public function register(Request $request): JsonResponse
    {
        // Define validation rules
        $validator = Validator::make($request->all(), [
            'donor_external_id' => 'required|string|max:36|unique:donors,donor_external_id',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:donors,email|max:255',
            'phone' => 'required|string|max:15',
            'country_code' => 'required|string|max:2',
            'postal_code' => 'required|string|max:10',
            'address' => 'required|string|max:255',
            'is_public' => 'boolean',
            'display_name' => 'nullable|string|max:255',
            'corporate_no' => 'nullable|string|max:20',
            'message' => 'nullable|string',
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors(),
            ], 400); // 400 Bad Request
        }

        // Create a new donor record
        $donor = Donor::create($request->all());

        // Return the newly created donor as JSON
        return response()->json([
            'status' => 'success',
            'data' => $donor,
        ], 201); // 201 Created
    }

    public function list(){
        return response()->json(["hello" => "world"]);
    }
}
