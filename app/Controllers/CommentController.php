<?php declare(strict_types=1);

namespace App\Controllers;

use App\Models\Comment;
use App\Services\CreateCommentService;

class CommentController
{
    private CreateCommentService $createCommentService;

    public function __construct(CreateCommentService $modifyCommentService)
    {
        $this->createCommentService = $modifyCommentService;
    }

    public function create(array $vars)
    {
        $comment = new Comment(
            (int)$vars['id'],
            $_POST['name'],
            $_POST['email'],
            $_POST['body']
        );

        $this->createCommentService->execute($comment);

        header('Location: /articles/' . $comment->getArticleId());
    }

}