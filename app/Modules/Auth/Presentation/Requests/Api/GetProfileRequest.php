<?php

namespace App\Modules\Auth\Presentation\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class GetProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [];
    }
}
