<?php declare(strict_types=1);

namespace App\Services\Comment;

use App\Models\Comment;
use App\Repositories\Comments\CommentRepository;

class CreateCommentService
{
    private CommentRepository $commentRepository;

    public function __construct(CommentRepository $commentRepository)
    {
        $this->commentRepository = $commentRepository;
    }

    public function execute(CreateCommentRequest $request): Comment
    {
        $comment = new Comment(
            $request->getArticleId(),
            $request->getTitle(),
            $request->getBody(),
            $request->getUserId()
        );

        return $this->commentRepository->create($comment);
    }

}