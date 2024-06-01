<?php

declare(strict_types=1);

namespace OtherCode\UserManagement\Application;

use OtherCode\UserManagement\Application\Contract\UserRepository;
use OtherCode\UserManagement\Domain\Exceptions\UserNotFound;
use OtherCode\UserManagement\Domain\User;

final readonly class UserFinder
{
    public function __construct(
        private UserRepository $repository
    ) {
    }

    public function byId(int $id): User
    {
        if (!$user = $this->repository->find($id)) {
            throw new UserNotFound("User $id not found");
        }

        return $user;
    }

    /**
     * @return array<User>
     */
    public function all(): array
    {
        return $this->repository->all();
    }
}
