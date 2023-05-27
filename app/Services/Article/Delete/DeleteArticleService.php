<?php

namespace App\Services\Article\Delete;

use App\Repositories\Article\ArticleRepository;


class DeleteArticleService
{
    private ArticleRepository $repository;

    public function __construct(ArticleRepository $repository)
    {
        $this->repository = $repository;
    }

    public function execute(int $id): void
    {
        $this->repository->delete($id);
    }

}