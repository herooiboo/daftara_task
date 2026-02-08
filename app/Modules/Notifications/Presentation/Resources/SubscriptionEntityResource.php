<?php

namespace App\Modules\Notifications\Presentation\Resources;

use App\Modules\Notifications\Domain\Entities\WarehouseNotificationSubscription;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Resource for WarehouseNotificationSubscription domain entities.
 * Use SubscriptionResource for Eloquent models.
 */
class SubscriptionEntityResource extends JsonResource
{
    /**
     * @param WarehouseNotificationSubscription $resource
     */
    public function __construct($resource)
    {
        parent::__construct($resource);
    }

    public function toArray(Request $request): array
    {
        /** @var WarehouseNotificationSubscription $subscription */
        $subscription = $this->resource;

        return [
            'id' => $subscription->id,
            'user_id' => $subscription->userId,
            'warehouse_id' => $subscription->warehouseId,
        ];
    }
}
