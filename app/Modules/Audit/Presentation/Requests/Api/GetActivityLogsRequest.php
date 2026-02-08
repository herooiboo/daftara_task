<?php

namespace App\Modules\Audit\Presentation\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class GetActivityLogsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('view-activity-logs');
    }

    public function rules(): array
    {
        return [
            'subject_type' => ['nullable', 'string'],
            'causer_id' => ['nullable', 'integer'],
            'event' => ['nullable', 'string'],
            'date_from' => ['nullable', 'date'],
            'date_to' => ['nullable', 'date', 'after_or_equal:date_from'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
            'page' => ['nullable', 'integer', 'min:1'],
        ];
    }
}
