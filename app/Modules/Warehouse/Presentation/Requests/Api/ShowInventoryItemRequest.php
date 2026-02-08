<?php

namespace App\Modules\Warehouse\Presentation\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class ShowInventoryItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('view-inventory-items');
    }

    public function rules(): array
    {
        return [
            'id' => ['required', 'integer', 'exists:inventory_items,id'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'id' => (int) $this->route('id'),
        ]);
    }
}
