<?php

namespace App\Modules\Notifications\Presentation\Responses\Api;

use Dust\Base\Response;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

class UnsubscribeUsersFromWarehouseNotificationResponse extends Response
{
    protected function createResource(mixed $resource): mixed
    {
        $unsubscribedCount = (int) $resource;

        return response()->json([
            'success' => true,
            'data' => [
                'unsubscribed_count' => $unsubscribedCount,
                'message' => "Successfully unsubscribed {$unsubscribedCount} user(s) from warehouse notifications.",
            ],
        ]);
    }
}