<?php

namespace App\Modules\Warehouse\Presentation\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class IndexStockTransferRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('view-stock-transfers');
    }

    public function rules(): array
    {
        return [
            'base_warehouse_id' => ['nullable', 'integer', 'exists:warehouses,id'],
            'target_warehouse_id' => ['nullable', 'integer', 'exists:warehouses,id'],
            'inventory_id' => ['nullable', 'integer', 'exists:inventory_items,id'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
            'page' => ['nullable', 'integer', 'min:1'],
        ];
    }
}
