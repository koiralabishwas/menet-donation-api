<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DonationFormRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'customer.name' => 'required|string',
            'customer.email' => 'required|email',
            'customer.phone' => 'required|string|regex:/^\d{10,11}$/', // Ensure the phone number is 10-11 digits
            'customer.is_public' => 'required|boolean', // Validation for is_public field
            'customer.display_name' => 'nullable|string', // Optional field
            'customer.corporate_no' => 'nullable|string', // Optional field
            'customer.message' => 'required|string', // Required message field
            'customer.address.country' => 'required|string',
            'customer.address.postal_code' => 'required|string|regex:/^\d{5}(-\d{4})?$/', // Example for US postal code
            'customer.address.city' => 'required|string',
            'customer.address.line1' => 'required|string',
            'customer.address.line2' => 'nullable|string', // Optional field
            'product_id' => 'required|string', // Product ID is required
            'price' => 'required|numeric', // Price is required and must be numeric
        ];
    }

    public function messages(): array
    {
        return [
            'customer.name.required' => 'Customer name is required.',
            'customer.phone.regex' => 'Phone number must be 10-11 digits.',
            'customer.is_public.required' => 'You must specify whether the customer is public or not.',
            'customer.message.required' => 'A message is required.',
            'customer.address.postal_code.regex' => 'Postal code must be in the format of 12345 or 12345-6789.',
            'product_id.required' => 'Product ID is required.',
            'price.required' => 'Price is required.',
        ];
    }
}
