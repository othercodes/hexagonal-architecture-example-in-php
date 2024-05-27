<?php

declare(strict_types=1);

use OtherCode\UserManagement\Domain\User;
use OtherCode\UserManagement\Infrastructure\Persistence\DoctrineUserRepository;
use OtherCode\UserManagement\Infrastructure\Persistence\JsonFileUserRepository;

describe('UserRepository', function () {

    test('should persist a `User`', function (string $type) {
        $repository = $this->makeRepository($type, 'save');

        $user = $repository->save(User::create('Vincent Vega'));

        expect($user)->toBeInstanceOf(User::class)
            ->and($user->name)->toBeString('Vincent Vega')
            ->and($user->id())->toBeGreaterThan(0);

        $this->persistenceClean($type, 'save');
    });

    test('should find a `User` by unique ID', function (string $type) {
        $this->persistenceSeed($type, 'find', [
            ['id' => 1, 'name' => 'Vincent Vega']
        ]);

        $repository = $this->makeRepository($type, 'find');
        $user = $repository->find(1);

        expect($user)->toBeInstanceOf(User::class)
            ->and($user->name)->toBeString()
            ->and($user->id())->toBeInt();

        $this->persistenceClean($type, 'find');
    });

    test('should not find a `User` by unique ID', function (string $type) {
        $repository = $this->makeRepository($type, 'notfind');
        $user = $repository->find(1);

        expect($user)->toBeNull();
    });

    test('should return all `User`s', function (string $type) {
        $this->persistenceSeed($type, 'all', 3);

        $repository = $this->makeRepository($type, 'all');
        $all = $repository->all();

        expect($all)->toBeArray()
            ->and($all)->toHaveCount(3)
            ->and($all)->each->toBeInstanceOf(User::class);

        $this->persistenceClean($type, 'all');
    });

    test('should delete a `User`', function (string $type) {
        $this->persistenceSeed($type, 'delete', [
            ['id' => 1, 'name' => 'Vincent Vega']
        ]);

        $repository = $this->makeRepository($type, 'delete');
        $user = $repository->find(1);

        $repository->delete($user);
        $user = $repository->find(1);

        expect($user)->toBeNull();

        $this->persistenceClean($type, 'delete');
    });

})->with([
    'DoctrineUserRepository' => [DoctrineUserRepository::class],
    'JsonFileUserRepository' => [JsonFileUserRepository::class],
]);
