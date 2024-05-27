<?php

declare(strict_types=1);

namespace OtherCode\UserManagement\Infrastructure\Persistence;

use OtherCode\UserManagement\Application\Contract\UserRepository;
use OtherCode\UserManagement\Domain\User;

readonly class JsonFileUserRepository implements UserRepository
{
    private string $directory;

    public function __construct(string $directory)
    {
        $this->directory = $directory.'/users';
        !is_dir($this->directory) && mkdir($this->directory, 0777, true);
    }

    public function find(int $id): ?User
    {
        $path = "{$this->directory}/$id.json";
        return file_exists($path)
            ? new User(...json_decode(file_get_contents($path), true))
            : null;
    }

    public function all(): array
    {
        return array_map(
            fn(string $path) => new User(...json_decode(file_get_contents($path), true)),
            glob("{$this->directory}/*.json")
        );
    }

    public function save(User $user): User
    {
        if (is_null($user->id())) {
            $user = new User(count(glob("{$this->directory}/*.json")) + 1, $user->name);
        }

        file_put_contents("{$this->directory}/{$user->id()}.json", json_encode([
            'id' => $user->id(),
            'name' => $user->name,
        ]));

        return $user;
    }

    public function delete(User $user): void
    {
        $file = "{$this->directory}/{$user->id()}.json";
        if (file_exists($file)) {
            unlink($file);
        }
    }
}
