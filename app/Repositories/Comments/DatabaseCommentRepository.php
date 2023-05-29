<?php declare(strict_types=1);

namespace App\Repositories\Comments;

use App\Core\Database;
use App\Models\Comment;
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

    public function create(Comment $comment): Comment
    {
        $this->builder
            ->insert('comments')
            ->values([
                'article_id' => ':articleId',
                'title' => ':title',
                'body' => ':body',
                'user_id' => ':user_id'
            ])
            ->setParameter('articleId', $comment->getArticleId())
            ->setParameter('title', $comment->getTitle())
            ->setParameter('body', $comment->getBody())
            ->setParameter('user_id', $comment->getUserId())
            ->executeStatement();

        $comment->setId((int) $this->connection->lastInsertId());

        return $comment;
    }

    private function buildModel(stdClass $comment): Comment
    {
        return new Comment(
            (int) $comment->article_id,
            $comment->title,
            $comment->body,
            (int) $comment->user_id,
        );
    }
}