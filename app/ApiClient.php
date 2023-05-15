<?php declare(strict_types=1);

namespace App;

use App\Models\Article;
use App\Models\Comment;
use App\Models\User;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use stdClass;

class ApiClient
{
    private Client $client;

    public function __construct()
    {
        $this->client = new Client();
    }

    public function getArticles(): array
    {
        try {
            if (!Cache::has('articles')) {
                $response = $this->client->get('https://jsonplaceholder.typicode.com/posts');
                $responseContent = $response->getBody()->getContents();
                Cache::save('articles', $responseContent);
            } else {
                $responseContent = Cache::get('articles');
            }

            $articleCollection = [];
            foreach (json_decode($responseContent) as $article) {
                $articleCollection[] = $this->createArticle($article);

            }
            return $articleCollection;
        } catch (GuzzleException $e) {
            return [];
        }
    }

    public function getUsers(): array
    {
        try {
            if (!Cache::has('users')) {
                $response = $this->client->get('https://jsonplaceholder.typicode.com/users');
                $responseContent = $response->getBody()->getContents();
                Cache::save('users', $responseContent);
            } else {
                $responseContent = Cache::get('users');
            }

            $userCollection = [];
            foreach (json_decode($responseContent) as $user) {
                $userCollection[] = $this->createUser($user);

            }
            return $userCollection;
        } catch (GuzzleException $e) {
            return [];
        }
    }

    public function getArticlesByUser(int $id): array
    {
        try {
            $cacheKey = 'articles_user_' . $id;
            if (!Cache::has($cacheKey)) {
                $response = $this->client->get('https://jsonplaceholder.typicode.com/posts?userId=' . $id);
                $responseContent = $response->getBody()->getContents();
                Cache::save($cacheKey, $responseContent);
            } else {
                $responseContent = Cache::get($cacheKey);
            }
            $articleCollection = [];
            foreach (json_decode($responseContent) as $article) {

                $articleCollection[] = $this->createArticle($article);
            }
            return $articleCollection;
        } catch (GuzzleException $e) {
            return [];
        }
    }

    public function getCommentsById(int $id): array
    {
        try {
            $cacheKey = 'comments_' . $id;
            if (!Cache::has($cacheKey)) {
                $response = $this->client->get('https://jsonplaceholder.typicode.com/comments?postId=' . $id);
                $responseContent = $response->getBody()->getContents();
                Cache::save($cacheKey, $responseContent);
            } else {
                $responseContent = Cache::get($cacheKey);
            }
            $commentCollection = [];
            foreach (json_decode($responseContent) as $comment) {
                $commentCollection[] = $this->createComment($comment);
            }
            return $commentCollection;
        } catch (GuzzleException $e) {
            return [];
        }
    }

    public function getUser(int $id): ?User
    {
        try {
            $cacheKey = 'user_' . $id;
            if (!Cache::has($cacheKey)) {
                $response = $this->client->get('https://jsonplaceholder.typicode.com/users/' . $id);
                $responseContent = $response->getBody()->getContents();
                Cache::save($cacheKey, $responseContent);
            } else {
                $responseContent = Cache::get($cacheKey);
            }
            return $this->createUser(json_decode($responseContent));
        } catch (GuzzleException $e) {
            return null;
        }
    }

    public function getSingleArticle(int $id): ?Article
    {
        try {
            $cacheKey = 'article_' . $id;
            if (!Cache::has($cacheKey)) {
                $response = $this->client->get('https://jsonplaceholder.typicode.com/posts/' . $id);
                $responseContent = $response->getBody()->getContents();
                Cache::save($cacheKey, $responseContent);
            } else {
                $responseContent = Cache::get($cacheKey);
            }
            return $this->createArticle(json_decode($responseContent));
        } catch (GuzzleException $e) {
            return null;
        }

    }

    private function createArticle(stdClass $article): Article
    {
        return new Article(
            $this->getUser($article->userId),
            $article->id,
            $article->title,
            $article->body,
            'https://placehold.co/600x400/gray/white?text=Some+News'
        );
    }

    private function createUser(stdClass $user): User
    {
        return new User(
            $user->id,
            $user->name,
            $user->username,
            $user->email
        );
    }

    private function createComment(stdClass $comment): Comment
    {
        return new Comment(
            $comment->postId,
            $comment->id,
            $comment->name,
            $comment->email,
            $comment->body
        );
    }
}