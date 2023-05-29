<?php declare(strict_types=1);

namespace App\Controllers;

use App\Core\Session;
use App\Models\Comment;
use App\Services\Comment\CreateCommentRequest;
use App\Services\Comment\CreateCommentService;
use App\Validator;

class CommentController
{
    private CreateCommentService $createCommentService;

    public function __construct(CreateCommentService $createCommentService)
    {
        $this->createCommentService = $createCommentService;
    }

    public function create(array $vars)
    {
        $title = $_POST['title'];
        $body = $_POST['body'];
        $articleId = (int)$vars['id'];

        if(Validator::comment($title, $body)){
            Session::flash('title', $title);
            Session::flash('body', $body);
            header('Location: /articles/'.$articleId);
            exit();
        }

        $user = Session::get('user');
        $comment = $this->createCommentService->execute(
            new CreateCommentRequest(
                $articleId,
                $title,
                $body,
                $user->getId(),
            ));

        header('Location: /articles/' . $comment->getArticleId());
    }

}