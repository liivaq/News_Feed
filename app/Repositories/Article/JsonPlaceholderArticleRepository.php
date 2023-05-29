<?php declare(strict_types=1);

namespace App\Repositories\Article;

use App\Cache;
use App\Exceptions\RecourseNotFoundException;
use App\Models\Article;
use Carbon\Carbon;
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
        $content = $this->checkCache('articles', '/posts');

        $articleCollection = [];

        if ($content) {
            foreach (json_decode($content) as $article) {
                $articleCollection[] = $this->buildModel($article);
            }
        }

        return $articleCollection;
    }

    public function getByUserId(int $userId): array
    {

        $content = $this->checkCache('articles_user_', '/posts?userId=', $userId);

        $articleCollection = [];

        if ($content) {
            foreach (json_decode($content) as $article) {
                $articleCollection[] = $this->buildModel($article);
            }
        }

        return $articleCollection;
    }

    public function getById(int $id): Article
    {
        $article = $this->checkCache('article_' . $id, '/posts/', $id);

        if ($article) {
            throw new RecourseNotFoundException('Article by ' . $id . ' not found');
        }

        return $this->buildModel(json_decode($article));
    }

    private function buildModel(stdClass $article): Article
    {
        return new Article(
            $article->userId,
            $article->title,
            $article->body,
            'https://placehold.co/600x400/gray/white?text=Some+News',
            $article->id,
        );
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