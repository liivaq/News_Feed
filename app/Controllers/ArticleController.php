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

        return new View('articles.twig', ['articles' => $articles]);
    }

    public function users(): View
    {
        $users = $this->client->getUsers();
        return new View('users.twig', ['users' => $users]);
    }

    public function singleArticle(array $vars): View
    {
        $article = $this->client->getSingleArticle((int)implode('', $vars));
        if (!$article) {
            return new View('notFound.twig', []);
        }
        $comments = $this->client->getCommentsById($article->getId());
        return new View('singleArticle.twig', ['article' => $article, 'comments' => $comments]);
    }

    public function user(array $vars): View
    {
        $user = $this->client->getUser((int)implode('', $vars));
        if (!$user) {
            return new View('notFound.twig', []);
        }
        $articles = $this->client->getArticlesByUser($user->getId());
        return new View('singleUser.twig', ['user' => $user, 'articles' => $articles]);
    }
}