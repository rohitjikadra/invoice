<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDeliveryChallanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'customer_id' => ['required', 'integer', 'exists:customers,id'],
            'challan_date' => ['required', 'date'],
            'vehicle_no' => ['nullable', 'string', 'max:100'],
            'eway_bill_no' => ['nullable', 'string', 'max:100'],
            'quality' => ['nullable', 'string', 'max:255'],
            'broker' => ['nullable', 'string', 'max:255'],
            'receiver_name' => ['required', 'string', 'max:255'],
            'receiver_address' => ['required', 'string'],
            'receiver_gstin' => ['nullable', 'string', 'max:100'],
            'consignee_name' => ['required', 'string', 'max:255'],
            'consignee_address' => ['required', 'string'],
            'consignee_gstin' => ['nullable', 'string', 'max:100'],
            'remark' => ['nullable', 'string'],
            'meters' => ['required', 'array', 'min:1'],
            'meters.*' => ['required', 'numeric', 'gt:0'],
        ];
    }
}
