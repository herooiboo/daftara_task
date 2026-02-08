<?php

namespace App\Modules\Auth\Application\Services;

use Illuminate\Http\Request;

class LogoutService
{
    public function handle(Request $request): void
    {
        $request->user()->currentAccessToken()->delete();
    }
}
