<?php

namespace App\Services\Article;

use App\Repositories\Article\DatabaseRepository;

class DeleteArticleService
{
    private DatabaseRepository $repository;

    public function __construct (DatabaseRepository $repository){
        $this->repository = $repository;
    }

    public function execute(int $id){
        $this->repository->delete($id);
    }
}