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
            if (!Cache::has('users')) {
                $response = $this->client->get('/users');
                $responseContent = $response->getBody()->getContents();
                Cache::save('users', $responseContent);
            } else {
                $responseContent = Cache::get('users');
            }

            $userCollection = [];
            foreach (json_decode($responseContent) as $user) {
                $userCollection[] = $this->buildModel($user);

            }
            return $userCollection;
        } catch (GuzzleException $e) {
            return [];
        }
    }

    public function getById(int $userId): ?User
    {
        try {
            $cacheKey = 'user_' . $userId;
            if (!Cache::has($cacheKey)) {
                $response = $this->client->get('/users/' . $userId);
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

}