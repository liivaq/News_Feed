<?php declare(strict_types=1);

namespace App\Repositories\User;

use App\Models\User;

interface UserRepository
{
    public function all(): array;

    public function getById(int $userId): ?User;

    public function store(User $user): User;

    public function update(User $user): void;

    public function authenticate(User $user): bool;

    public function login(string $email, string $password): ?User;


}