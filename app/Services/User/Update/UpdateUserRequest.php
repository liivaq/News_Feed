<?php declare(strict_types=1);

namespace App\Services\User\Update;

class UpdateUserRequest
{
    private int $id;
    private string $email;
    private string $username;

    public function __construct(
        int $id,
        string $email,
        string $username)
    {
        $this->id = $id;
        $this->email = $email;
        $this->username = $username;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

}