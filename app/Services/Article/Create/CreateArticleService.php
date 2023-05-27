<?php declare(strict_types=1);

namespace App\Services\Article\Create;

use App\Models\Article;
use App\Repositories\Article\ArticleRepository;

class CreateArticleService
{
    private ArticleRepository $repository;

    public function __construct(ArticleRepository $repository)
    {
        $this->repository = $repository;
    }

    public function execute(CreateArticleRequest $request): CreateArticleResponse
    {
        $article = new Article(
            rand(1, 10),
            $request->getTitle(),
            $request->getBody(),
            'https://placehold.co/600x400/gray/white?text=Some+News',
        );
        $this->repository->store($article);

        return new CreateArticleResponse($article);
    }
}