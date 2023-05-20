<?php declare(strict_types=1);

namespace App\Repositories;

use App\Cache;
use App\Models\Comment;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use stdClass;

class CommentRepository
{
    private Client $client;

    public function __construct()
    {
        $this->client = new Client(
            ['base_uri' => 'https://jsonplaceholder.typicode.com',]
        );
    }

    public function getByArticleId(int $articleId): array
    {
        try {
            $cacheKey = 'comments_' . $articleId;
            if (!Cache::has($cacheKey)) {
                $response = $this->client->get('/comments?postId=' . $articleId);
                $responseContent = $response->getBody()->getContents();
                Cache::save($cacheKey, $responseContent);
            } else {
                $responseContent = Cache::get($cacheKey);
            }
            $commentCollection = [];
            foreach (json_decode($responseContent) as $comment) {
                $commentCollection[] = $this->buildModel($comment);
            }
            return $commentCollection;
        } catch (GuzzleException $e) {
            return [];
        }
    }

    private function buildModel(stdClass $comment): Comment
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