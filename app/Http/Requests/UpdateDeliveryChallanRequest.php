<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDeliveryChallanRequest extends FormRequest
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
            'meters' => [
                'required',
                'array',
                'size:48',
                function (string $attribute, mixed $value, \Closure $fail): void {
                    $meters = collect($value)->map(
                        fn (mixed $meter): string => trim((string) ($meter ?? ''))
                    );

                    $hasAtLeastOneValue = $meters->contains(
                        fn (string $meter): bool => $meter !== ''
                    );

                    if (!$hasAtLeastOneValue) {
                        $fail('At least one meter entry is required.');
                        return;
                    }

                    $foundEmpty = false;
                    foreach ($meters as $meter) {
                        if ($meter === '') {
                            $foundEmpty = true;
                            continue;
                        }

                        if ($foundEmpty) {
                            $fail('Meter entries must be filled line by line without gaps.');
                            return;
                        }
                    }
                },
            ],
            'meters.*' => ['nullable', 'numeric', 'gt:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'meters.size' => 'Exactly 48 meter entries are required.',
            'meters.*.numeric' => 'Meter value must be numeric.',
            'meters.*.gt' => 'Meter value must be greater than 0.',
        ];
    }
}
