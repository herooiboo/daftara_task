<?php

namespace App\Modules\Notifications\Presentation\Responses\Api;

use App\Modules\Notifications\Presentation\Resources\SubscriptionEntityResource;
use Dust\Base\Response;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

class SubscribeUsersToWarehouseNotificationResponse extends Response
{
    protected function createResource(mixed $resource): mixed
    {
        /** @var array $subscriptions */
        $subscriptions = $resource;

        return response()->json([
            'success' => true,
            'data' => [
                'subscribed_count' => count($subscriptions),
                'subscriptions' => SubscriptionEntityResource::collection($subscriptions),
            ],
        ], SymfonyResponse::HTTP_CREATED);
    }
}