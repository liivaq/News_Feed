<?php declare(strict_types=1);

namespace App\Repositories\Article;

use App\Core\Database;
use App\Models\Article;
use Carbon\Carbon;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;

class DatabaseRepository implements ArticleRepository
{
    private QueryBuilder $builder;
    private Connection $connection;

    public function __construct()
    {
        $this->connection = Database::getConnection();
        $this->builder = $this->connection->createQueryBuilder();
    }

    public function all(): array
    {
        $articles = $this->builder
            ->select('*')
            ->from('articles')
            ->fetchAllAssociative();

        $articleCollection = [];
        foreach ($articles as $article) {
            $articleCollection[] = $this->buildModel((object)$article);
        }

        return $articleCollection;
    }

    public function getById(int $id): ?Article
    {
        $article = $this->builder
            ->select('*')
            ->from('articles')
            ->where('id = :id')
            ->setParameter('id', $id)
            ->fetchAssociative();

        return $this->buildModel((object)$article);
    }

    public function getByUserId(int $userId): array
    {
        $articles = $this->builder
            ->select('*')
            ->from('articles')
            ->where('user_id = :user_id')
            ->setParameter('user_id', $userId)
            ->fetchAllAssociative();

        $articleCollection = [];
        foreach ($articles as $article) {
            $articleCollection[] = $this->buildModel((object)$article);
        }
        return $articleCollection;
    }

    public function create(string $title, string $content): int
    {
        $this->builder
            ->insert('articles')
            ->values([
                'title' => ':title',
                'content' => ':content',
                'user_id' => rand(1, 10),
                'date' => ':date'
            ])
            ->setParameter('title', $title)
            ->setParameter('content', $content)
            ->setParameter('date', Carbon::now()->toDateTimeString())
            ->executeStatement();

        return (int)$this->connection->lastInsertId();
    }

    public function update(int $articleId, string $title, string $content): void
    {
        $this->builder
            ->update('articles')
            ->set('title', ':title')
            ->set('content', ':content')
            ->where('id = :id')
            ->setParameter('title', $title)
            ->setParameter('content', $content)
            ->setParameter('id', $articleId)
            ->executeStatement();
    }

    public function delete(int $articleId)
    {
        $this->builder
            ->delete('articles')
            ->where('id = :id')
            ->setParameter('id', $articleId)
            ->executeStatement();
        ;
    }

    private function buildModel(\stdClass $article): Article
    {
        return new Article(
            (int)$article->id,
            (int)$article->user_id,
            $article->title,
            $article->content,
            'https://placehold.co/600x400/gray/white?text=Some+News',
            $article->date
        );
    }

}