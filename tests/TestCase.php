<?php

namespace Tests;

use OtherCode\UserManagement\Application\Contract\UserRepository;
use OtherCode\UserManagement\Domain\User;
use OtherCode\UserManagement\Infrastructure\Persistence\DoctrineUserRepository;
use OtherCode\UserManagement\Infrastructure\Persistence\JsonFileUserRepository;
use PHPUnit\Framework\TestCase as BaseTestCase;
use Doctrine\ORM\EntityManager;

use function OtherCode\Shared\Infrastructure\Persistence\Doctrine\provideEntityManger;
use function Pest\Faker\fake;

abstract class TestCase extends BaseTestCase
{
    private static array $repositories = [];

    public function configuration(string $section = null): array
    {
        $config = require __DIR__.'/../configuration.php';
        return is_null($section)
            ? $config
            : $config[$section];
    }

    public function makeRepository(string $type, string $context = 'default'): UserRepository
    {
        if (!isset(self::$repositories["$type::$context"])) {
            $fn = match ($type) {
                DoctrineUserRepository::class => function (string $context, array $config) {
                    return new DoctrineUserRepository(provideEntityManger($config['database']));
                },
                JsonFileUserRepository::class => function (string $context, array $config) {
                    if ($context !== 'default') {
                        $config['json']['path'] = "{$config['json']['path']}/$context";
                    }

                    return new JsonFileUserRepository(
                        $config['json']['path']
                    );
                },
            };

            self::$repositories["$type::$context"] = $fn($context, $this->configuration('persistence'));
        }

        return self::$repositories["$type::$context"];
    }

    public function persistenceClean(string $type, string $context = 'default'): void
    {
        $repository = $this->makeRepository($type, $context);
        foreach ($repository->all() as $user) {
            $repository->delete($user);
        }
    }

    public function persistenceSeed(string $type, string $context = 'default', int|array $seed = 1): array
    {
        $generator = match (gettype($seed)) {
            'integer' => function (int $amount) {
                foreach (range(1, $amount) as $_) {
                    yield User::create(fake()->name());
                }
            },
            'array' => function (array $items) {
                foreach ($items as $item) {
                    yield new User(...$item);
                }
            }
        };

        $seeded = [];

        $repository = $this->makeRepository($type, $context);
        foreach ($generator($seed) as $item) {
            $seeded[] = $repository->save($item)->id();
        }

        return $seeded;
    }
}
