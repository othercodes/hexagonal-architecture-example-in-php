<?php

declare(strict_types=1);

use OtherCode\UserManagement\Application\Contract\UserRepository;
use OtherCode\UserManagement\Application\UserFinder;
use OtherCode\UserManagement\Domain\Exceptions\UserNotFound;
use OtherCode\UserManagement\Domain\User;

describe('UserFinder', function () {
    test('should find a `User` by give ID.', function () {
        $repository = Mockery::mock(UserRepository::class);
        $repository->expects('find')
            ->with(Mockery::type('int'))
            ->once()
            ->andReturnUsing(fn(int $id) => new User($id, 'Vincent Vega'));

        $service = new UserFinder($repository);

        expect($service->byId(42))->toBeInstanceOf(User::class);
    });

    test('should throw `UserNotFound` exception when a `User` is not found by the given ID.', function () {
        $repository = Mockery::mock(UserRepository::class);
        $repository->expects('find')
            ->with(Mockery::type('int'))
            ->once()
            ->andReturnUsing(fn(int $id) => null);

        $service = new UserFinder($repository);
        $service->byId(42);
    })->throws(UserNotFound::class, 'User 42 not found');

    test('should return all `User`s', function () {
        $repository = Mockery::mock(UserRepository::class);
        $repository->expects('all')
            ->withNoArgs()
            ->once()
            ->andReturnUsing(fn() => []);

        $service = new UserFinder($repository);

        expect($service->all())->toBeArray();
    });
});

