<?php

namespace App\Modules\Warehouse\Presentation\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class DestroyWarehouseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('delete-warehouses');
    }

    public function rules(): array
    {
        return [
            'id' => ['required', 'integer', 'exists:warehouses,id'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'id' => (int) $this->route('id'),
        ]);
    }
}
