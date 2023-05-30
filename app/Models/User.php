<?php declare(strict_types=1);

namespace App\Models;

class User
{

    private string $email;
    private ?string $password;
    private ?string $username;
    private ?string $name;
    private ?int $id;

    public function __construct(
        string $email,
        ?string $password = null,
        string $name = null,
        string $username = null,
        ?int    $id = null

    )
    {
        $this->email = $email;
        $this->password = $password;
        $this->name = $name;
        $this->username = $username;
        $this->id = $id;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getUsername(): ?string
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

    public function getPassword(): ?string
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