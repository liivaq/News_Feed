<?php declare(strict_types=1);

namespace App\Controllers;

use App\Core\View;
use App\Exceptions\RecourseNotFoundException;
use App\Services\Article\Create\CreateArticleRequest;
use App\Services\Article\Create\CreateArticleService;
use App\Services\Article\Delete\DeleteArticleService;
use App\Services\Article\IndexArticleService;
use App\Services\Article\Show\ShowArticleRequest;
use App\Services\Article\Show\ShowArticleService;
use App\Services\Article\Update\UpdateArticleRequest;
use App\Services\Article\Update\UpdateArticleService;

class ArticleController
{
    private IndexArticleService $indexArticleService;
    private ShowArticleService $showArticleService;
    private CreateArticleService $createArticleService;
    private UpdateArticleService $updateArticleService;
    private  DeleteArticleService $deleteArticleService;

    public function __construct(
        IndexArticleService  $indexArticleService,
        ShowArticleService   $showArticleService,
        CreateArticleService $createArticleService,
        UpdateArticleService $updateArticleService,
        DeleteArticleService $deleteArticleService

    )
    {
        $this->indexArticleService = $indexArticleService;
        $this->showArticleService = $showArticleService;
        $this->createArticleService = $createArticleService;
        $this->updateArticleService = $updateArticleService;
        $this->deleteArticleService = $deleteArticleService;
    }

    public function index(): View
    {
        $articles = $this->indexArticleService->execute();

        return new View('articles', ['articles' => $articles]);
    }

    public function show(array $vars): View
    {
        try {
            $articleId = $vars['id'] ?? null;
            $response = $this->showArticleService->execute(new ShowArticleRequest((int)$articleId));
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

    public function create(): void
    {
        $title = trim($_POST['title']);
        $content = trim($_POST['content']);

        $article = $this->createArticleService->execute(new CreateArticleRequest($title, $content));

        header('Location: /articles/'.$article->getResponse()->getId());
    }

    public function delete(array $vars): void
    {
        $this->deleteArticleService->execute((int) $vars['id']);
        header('Location: /articles');
    }

    public function updateView(array $vars): View
    {
        $id = (int)$vars['id'];
        $response = $this->showArticleService->execute(new ShowArticleRequest($id));

        return new View('updateArticle', [
            'article' => $response->getArticle()
        ]);
    }

    public function update(array $vars)
    {
        $id = (int)$vars['id'];
        $title = trim($_POST['title']);
        $body = trim($_POST['body']);

        $this->updateArticleService->execute(new UpdateArticleRequest($title, $body, $id));

        header('Location: /articles/'.$id);
    }
}