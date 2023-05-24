<?php declare(strict_types=1);

namespace App\Controllers;

use App\Core\Database;
use App\Core\View;
use App\Exceptions\RecourseNotFoundException;
use App\Services\Article\CreateArticleService;
use App\Services\Article\IndexArticleService;
use App\Services\Article\Show\ShowArticleRequest;
use App\Services\Article\Show\ShowArticleService;

class ArticleController
{
    private IndexArticleService $indexService;
    private ShowArticleService $showService;
    private CreateArticleService $createService;

    public function __construct(
        IndexArticleService  $indexService,
        ShowArticleService   $showService,
        CreateArticleService $createService
    )
    {
        $this->indexService = $indexService;
        $this->showService = $showService;
        $this->createService = $createService;
    }

    public function index(): View
    {
        $service = $this->indexService;
        $articles = $service->execute();

        return new View('articles', ['articles' => $articles]);
    }

    public function show(array $vars): View
    {
        try {
            $articleId = $vars['id'] ?? null;
            $service = $this->showService;
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

    public function create(): View
    {
        if (empty($_POST)) {
            return new View ('createArticle', []);
        }
        $title = $_POST['title'];
        $content = $_POST['content'];
        $article = $this->createService->execute($title, $content);

        return new View ('singleArticle', [
            'article' => $article
        ]);

    }
}