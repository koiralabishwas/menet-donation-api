<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DonationFormRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'customer' => 'required|array',
            'customer.name' => 'required|string',
            'customer.email' => 'required|email',
            'customer.phone' => 'required|string', // 海外の電話番号を考慮してstringのみ
            'customer.is_public' => 'required|boolean', // Validation for is_public field
            'customer.display_name' => 'nullable|string',
            'customer.corporate_no' => 'nullable|string',
            'customer.message' => 'nullable|string',
            'customer.address' => 'required|array',
            'customer.address.country' => 'required|string',
            'customer.address.postal_code' => 'nullable|string',
            'customer.address.city' => 'required|string',
            'customer.address.line1' => 'required|string',
            'customer.address.line2' => 'nullable|string',
            'product_id' => 'required|string', // StripeのプロダクトID
            'price' => 'required|numeric', // 寄付額
        ];
    }
}
