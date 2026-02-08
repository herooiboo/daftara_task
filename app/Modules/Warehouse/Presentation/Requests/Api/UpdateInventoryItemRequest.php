<?php

namespace App\Modules\Warehouse\Presentation\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class UpdateInventoryItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update-inventory-items');
    }

    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'string', 'max:255'],
            'sku' => ['sometimes', 'string', 'max:255', 'unique:inventory_items,SKU,' . $this->route('id')],
            'price' => ['sometimes', 'nullable', 'numeric', 'min:0'],
            'description' => ['sometimes', 'nullable', 'string'],
        ];
    }
}
