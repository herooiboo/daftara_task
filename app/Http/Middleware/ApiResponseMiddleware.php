<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ApiResponseMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if ($response instanceof JsonResponse) {
            $original = $response->getData(true);

            $wrapped = [
                'status' => $response->getStatusCode() < 400 ? 'success' : 'error',
                'code' => $response->getStatusCode(),
                'data' => $original,
                'message' => $response->getStatusCode() < 400
                ? ($original['message'] ?? null)
                : ($original['message'] ?? 'Something went wrong'),
            ];

            return response()->json($wrapped, $response->getStatusCode());
        }

        return $response;
    }
}
