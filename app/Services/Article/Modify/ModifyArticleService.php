<?php declare(strict_types=1);

namespace App\Services\Article\Modify;

use App\ArticleValidator;
use App\Core\View;
use App\Redirect;
use App\Repositories\Article\DatabaseRepository;

class ModifyArticleService
{
    private DatabaseRepository $repository;

    public function __construct(DatabaseRepository $repository)
    {
        $this->repository = $repository;
    }

    public function create(ModifyRequest $request): ModifyResponse
    {
        $title = $request->getTitle();
        $content = $request->getContent();

        $errors = ArticleValidator::validate($title, $content);

        if(empty($errors)){
            $article = $this->repository->create($title, $content);
            return new ModifyResponse([new Redirect('/articles/'.$article)]);
        }

        return new ModifyResponse([
            new View ('createArticle', [
                'errors' => $errors,
                'title' => $title,
                'content' => $content
            ])
        ]);
    }

    public function update(ModifyRequest $request): ModifyResponse
    {
        $title = $request->getTitle();
        $content = $request->getContent();
        $id = $request->getId();

        $errors = ArticleValidator::validate($title, $content);

        if (empty($errors)) {
            $this->repository->update($id, $title, $content);
            return new ModifyResponse([new Redirect('/articles/' . $id)]);
        }

        return new ModifyResponse([
            new View ('updateArticle', [
                'errors' => $errors,
                'title' => $title,
                'content' => $content,
                'id'=> $id
            ])
        ]);
    }

    public function delete(int $id): ModifyResponse
    {
        $this->repository->delete($id);
        return new ModifyResponse([new Redirect('/')]);
    }
}