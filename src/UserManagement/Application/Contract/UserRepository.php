<?php

declare(strict_types=1);

namespace OtherCode\UserManagement\Application\Contract;

use OtherCode\UserManagement\Domain\User;

interface UserRepository
{
    public function find(int $id): ?User;

    /**
     * @return array<User>
     */
    public function all(): array;

    public function save(User $user): User;

    public function delete(User $user): void;
}
