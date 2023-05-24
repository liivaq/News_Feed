<?php declare(strict_types=1);

namespace App\Services\Article;

use App\Models\Article;
use App\Repositories\Article\DatabaseRepository;

class CreateArticleService
{
    private DatabaseRepository $databaseRepository;

    public function __construct(DatabaseRepository $repository){
        $this->databaseRepository = $repository;
    }

    public function execute(string $title, string $content): Article
    {
        return $this->databaseRepository->create($title, $content);
    }

}