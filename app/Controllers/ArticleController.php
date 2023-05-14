<?php declare(strict_types=1);

namespace App\Controllers;

use App\ApiClient;
use App\Core\View;
use GuzzleHttp\Client;

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

    public function singleArticle(array $vars): View
    {
        $article = $this->client->getSingleArticle((int)implode('', $vars));
        $comments = $this->client->getCommentsById($article->getId());

        return new View('singleArticle.twig', ['article' => $article, 'comments' => $comments]);
    }
}