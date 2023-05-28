<?php declare(strict_types=1);

namespace App\Services\User\Create;

use App\Models\User;

class CreateUserResponse
{
    private User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function getUser(): User
    {
        return $this->user;
    }
}