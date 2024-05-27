<?php

declare(strict_types=1);

namespace OtherCode\UserManagement\Infrastructure\Persistence;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Id\{AssignedGenerator, IdentityGenerator};
use Doctrine\ORM\Mapping\ClassMetadata;
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
            ->find($id);
    }

    public function all(): array
    {
        return $this->entityManager
            ->getRepository(User::class)
            ->findAll();
    }

    public function save(User $user): User
    {
        $metadata = $this->entityManager->getClassMetaData(User::class);

        if (is_int($user->id())) {
            $metadata->setIdGeneratorType(ClassMetadata::GENERATOR_TYPE_NONE);
            $metadata->setIdGenerator(new AssignedGenerator());
        }

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        if (is_int($user->id())) {
            $metadata->setIdGeneratorType(ClassMetadata::GENERATOR_TYPE_IDENTITY);
            $metadata->setIdGenerator(new IdentityGenerator());
        }

        return $user;
    }

    public function delete(User $user): void
    {
        $this->entityManager->remove($user);
        $this->entityManager->flush();
    }
}
