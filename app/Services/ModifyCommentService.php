<?php declare(strict_types=1);

namespace App\Services;

use App\Repositories\Comments\CommentRepository;

class ModifyCommentService
{
    private CommentRepository $repository;

    public function __construct(CommentRepository $repository)
    {
        $this->repository = $repository;
    }

    public function create($id, $name, $content, $email){
        $this->repository->create($id, $name, $content, $email);
    }


}