<?php

namespace App\Modules\Auth\Tests\Feature\Api;

use App\Modules\Auth\Infrastructure\Models\User;
use Tests\TestCase;

/**
 * @group auth
 */
class LogoutTest extends TestCase
{
    public function test_authenticated_user_can_logout(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('test-token');

        $response = $this->postJson('/api/v1/auth/logout', [], [
            'Authorization' => 'Bearer ' . $token->plainTextToken,
        ]);

        $response->assertStatus(200);

        // Verify token was revoked
        $this->assertDatabaseMissing('personal_access_tokens', [
            'id' => $token->accessToken->id,
        ]);
    }

    public function test_unauthenticated_user_cannot_logout(): void
    {
        $response = $this->postJson('/api/logout');

        $response->assertStatus(401);
    }
}
