<?php declare(strict_types=1);

namespace App\Controllers;

use App\Core\View;
use App\Exceptions\RecourseNotFoundException;
use App\Services\Article\Modify\ModifyRequest;
use App\Services\Article\Modify\ModifyResponse;
use App\Services\Article\IndexArticleService;
use App\Services\Article\Modify\ModifyArticleService;
use App\Services\Article\Show\ShowArticleRequest;
use App\Services\Article\Show\ShowArticleService;

class ArticleController
{
    private IndexArticleService $indexService;
    private ShowArticleService $showService;
    private ModifyArticleService $modifyArticleService;

    public function __construct(
        IndexArticleService  $indexService,
        ShowArticleService   $showService,
        ModifyArticleService $modifyArticleService

    )
    {
        $this->indexService = $indexService;
        $this->showService = $showService;
        $this->modifyArticleService = $modifyArticleService;
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

    public function createView(): View
    {
        return new View ('createArticle', []);
    }

    public function create(): ModifyResponse
    {
        $title = trim($_POST['title']);
        $content = trim($_POST['content']);

        return $this->modifyArticleService->create(new ModifyRequest($title, $content));
    }

    public function delete(): ModifyResponse
    {
        $articleId = (int)$_POST['delete'];
        return $this->modifyArticleService->delete($articleId);

    }

    public function updateView(array $vars): View
    {
        $id = (int)$vars['id'];
        $response = $this->showService->execute(new ShowArticleRequest($id));

        return new View('updateArticle', [
            'article' => $response->getArticle()
        ]);
    }

    public function update(array $vars): ModifyResponse
    {
        $id = (int)$vars['id'];
        $title = trim($_POST['title']);
        $content = trim($_POST['content']);

        return $this->modifyArticleService->update(new ModifyRequest($title, $content, $id));
    }
}