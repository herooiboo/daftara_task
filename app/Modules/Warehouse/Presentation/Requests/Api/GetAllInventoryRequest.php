<?php

namespace App\Modules\Warehouse\Presentation\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class GetAllInventoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('view-inventory');
    }

    public function rules(): array
    {
        return [
            'warehouse_id' => ['nullable', 'integer', 'exists:warehouses,id'],
            'name' => ['nullable', 'string', 'max:255'],
            'sku' => ['nullable', 'string', 'max:255'],
            'price_min' => ['nullable', 'numeric', 'min:0'],
            'price_max' => ['nullable', 'numeric', 'min:0', 'gte:price_min'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
            'page' => ['nullable', 'integer', 'min:1'],
        ];
    }
}
