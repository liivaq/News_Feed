<?php declare(strict_types=1);

namespace App\Controllers;

use App\Core\View;
use App\Exceptions\RecourseNotFoundException;
use App\Services\Article\CreateArticleService;
use App\Services\Article\DeleteArticleService;
use App\Services\Article\IndexArticleService;
use App\Services\Article\Show\ShowArticleRequest;
use App\Services\Article\Show\ShowArticleService;
use App\Services\Article\UpdateArticleService;

class ArticleController
{
    private IndexArticleService $indexService;
    private ShowArticleService $showService;
    private CreateArticleService $createService;
    private UpdateArticleService $updateService;

    private DeleteArticleService $deleteService;

    public function __construct(
        IndexArticleService  $indexService,
        ShowArticleService   $showService,
        CreateArticleService $createService,
        UpdateArticleService $updateService,
        DeleteArticleService $deleteService
    )
    {
        $this->indexService = $indexService;
        $this->showService = $showService;
        $this->createService = $createService;
        $this->updateService = $updateService;
        $this->deleteService = $deleteService;
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

    public function delete()
    {
        $articleId = (int) $_POST['delete'];
        $this->deleteService->execute($articleId);
        header('Location: /');
    }

    public function update()
    {
        $articleId = isset($_POST['edit']) ? (int)$_POST['edit'] : (int)$_POST['__update'] ;

        if(!isset($_POST['__update'])) {
            $article = $this->showService->execute(new ShowArticleRequest($articleId));
            return new View('updateArticle', [
                'article' => $article->getArticle()
            ]);
        }

        $title = $_POST['title'];
        $content = $_POST['content'];
        $article = $this->updateService->execute($articleId, $title, $content);
        return new View('singleArticle', [
            'article' => $article
        ]);
    }

}