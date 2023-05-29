<?php declare(strict_types=1);

namespace App\Repositories\Article;

use App\Models\Article;

interface ArticleRepository
{
    public function all(): array;

    public function getById(int $id): Article;

    public function getByUserId(int $userId): array;

    //TODO implement functions that are on database repository for interface and json repository
}
