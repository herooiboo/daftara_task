<?php

namespace App\Modules\Auth\Application\Services;

use Illuminate\Http\Request;

class GetProfileService
{
    public function handle(Request $request): object
    {
        return $request->user();
    }
}
