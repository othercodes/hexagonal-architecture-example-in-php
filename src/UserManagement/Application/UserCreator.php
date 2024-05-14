<?php

declare(strict_types=1);

namespace OtherCode\UserManagement\Application;

use OtherCode\UserManagement\Application\Contract\UserRepository;
use OtherCode\UserManagement\Domain\User;

final readonly class UserCreator
{
    public function __construct(
        private UserRepository $repository
    ) {
    }

    public function create(string $name): User
    {
        return $this->repository->save(User::create($name));
    }
}
