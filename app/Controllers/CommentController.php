<?php declare(strict_types=1);

namespace App\Controllers;

use App\Core\Session;
use App\Models\Comment;
use App\Services\Comment\CreateCommentRequest;
use App\Services\Comment\CreateCommentService;

class CommentController
{
    private CreateCommentService $createCommentService;

    public function __construct(CreateCommentService $modifyCommentService)
    {
        $this->createCommentService = $modifyCommentService;
    }

    public function create(array $vars)
    {
        $user = Session::get('user');
        $comment = $this->createCommentService->execute(
            new CreateCommentRequest(
                (int)$vars['id'],
                $_POST['title'],
                $_POST['body'],
                $user->getId(),
            ));

        header('Location: /articles/' . $comment->getArticleId());
    }

}