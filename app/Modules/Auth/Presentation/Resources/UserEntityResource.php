<?php

namespace App\Modules\Auth\Presentation\Resources;

use App\Modules\Auth\Domain\Entities\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Resource for User domain entities.
 * Use UserResource for Eloquent models.
 */
class UserEntityResource extends JsonResource
{
    /**
     * @param User $resource
     */
    public function __construct($resource)
    {
        parent::__construct($resource);
    }

    public function toArray(Request $request): array
    {
        /** @var User $user */
        $user = $this->resource;

        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'preferences' => $user->preferences,
        ];
    }
}
