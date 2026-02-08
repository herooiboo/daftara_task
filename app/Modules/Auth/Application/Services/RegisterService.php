<?php

namespace App\Modules\Auth\Application\Services;

use App\Modules\Auth\Domain\Exceptions\UserAlreadyExistsException;
use App\Modules\Auth\Infrastructure\Models\User;
use App\Modules\Auth\Infrastructure\Repositories\UserRepository;
use App\Modules\Auth\Presentation\DTOs\RegisterDTO;
use Spatie\Permission\Models\Role;

class RegisterService
{
    public function __construct(
        protected UserRepository $userRepository,
    ) {}

    /**
     * @throws UserAlreadyExistsException
     */
    public function handle(RegisterDTO $dto): User
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

        $staffRole = Role::findByName('staff', 'api');
        if ($staffRole) {
            $user->assignRole($staffRole);
        }

        return $user;
    }
}
