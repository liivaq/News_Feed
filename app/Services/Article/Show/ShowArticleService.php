<?php declare(strict_types=1);

namespace App\Services\Article\Show;

use App\Exceptions\RecourseNotFoundException;
use App\Models\User;
use App\Repositories\ArticleRepository;
use App\Repositories\CommentRepository;
use App\Repositories\UserRepository;

class ShowArticleService
{
    private ArticleRepository $articleRepository;
    private UserRepository $userRepository;
    private CommentRepository $commentRepository;

    public function __construct()
    {
        $this->articleRepository = new ArticleRepository();
        $this->userRepository = new UserRepository();
        $this->commentRepository = new CommentRepository();
    }
    public function execute(ShowArticleRequest $request): ShowArticleResponse
    {
        $article = $this->articleRepository->getById($request->getArticleId());

        if($article == null){
            throw new RecourseNotFoundException('Article by id '.$request->getArticleId().' not found');
        }

        $author = $this->userRepository->getById($article->getAuthorId());
        $article->setAuthor($author);

        $comments = $this->commentRepository->getByArticleId($article->getId());

        return new ShowArticleResponse($article, $comments);
    }

}