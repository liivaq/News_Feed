<?php declare(strict_types=1);

namespace App\Controllers;

use App\ApiClient;
use App\Core\View;

class ArticleController
{
    private ApiClient $client;

    public function __construct()
    {
        $this->client = new ApiClient();
    }

    public function articles(): View
    {
        $articles = $this->client->getArticles();

        return new View('articles', ['articles' => $articles]);
    }

    public function users(): View
    {
        $users = $this->client->getUsers();
        return new View('users', ['users' => $users]);
    }

    public function singleArticle(array $vars): View
    {
        $article = $this->client->getSingleArticle((int)implode('', $vars));
        if (!$article) {
            return new View('notFound', []);
        }
        $comments = $this->client->getCommentsById($article->getId());
        return new View('singleArticle', ['article' => $article, 'comments' => $comments]);
    }

    public function singleUser(array $vars): View
    {
        $user = $this->client->getSingleUser((int)implode('', $vars));
        if (!$user) {
            return new View('notFound', []);
        }
        $articles = $this->client->getArticlesByUser($user->getId());
        return new View('singleUser', ['user' => $user, 'articles' => $articles]);
    }
}