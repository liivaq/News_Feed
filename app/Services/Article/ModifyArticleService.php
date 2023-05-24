<?php

namespace App\Services\Article;

use App\Models\Article;
use App\Repositories\Article\DatabaseRepository;

class ModifyArticleService
{
    private DatabaseRepository $repository;

    public function __construct(DatabaseRepository $repository){
        $this->repository = $repository;
    }

    public function create(string $title, string $content): Article
    {
        return $this->repository->create($title, $content);
    }

    public function delete(int $id){
        $this->repository->delete($id);
    }

    public function update(int $id, string $title, string $content){
        $this->repository->update($id, $title, $content);
    }

}