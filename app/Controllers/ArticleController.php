<?php declare(strict_types=1);

namespace App\Controllers;

use App\Core\View;
use App\Exceptions\RecourseNotFoundException;
use App\Services\Article\IndexArticleService;
use App\Services\Article\Show\ShowArticleRequest;
use App\Services\Article\Show\ShowArticleService;

class ArticleController
{
    public function index(): View
    {
        $service = new IndexArticleService();
        $articles = $service->execute();

        return new View('articles', ['articles' => $articles]);
    }

    public function show(array $vars): View
    {

        try {
            $articleId = $vars['id'] ?? null;
            $service = new ShowArticleService();
            $response = $service->execute(new ShowArticleRequest((int)$articleId));
        } catch (RecourseNotFoundException $exception) {
            return new View('notFound', []);
        }

        return new View('singleArticle',
            [
                'article' => $response->getArticle(),
                'comments' => $response->getComments()
            ]);
    }

}