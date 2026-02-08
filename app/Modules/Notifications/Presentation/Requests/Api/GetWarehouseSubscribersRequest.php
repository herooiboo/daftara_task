<?php

namespace App\Modules\Notifications\Presentation\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class GetWarehouseSubscribersRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('manage-warehouse-subscriptions');
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
