<?php declare(strict_types=1);

namespace App\Services\Article\Update;

use App\Repositories\Article\ArticleRepository;

class UpdateArticleService
{
    private ArticleRepository $repository;

    public function __construct(ArticleRepository $repository)
    {
        $this->repository = $repository;
    }

    public function execute(UpdateArticleRequest $request)
    {
        $article = $this->repository->getById($request->getId());

        $article->update([
            'title' => $request->getTitle(),
            'body' => $request->getBody()
        ]);

        $this->repository->update($article);

        return new UpdateArticleResponse($article);
    }
}