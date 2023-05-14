<?php declare(strict_types=1);

namespace App\Models;

class Article
{
    private User $author;
    private int $id;
    private string $title;
    private string $body;

    public function __construct(
        User $author,
        int $id,
        string $title,
        string $body)
    {
        $this->author = $author;
        $this->id = $id;
        $this->title = $title;
        $this->body = $body;
    }

    public function getAuthor(): User
    {
        return $this->author;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getBody(): string
    {
        return $this->body;
    }

}