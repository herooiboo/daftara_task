<?php

namespace App\Modules\Warehouse\Presentation\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class CreateStockTransferRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create-stock-transfers');
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'created_by' => $this->user()->id,
        ]);
    }

    public function rules(): array
    {
        return [
            'inventory_id' => ['required', 'integer', 'exists:inventory_items,id'],
            'base_warehouse_id' => ['required', 'integer', 'exists:warehouses,id', 'different:target_warehouse_id'],
            'target_warehouse_id' => ['required', 'integer', 'exists:warehouses,id'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'created_by' => ['nullable', 'integer'],
        ];
    }
}
