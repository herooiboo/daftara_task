<?php

namespace App\Modules\Auth\Application\Services;

use App\Modules\Auth\Application\DTOs\LoginResponseDTO;
use App\Modules\Auth\Domain\Exceptions\InvalidCredentialsException;
use App\Modules\Auth\Infrastructure\Repositories\UserRepository;
use App\Modules\Auth\Presentation\DTOs\LoginDTO;
use Illuminate\Support\Facades\Hash;

class LoginService
{
    public function __construct(
        protected UserRepository $userRepository,
    ) {}

    /**
     * @throws InvalidCredentialsException
     */
    public function handle(LoginDTO $dto): LoginResponseDTO
    {
        $user = $this->userRepository->findByEmail($dto->email);

        if (! $user || ! Hash::check($dto->password, $user->password)) {
            throw new InvalidCredentialsException();
        }

        $token = $user->createToken('auth-token')->plainTextToken;

        return new LoginResponseDTO(
            user: $user,
            token: $token,
        );
    }
}
