<?php

declare(strict_types=1);

namespace OtherCode\Shared\Infrastructure\Persistence\Doctrine;

use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;

/**
 * @param  array<string, array<string, mixed>>  $parameters
 * @return EntityManager
 */
function provideEntityManager(array $parameters = []): EntityManager
{
    $parameters = array_replace_recursive([
        'orm' => [
            'paths' => [],
            'isDevMode' => true,
        ],
        'connection' => [
            'driver' => 'pdo_sqlite',
            'path' => '',
        ]
    ], $parameters);

    $config = ORMSetup::createXMLMetadataConfiguration(...$parameters['orm']);
    $connection = DriverManager::getConnection($parameters['connection'], $config);

    return new EntityManager($connection, $config);
}
