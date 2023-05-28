<?php

namespace App\Models;

class Comment
{
    private int $articleId;
    private string $name;
    private string $email;
    private string $body;
    private ?int $id = null;

    public function __construct(
        int    $articleId,
        string $name,
        string $email,
        string $body)
    {
        $this->articleId = $articleId;
        $this->name = $name;
        $this->email = $email;
        $this->body = $body;
    }

    public function getArticleId(): int
    {
        return $this->articleId;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getBody(): string
    {
        return $this->body;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

}