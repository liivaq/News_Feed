<?php

namespace App\Services\Article;

use App\Repositories\Article\DatabaseRepository;

class ModifyArticleService
{
    private DatabaseRepository $repository;

    public function __construct(DatabaseRepository $repository)
    {
        $this->repository = $repository;
    }

    public function create(string $title, string $content): int
    {
        return $this->repository->create($title, $content);
    }

    public function delete(int $id): void
    {
        $this->repository->delete($id);
    }

    public function update(int $id, string $title, string $content): void
    {
        $this->repository->update($id, $title, $content);
    }

}