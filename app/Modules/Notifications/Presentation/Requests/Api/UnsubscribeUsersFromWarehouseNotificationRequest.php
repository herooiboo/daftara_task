<?php

namespace App\Modules\Notifications\Presentation\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class UnsubscribeUsersFromWarehouseNotificationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('manage-warehouse-subscriptions');
    }

    public function rules(): array
    {
        return [
            'user_ids' => ['required', 'array', 'min:1'],
            'user_ids.*' => ['required', 'integer', 'exists:users,id'],
            'warehouse_id' => ['required', 'integer', 'exists:warehouses,id'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'warehouse_id' => (int) $this->route('id'),
        ]);
    }
}