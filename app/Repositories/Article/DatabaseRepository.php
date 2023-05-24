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
        $articles = $this->database->getDatabase()
            ->select("articles", ['id', 'title', 'content', 'user_id']);

        $articleCollection = [];
        foreach ($articles as $article) {
            $articleCollection[] = $this->buildModel((object)$article);
        }

        return $articleCollection;
    }

    public function getById(int $id): ?Article
    {
        $article = $this->database->getDatabase()
            ->get('articles', ['id', 'title', 'content', 'user_id'], ['id' => $id]);

        return $this->buildModel((object)$article);
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

        return $this->getById((int)$this->database->getDatabase()->id());
    }

    public function update(int $articleId, string $title, string $content): void
    {
        $this->database->getDatabase()->update('articles',
            [
                'title' => $title,
                'content' => $content
            ],
            [
                'id' => $articleId
            ]);

    }

    public function delete(int $articleId){
        $this->database->getDatabase()->delete('articles', ['id' => $articleId]);
    }

    private function buildModel(\stdClass $article): Article
    {
        return new Article(
            (int)$article->id,
            (int)$article->user_id,
            $article->title,
            $article->content,
            'https://placehold.co/600x400/gray/white?text=Some+News'
        );
    }

}