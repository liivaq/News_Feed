<?php declare(strict_types=1);

namespace App\Controllers;

use App\Core\View;
use App\Exceptions\RecourseNotFoundException;
use App\Services\Article\IndexArticleService;
use App\Services\Article\ModifyArticleService;
use App\Services\Article\Show\ShowArticleRequest;
use App\Services\Article\Show\ShowArticleService;

class ArticleController
{
    private IndexArticleService $indexService;
    private ShowArticleService $showService;
    private ModifyArticleService $modifyService;

    public function __construct(
        IndexArticleService  $indexService,
        ShowArticleService   $showService,
        ModifyArticleService $modifyService
    )
    {
        $this->indexService = $indexService;
        $this->showService = $showService;
        $this->modifyService = $modifyService;
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
        $article = $this->modifyService->create($title, $content);

        return new View ('singleArticle', [
            'article' => $article
        ]);
    }

    public function delete()
    {
        $articleId = (int) $_POST['delete'];
        $this->modifyService->delete($articleId);
        header('Location: /');
    }

    public function edit(){
        $id = (int) ($_POST['edit']);
        $article = $this->showService->execute(new ShowArticleRequest($id))->getArticle();
        return new View('updateArticle', [
            'article' => $article
        ]);
    }

    public function update()
    {
        $articleId = (int)$_POST['__update'];
        $title = $_POST['title'];
        $content = $_POST['content'];

        $this->modifyService->update($articleId, $title, $content);
        header ('Location: /articles/'.$articleId);
    }
}