<?php declare(strict_types=1);

namespace App\Models;

class User
{
    private string $email;
    private string $username;
    private string $password;
    private ?string $avatarUrl;
    private ?int $id;

    public function __construct(
        string $email,
        string $password,
        string $username,
        ?string $avatarUrl = null,
        ?int    $id = null

    )
    {
        $this->email = $email;
        $this->password = $password;
        $this->username = $username;
        $this->id = $id;
        $this->avatarUrl = $avatarUrl ?? 'https://i.pravatar.cc/150?img='.(rand(1,70));
    }

    public function getAvatarUrl(): string
    {
        return $this->avatarUrl;
    }

    public function getId(): ?int
    {
        return $this->id;
    }


    public function getUsername(): string
    {
        return $this->username;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

   public function update(array $attributes)
   {
       foreach ($attributes as $attribute => $value)
       {
           $this->{$attribute} = $value;
       }
   }

}