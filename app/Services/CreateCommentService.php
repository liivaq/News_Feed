<?php declare(strict_types=1);

namespace App\Services;

use App\Models\Comment;
use App\Repositories\Comments\CommentRepository;

class CreateCommentService
{
    private CommentRepository $repository;

    public function __construct(CommentRepository $repository)
    {
        $this->repository = $repository;
    }

    public function execute(Comment $comment){
        $this->repository->create($comment);
    }


}