<?php declare(strict_types=1);

namespace App\Controllers;

use App\Services\ModifyCommentService;

class CommentController
{
    private ModifyCommentService $modifyCommentService;

    public function __construct(ModifyCommentService $modifyCommentService){
        $this->modifyCommentService = $modifyCommentService;
    }
    public function create(array $vars){
        $id = (int) $vars['id'];
        $name = $_POST['name'];
        $content = $_POST['content'];
        $email = $_POST['email'];

        $this->modifyCommentService->create($id, $name, $content, $email);

        header('Location: /articles/'.$id);
    }

}