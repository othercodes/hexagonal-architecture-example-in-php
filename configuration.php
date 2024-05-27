<?php

declare(strict_types=1);

return [
    'persistence' => [
        'json' => [
            'path' => __DIR__.'/data/json',
        ],
        'database' => [
            'connection' => [
                'driver' => 'pdo_sqlite',
                'path' => __DIR__.'/data/database.sqlite',
            ],
            'orm' => [
                'paths' => [
                    __DIR__.'/src/UserManagement/Infrastructure/Persistence/orm'
                ]
            ]
        ]
    ]
];