<?php declare(strict_types=1);

namespace App\Services\Article;

use App\Repositories\Article\DatabaseRepository;


class UpdateArticleService
{
    private DatabaseRepository $repository;

    public function __construct (DatabaseRepository $repository){
        $this->repository = $repository;
    }

    public function execute(int $id, string $title, string $content){
        $this->repository->update($id, $title, $content);
        return $this->repository->getById($id);
    }
}