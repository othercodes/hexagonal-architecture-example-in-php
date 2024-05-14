<?php

declare(strict_types=1);

namespace OtherCode\UserManagement\Domain;

final class User
{
    public function __construct(
        private ?int $id,
        public string $name
    ) {
    }

    public function id(): ?int
    {
        return $this->id;
    }

    public static function create(string $name): self
    {
        return new self(null, $name);
    }
}
