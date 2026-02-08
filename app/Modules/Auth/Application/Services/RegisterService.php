<?php

namespace App\Modules\Auth\Application\Services;

use App\Modules\Auth\Domain\Contracts\Repositories\UserRepositoryInterface;
use App\Modules\Auth\Domain\Contracts\Services\RoleServiceInterface;
use App\Modules\Auth\Domain\Exceptions\UserAlreadyExistsException;
use App\Modules\Auth\Presentation\DTOs\RegisterDTO;

class RegisterService
{
    public function __construct(protected
        UserRepositoryInterface $userRepository, protected
        RoleServiceInterface $roleService,
        )
    {
    }

    /**
     * @throws UserAlreadyExistsException
     */
    public function handle(RegisterDTO $dto): object
    {
        $existing = $this->userRepository->findByEmail($dto->email);

        if ($existing) {
            throw new UserAlreadyExistsException();
        }

        $user = $this->userRepository->create([
            'name' => $dto->name,
            'email' => $dto->email,
            'password' => $dto->password,
            'preferences' => ['notification_channel' => 'email'],
        ]);

        $staffRole = $this->roleService->findByName('staff', 'api');
        if ($staffRole) {
            $this->roleService->assignToUser($user, $staffRole);
        }

        return $user;
    }
}
