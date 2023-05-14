<?php declare(strict_types=1);

namespace App;

use App\Models\Article;
use App\Models\Comment;
use App\Models\User;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

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
            $response = $this->client->get('https://jsonplaceholder.typicode.com/posts');
            $content = json_decode($response->getBody()->getContents());
            $articleCollection = [];
            if (empty($vars)) {
                foreach ($content as $article) {
                    $articleCollection[] = $this->createArticle($article);
                }
            } else {
                $articleCollection [] = $this->createArticle($content);
            }
            return $articleCollection;
        } catch (GuzzleException $e) {
            return [];
        }
    }

    public function getCommentsById(int $id): array
    {
        $response = $this->client->get('https://jsonplaceholder.typicode.com/comments?postId=' . $id);
        $content = json_decode($response->getBody()->getContents());
        $commentCollection = [];
        foreach ($content as $comment) {
            $commentCollection[] = $this->createComment($comment);
        }
        return $commentCollection;
    }

    public function getUser(int $id)
    {
        try {
            $response = $this->client->get('https://jsonplaceholder.typicode.com/users/' . $id);
            return $this->createUser(json_decode($response->getBody()->getContents()));
        } catch (GuzzleException $e) {
            return [];
        }

    }

    public function getSingleArticle(int $id)
    {
        try {
            $response = $this->client->get('https://jsonplaceholder.typicode.com/posts/' . $id);
            return $this->createArticle(json_decode($response->getBody()->getContents()));
        } catch (GuzzleException $e) {
            return [];
        }

    }

    private function createArticle(\stdClass $article): Article
    {
        return new Article(
            $this->getUser($article->userId),
            $article->id,
            $article->title,
            $article->body);
    }

    private function createUser(\stdClass $user): User
    {
        return new User(
            $user->id,
            $user->name,
            $user->username,
            $user->email
        );
    }

    private function createComment(\stdClass $comment): Comment
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