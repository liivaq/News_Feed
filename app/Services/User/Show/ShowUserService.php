<?php declare(strict_types=1);

namespace App\Services\User\Show;

use App\Core\Container;
use App\Exceptions\RecourseNotFoundException;
use App\Models\Article;
use App\Repositories\Article\ArticleRepository;
use App\Repositories\User\UserRepository;

class ShowUserService
{
    private UserRepository $userRepository;
    private ArticleRepository $articleRepository;

    public function __construct(UserRepository $userRepository, ArticleRepository $articleRepository)
    {
        $this->userRepository = $userRepository;
        $this->articleRepository = $articleRepository;

    }
    public function execute(ShowUserRequest $request): ShowUserResponse
    {
        $user = $this->userRepository->getById($request->getUserId());

        if($user == null){
            throw new RecourseNotFoundException('User by id '.$request->getUserId().' not found');
        }

        $articles = $this->articleRepository->getByUserId($user->getId());
        /** @var Article $article */
        foreach ($articles as $article){
            $author = $this->userRepository->getById($article->getAuthorId());
            $article->setAuthor($author);
        }

        return new ShowUserResponse($user, $articles);
    }
}