<?php

namespace App\Modules\Notifications\Presentation\Responses\Api;

use App\Modules\Notifications\Presentation\Resources\SubscriptionResource;
use Dust\Base\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

class SubscribeUsersToWarehouseNotificationResponse extends Response
{
    protected function createResource(mixed $resource): mixed
    {
        /** @var Collection $subscriptions */
        $subscriptions = $resource;

        return response()->json([
            'success' => true,
            'data' => [
                'subscribed_count' => $subscriptions->count(),
                'subscriptions' => SubscriptionResource::collection($subscriptions),
            ],
        ], SymfonyResponse::HTTP_CREATED);
    }
}