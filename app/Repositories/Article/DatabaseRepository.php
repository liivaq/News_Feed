<?php declare(strict_types=1);

namespace App\Repositories\Article;

use App\Core\Database;
use App\Models\Article;

class DatabaseRepository implements ArticleRepository
{
    private Database $database;

    public function __construct()
    {
        $this->database = new Database();
    }

    public function all(): array
    {
        $articles = $this->database->getBuilder()
            ->select('*')
            ->from('articles')
            ->fetchAllAssociative();

        $articleCollection = [];
        foreach ($articles as $article){
            $articleCollection[] = $this->buildModel($article);
        }
        return $articleCollection;
    }

    public function getById(int $id): ?Article
    {
        $article = $this->database->getBuilder()
            ->select('*')
            ->from('articles')
            ->where('id = ' . $id)
            ->fetchAssociative();

        return $this->buildModel($article);
    }

    public function getByUserId(int $userId): array
    {
        return [];
    }

    public function create(string $title, string $content, int $userId = 1): Article
    {
        $this->database->getDatabase()->insert('articles', [
            'title' => $title,
            'content' => $content,
            'user_id' => $userId
        ]);

        return $this->getById((int)$this->database->getDatabase()->lastInsertId());
    }

    private function buildModel(array $article): Article
    {
        return new Article(
            (int)$article['id'],
            (int)$article['user_id'],
            $article['title'],
            $article['content'],
            'https://placehold.co/600x400/gray/white?text=Doctrine+News'
        );
    }
}