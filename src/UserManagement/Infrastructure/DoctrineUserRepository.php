<?php

declare(strict_types=1);

namespace OtherCode\UserManagement\Infrastructure;

use Doctrine\ORM\EntityManagerInterface;
use OtherCode\UserManagement\Application\Contract\UserRepository;
use OtherCode\UserManagement\Domain\User;


readonly class DoctrineUserRepository implements UserRepository
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {
    }

    public function find(int $id): ?User
    {
        return $this->entityManager
            ->getRepository(User::class)
            ->find(User::class, $id);
    }

    public function all(): array
    {
        return $this->entityManager
            ->getRepository(User::class)
            ->findAll();
    }

    public function save(User $user): User
    {
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }
}
