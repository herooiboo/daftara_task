<?php

namespace App\Modules\Auth\Presentation\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Resource for User Eloquent models.
 * Use UserEntityResource for domain entities.
 */
class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'preferences' => $this->preferences,
            'roles' => method_exists($this->resource, 'getRoleNames') ? $this->resource->getRoleNames() : [],
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
