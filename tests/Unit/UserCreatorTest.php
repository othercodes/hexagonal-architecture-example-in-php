<?php

declare(strict_types=1);

use OtherCode\UserManagement\Application\Contract\UserRepository;
use OtherCode\UserManagement\Application\UserCreator;
use OtherCode\UserManagement\Domain\User;

describe('UserCreator', function () {

    test('should create a new `User` from the given data.', function () {
        $repository = Mockery::mock(UserRepository::class);
        $repository->expects('save')
            ->with(Mockery::type(User::class))
            ->once()
            ->andReturnUsing(fn(User $user) => new User(1, $user->name));

        $creator = new UserCreator($repository);
        $user = $creator->create('Vincent Vega');

        expect($user->name)->toBe('Vincent Vega')
            ->and($user->id())->toBeGreaterThan(0);
    });

});