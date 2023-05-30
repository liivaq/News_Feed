<?php declare(strict_types=1);

namespace App\Services\Article\Show;

use App\Exceptions\RecourseNotFoundException;
use App\Models\Comment;
use App\Repositories\Article\ArticleRepository;
use App\Repositories\Comments\CommentRepository;
use App\Repositories\User\UserRepository;

class ShowArticleService
{
    private ArticleRepository $articleRepository;
    private UserRepository $userRepository;
    private CommentRepository $commentRepository;

    public function __construct(
        ArticleRepository $articleRepository,
        UserRepository $userRepository,
        CommentRepository $commentRepository
    )
    {
        $this->articleRepository = $articleRepository;
        $this->userRepository = $userRepository;
        $this->commentRepository = $commentRepository;
    }

    public function execute(ShowArticleRequest $request): ShowArticleResponse
    {
        $article = $this->articleRepository->getById($request->getArticleId());

        $author = $this->userRepository->getById($article->getAuthorId());
        $article->setAuthor($author);

        $comments = $this->commentRepository->getByArticleId($article->getId());

        /** @var Comment $comment */
        foreach ($comments as $comment){
            $comment->setUser($this->userRepository->getById($comment->getUserId()));
        }

        return new ShowArticleResponse($article, $comments);
    }

}