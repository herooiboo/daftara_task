<?php

namespace App\Modules\Auth\Application\Services;

use App\Modules\Auth\Application\DTOs\LoginResponseDTO;
use App\Modules\Auth\Domain\Contracts\Repositories\UserRepositoryInterface;
use App\Modules\Auth\Domain\Exceptions\InvalidCredentialsException;
use App\Modules\Auth\Presentation\DTOs\LoginDTO;
use Illuminate\Support\Facades\Hash;

class LoginService
{
    public function __construct(
        protected UserRepositoryInterface $userRepository,
    ) {}

    /**
     * @throws InvalidCredentialsException
     */
    public function handle(LoginDTO $dto): LoginResponseDTO
    {
        /** @var \App\Modules\Auth\Infrastructure\Models\User|null $userModel */
        $userModel = $this->userRepository->findByEmail($dto->email);

        if (! $userModel || ! Hash::check($dto->password, $userModel->password)) {
            throw new InvalidCredentialsException();
        }

        $token = $userModel->createToken('auth-token')->plainTextToken;

        // Convert to domain entity
        $user = \App\Modules\Auth\Domain\Entities\User::fromArray([
            'id' => $userModel->id,
            'name' => $userModel->name,
            'email' => $userModel->email,
            'preferences' => $userModel->preferences,
        ]);

        return new LoginResponseDTO(
            user: $user,
            token: $token,
        );
    }
}
