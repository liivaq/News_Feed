<?php declare(strict_types=1);

namespace App\Services\User\Create;

class CreateUserRequest
{
    private string $email;
    private string $username;
    private string $password;

    public function __construct(
        string $email,
        string $password,
        string $username)
    {
        $this->email = $email;
        $this->password = $password;
        $this->username = $username;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

}