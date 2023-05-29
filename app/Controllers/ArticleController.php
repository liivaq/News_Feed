<?php declare(strict_types=1);

namespace App\Controllers;

use App\Validator;
use App\Core\Session;
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

        if(!$articles){
            return new View('errors/notFound', []);
        }

        return new View('article/index', ['articles' => $articles]);
    }

    public function show(array $vars): View
    {
        try {
            $articleId = $vars['id'] ?? null;
            $response = $this->showArticleService->execute(new ShowArticleRequest((int)$articleId));
        } catch (RecourseNotFoundException $exception) {
            return new View('errors/notFound', []);
        }

        return new View('article/show',
            [
                'article' => $response->getArticle(),
                'comments' => $response->getComments()
            ]);
    }

    public function create(): View
    {
        if(!Session::get('user')){
            return new View('errors/notAuthorized', []);
        }
        return new View ('article/create', []);
    }

    public function store(): void
    {
        $title = $_POST['title'];
        $body = $_POST['body'];

        if(Validator::article($title, $body)){
            Session::flash('title', $title);
            Session::flash('body', $body);
            header('Location: /articles/create');
            exit;
        }

        $userId = Session::get('user')->getId();
        $article = $this->createArticleService->execute(new CreateArticleRequest($title, $body, $userId));

        header('Location: /articles/'.$article->getResponse()->getId());
    }

    public function delete(array $vars): void
    {
        $this->deleteArticleService->execute((int) $vars['id']);
        header('Location: /articles');
        exit;
    }

    public function edit(array $vars): View
    {
        $id = (int)$vars['id'];
        $response = $this->showArticleService->execute(new ShowArticleRequest($id));

        return new View('article/update', [
            'article' => $response->getArticle()
        ]);
    }

    public function update(array $vars)
    {
        $id = (int)$vars['id'];
        $title = $_POST['title'];
        $body = $_POST['body'];

        if(Validator::article($title, $body)){
            Session::flash('title', $title);
            Session::flash('body', $body);
            header('Location: /articles/edit/'.$id);
            exit;
        }

        $this->updateArticleService->execute(new UpdateArticleRequest($title, $body, $id));

        header('Location: /articles/'.$id);
        exit;
    }
}