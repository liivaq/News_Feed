<?php declare(strict_types=1);

namespace App\Repositories\User;

use App\Cache;
use App\Models\User;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use stdClass;

class JsonPlaceholderUserRepository implements UserRepository
{
    private Client $client;

    public function __construct()
    {
        $this->client = new Client(
            ['base_uri' => 'https://jsonplaceholder.typicode.com',]
        );
    }

    public function all(): array
    {
        try {
            $response = $this->checkCache('users', '/users');
            return $this->buildCollection($response);
        } catch (GuzzleException $e) {
            return [];
        }
    }

    public function getById(int $userId): ?User
    {
        try {
            $user = $this->checkCache('user_' . $userId, '/users/', $userId );
            return $this->buildModel(json_decode($user));
        } catch (GuzzleException $e) {
            return null;
        }
    }

    private function buildModel(stdClass $user): User
    {
        return new User(
            $user->id,
            $user->name,
            $user->username,
            $user->email,
            $user->phone,
            $user->website
        );
    }

    private function buildCollection(string $response): array
    {
        $userCollection = [];
        foreach (json_decode($response) as $article) {
            $userCollection[] = $this->buildModel($article);
        }
        return $userCollection;
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