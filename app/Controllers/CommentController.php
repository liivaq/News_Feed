<?php declare(strict_types=1);

namespace App\Controllers;

use App\Core\Redirect;
use App\Core\Session;
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

    public function create(array $vars): Redirect
    {
        $title = $_POST['title'];
        $body = $_POST['body'];
        $articleId = (int)$vars['id'];

        if(Validator::comment($title, $body)){
            Session::flash('title', $title);
            Session::flash('body', $body);
            return new Redirect('/articles/'.$articleId);

        }

        $user = Session::get('user');
        $comment = $this->createCommentService->execute(
            new CreateCommentRequest(
                $articleId,
                $title,
                $body,
                $user->getId(),
            ));

        return new Redirect('/articles/' . $comment->getArticleId());
    }
}