<?php

namespace App\Modules\Auth\Application\Services;

use App\Modules\Auth\Infrastructure\Models\User;
use Illuminate\Http\Request;

class GetProfileService
{
    public function handle(Request $request): User
    {
        return $request->user();
    }
}
