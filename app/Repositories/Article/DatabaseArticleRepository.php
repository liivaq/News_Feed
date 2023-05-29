<?php declare(strict_types=1);

namespace App\Repositories\Article;

use App\Core\Database;
use App\Exceptions\RecourseNotFoundException;
use App\Models\Article;
use Doctrine\DBAL\Connection;
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
        $articles = $this->builder
            ->select('*')
            ->from('articles')
            ->fetchAllAssociative();

        $articleCollection = [];

        if ($articles) {
            foreach ($articles as $article) {
                $articleCollection[] = $this->buildModel((object)$article);
            }
        }

        return $articleCollection;
    }

    public function getById(int $id): Article
    {
        $article = $this->builder
            ->select('*')
            ->from('articles')
            ->where('id = :id')
            ->setParameter('id', $id)
            ->fetchAssociative();

        if (!$article) {
            throw new RecourseNotFoundException('Article with id ' . $id . 'not found in database');
        }

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

        if ($articles) {
            foreach ($articles as $article) {
                $articleCollection[] = $this->buildModel((object)$article);
            }

        }
        return $articleCollection;
    }

    public function store(Article $article): Article
    {
        $this->builder
            ->insert('articles')
            ->values([
                'title' => ':title',
                'body' => ':body',
                'user_id' => ':userId',
                'created_at' => ':created_at',
                'image_url' => ':imageUrl'
            ])
            ->setParameter('title', $article->getTitle())
            ->setParameter('body', $article->getBody())
            ->setParameter('userId', $article->getAuthorId())
            ->setParameter('created_at', $article->getCreatedAt())
            ->setParameter('imageUrl', $article->getImageUrl())
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

    public function delete(int $articleId): void
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
            $article->image_url,
            $article->created_at,
            (int)$article->id
        );
    }

}