<?php declare(strict_types=1);

namespace App\Controllers;

use App\ArticleValidator;
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

        $title = trim($_POST['title']);
        $content = trim($_POST['content']);

        $errors = ArticleValidator::validate($title, $content);

        if (!empty($errors)) {
            return new View ('createArticle', [
                'errors' => $errors,
                'title' => $title,
                'content' => $content
            ]);
        }

        $article = $this->modifyService->create($title, $content);
        if(!$article){
            return new View('notFound', []);
        }

        header('Location: /articles/' . $article);
        exit;
    }

    public function delete()
    {
        $articleId = (int)$_POST['delete'];
        $this->modifyService->delete($articleId);
        header('Location: /');
    }

    public function update(array $vars)
    {
        $id = (int)$vars['id'];
        $response = $this->showService->execute(new ShowArticleRequest($id));

        if (empty($_POST)) {
            return new View('updateArticle', [
                'article' => $response->getArticle()
            ]);
        }

        $title = $_POST['title'];
        $content = $_POST['content'];
        $this->modifyService->update($id, $title, $content);
        header('Location: /articles/' . $id);
        exit;
    }

}