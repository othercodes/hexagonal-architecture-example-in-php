<?php

declare(strict_types=1);

namespace OtherCode\UserManagement\Infrastructure\Persistence;

use Exception;
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

    /**
     * @param  string  $filename
     * @return array{id: int, name: string}
     * @throws Exception
     */
    private function getJSONFileData(string $filename): array
    {
        if (!file_exists($filename)) {
            throw new Exception("Unable to find file: $filename");
        }

        $content = file_get_contents($filename);
        if ($content === false) {
            throw new Exception("Unable to read file: $filename");
        }

        $data = json_decode($content, true);
        if ($data === null) {
            throw new Exception("Unable to decode the data from file: $filename");
        }

        /** @var array{id: int, name: string} */
        return $data;
    }

    /**
     * @return array<string>
     */
    private function getJSONFileList(): array
    {
        return ($all = glob("{$this->directory}/*.json"))
            ? $all
            : [];
    }

    public function find(int $id): ?User
    {
        try {
            return new User(...$this->getJSONFileData("{$this->directory}/$id.json"));
        } catch (Exception $e) {
            return null;
        }
    }

    public function all(): array
    {
        return array_reduce(
            $this->getJSONFileList(),
            function (array $list, string $filepath): array {
                try {
                    $list[] = new User(...$this->getJSONFileData($filepath));
                } catch (Exception $e) {
                    // silence fail;
                }

                return $list;
            },
            []
        );
    }

    public function save(User $user): User
    {
        if (is_null($user->id())) {
            $user = new User(count($this->getJSONFileList()) + 1, $user->name);
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
