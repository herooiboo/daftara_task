<?php

namespace App\Modules\Warehouse\Presentation\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class UpdateWarehouseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update-warehouses');
    }

    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'string', 'max:255'],
            'location' => ['sometimes', 'nullable', 'string', 'max:255'],
        ];
    }
}
