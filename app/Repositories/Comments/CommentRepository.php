<?php declare(strict_types=1);

namespace App\Repositories\Comments;

interface CommentRepository
{
    public function getByArticleId(int $articleId): array;

}