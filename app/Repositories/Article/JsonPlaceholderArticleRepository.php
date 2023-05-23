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
            $content = $this->checkCache('articles', '/posts');
            return $this->buildCollection($content);
        } catch (GuzzleException $e) {
            return [];
        }
    }

    public function getByUserId(int $userId): array
    {
        try {
            $content = $this->checkCache('articles_user_', '/posts?userId=', $userId);
            return $this->buildCollection($content);
        } catch (GuzzleException $e) {
            return [];
        }
    }

    public function getById(int $id): ?Article
    {
        try {
            $article = $this->checkCache('article_' . $id, '/posts/', $id);
            return $this->buildModel(json_decode($article));
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

    private function buildCollection(string $response): array
    {
        $articleCollection = [];
        foreach (json_decode($response) as $article) {
            $articleCollection[] = $this->buildModel($article);
        }
        return $articleCollection;
    }

    private function checkCache(string $cacheKey, string $url, ?int $id = null): string
    {
        if (!Cache::has($cacheKey)) {
            $response = $this->client->get($url . $id);
            $responseContent = $response->getBody()->getContents();
            Cache::save($cacheKey, $responseContent);
        } else {
            $responseContent = Cache::get($cacheKey);
        }
        return $responseContent;
    }
}