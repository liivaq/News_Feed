<?php declare(strict_types=1);

namespace App\Repositories\Article;

use App\Core\Database;
use App\Models\Article;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use Doctrine\DBAL\Query\QueryBuilder;
use stdClass;

class DatabaseArticleRepository implements ArticleRepository
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
        try {

            $articles = $this->builder
                ->select('*')
                ->from('articles')
                ->fetchAllAssociative();

            $articleCollection = [];
            foreach ($articles as $article) {
                $articleCollection[] = $this->buildModel((object)$article);
            }
            return $articleCollection;

        } catch (Exception $e) {
            return [];
        }
    }

    public function getById(int $id): ?Article
    {
        try {
            $article = $this->builder
                ->select('*')
                ->from('articles')
                ->where('id = :id')
                ->setParameter('id', $id)
                ->fetchAssociative();

        } catch (Exception $e) {
            return null;
        }

        if (!$article) {
            return null;
        }

        return $this->buildModel((object)$article);
    }

    public function getByUserId(int $userId): array
    {
        try {
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
        } catch (Exception $e) {
            return [];
        }
    }

    public function store(Article $article): Article
    {
        $this->builder
            ->insert('articles')
            ->values([
                'title' => ':title',
                'body' => ':body',
                'user_id' => ':userId',
                'created_at' => ':created_at'
            ])
            ->setParameter('title', $article->getTitle())
            ->setParameter('body', $article->getBody())
            ->setParameter('userId', $article->getAuthorId())
            ->setParameter('created_at', $article->getCreatedAt())
            ->executeStatement();

        $article->setId((int)$this->connection->lastInsertId());
        return $article;
    }

    public function update(Article $article): void
    {
        $this->builder
            ->update('articles')
            ->set('title', ':title')
            ->set('body', ':body')
            ->where('id = :id')
            ->setParameter('title', $article->getTitle())
            ->setParameter('body', $article->getBody())
            ->setParameter('id', $article->getId())
            ->executeStatement();
    }

    public function delete(int $articleId)
    {
        $this->builder
            ->delete('articles')
            ->where('id = :id')
            ->setParameter('id', $articleId)
            ->executeStatement();
    }

    private function buildModel(stdClass $article): Article
    {
        return new Article(
            (int)$article->user_id,
            $article->title,
            $article->body,
            'https://picsum.photos/id/' . rand(20, 100) . '/200',
            $article->created_at,
            (int) $article->id
        );
    }

}