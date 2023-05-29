<?php declare(strict_types=1);

namespace App\Services\Comment;

use App\Models\Comment;

class CreateCommentResponse
{
    private Comment $comment;

    public function __construct(Comment $comment)
    {
        $this->comment = $comment;
    }

    public function getComment(): Comment
    {
        return $this->comment;
    }

}