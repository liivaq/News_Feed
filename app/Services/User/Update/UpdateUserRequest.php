<?php declare(strict_types=1);

namespace App\Services\User\Update;

class UpdateUserRequest
{
    private int $id;
    private string $name;
    private string $username;

    public function __construct(
        int $id,
        string $name,
        string $username)
    {
        $this->id = $id;
        $this->name = $name;
        $this->username = $username;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

}