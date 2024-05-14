<?php

declare(strict_types=1);

use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;

$config = ORMSetup::createXMLMetadataConfiguration(
    paths: [__DIR__."/src/UserManagement/Infrastructure/orm"],
    isDevMode: true,
);

// configuring the database connection
$connection = DriverManager::getConnection([
    'driver' => 'pdo_sqlite',
    'path' => __DIR__.'/db.sqlite',
], $config);

// obtaining the entity manager
return new EntityManager($connection, $config);
