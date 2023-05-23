<?php declare(strict_types=1);

namespace App\Repositories\Article;

use App\Cache;
use App\Models\Article;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use stdClass;

class JsonPlaceholderArticleRepository implements ArticleRepository
{
    private Client $client;

    public function __construct()
    {
        $this->client = new Client(
            ['base_uri' => 'https://jsonplaceholder.typicode.com']
        );
    }

    public function all(): array
    {
        try {
            if (!Cache::has('articles')) {
                $response = $this->client->get('/posts');
                $responseContent = $response->getBody()->getContents();
                Cache::save('articles', $responseContent);
            } else {
                $responseContent = Cache::get('articles');
            }

            $articleCollection = [];
            foreach (json_decode($responseContent) as $article) {
                $articleCollection[] = $this->buildModel($article);

            }
            return $articleCollection;
        } catch (GuzzleException $e) {
            return [];
        }
    }

    public function getByUserId(int $userId): array
    {
        try {
            $cacheKey = 'articles_user_' . $userId;
            if (!Cache::has($cacheKey)) {
                $response = $this->client->get('/posts?userId=' . $userId);
                $responseContent = $response->getBody()->getContents();
                Cache::save($cacheKey, $responseContent);
            } else {
                $responseContent = Cache::get($cacheKey);
            }
            $articleCollection = [];
            foreach (json_decode($responseContent) as $article) {

                $articleCollection[] = $this->buildModel($article);
            }
            return $articleCollection;
        } catch (GuzzleException $e) {
            return [];
        }
    }

    public function getById(int $id): ?Article
    {
        try {
            $cacheKey = 'article_' . $id;
            if (!Cache::has($cacheKey)) {
                $response = $this->client->get('/posts/' . $id);
                $responseContent = $response->getBody()->getContents();
                Cache::save($cacheKey, $responseContent);
            } else {
                $responseContent = Cache::get($cacheKey);
            }
            return $this->buildModel(json_decode($responseContent));
        } catch (GuzzleException $e) {
            return null;
        }

    }

    private function buildModel(stdClass $article): Article
    {
        return new Article(
            $article->id,
            $article->userId,
            $article->title,
            $article->body,
            'https://placehold.co/600x400/gray/white?text=Some+News'
        );
    }

}