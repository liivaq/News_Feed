<?php

namespace App\Repositories\Comments;

use App\Core\Database;
use App\Models\Comment;
use Carbon\Carbon;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use Doctrine\DBAL\Query\QueryBuilder;
use stdClass;

class DatabaseCommentRepository implements CommentRepository
{
    private QueryBuilder $builder;
    private Connection $connection;

    public function __construct()
    {
        $this->connection = Database::getConnection();
        $this->builder = $this->connection->createQueryBuilder();
    }

    public function getByArticleId(int $articleId): array
    {
        try {
            $comments = $this->builder
                ->select('*')
                ->from('comments')
                ->where('article_id = :id')
                ->setParameter('id', $articleId)
                ->fetchAllAssociative();

            $commentCollection = [];

            foreach ($comments as $comment) {
                $commentCollection[] = $this->buildModel((object)$comment);
            }

            return $commentCollection;

        } catch (Exception $e) {
            return [];
        }

    }

    public function create(Comment $comment): void
    {
        $this->builder
            ->insert('comments')
            ->values([
                'article_id' => ':articleId',
                'name' => ':name',
                'body' => ':body',
                'email' => ':email'
            ])
            ->setParameter('articleId', $comment->getArticleId())
            ->setParameter('name', $comment->getName())
            ->setParameter('body', $comment->getBody())
            ->setParameter('email', $comment->getEmail())
            ->executeStatement();

        $comment->setId((int) $this->connection->lastInsertId());

    }

    private function buildModel(stdClass $comment): Comment
    {
        return new Comment(
            (int) $comment->article_id,
            $comment->name,
            $comment->email,
            $comment->body
        );
    }
}