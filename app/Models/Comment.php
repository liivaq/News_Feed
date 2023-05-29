<?php

namespace App\Models;

class Comment
{
    private int $articleId;
    private string $title;
    private string $body;
    private ?string $email;
    private int $userId;
    private ?User $user = null;
    private ?int $id = null;

    public function __construct(
        int    $articleId,
        string $title,
        string $body,
        int $userId,
        string $email = null
    )
    {
        $this->articleId = $articleId;
        $this->title = $title;
        $this->body = $body;
        $this->userId = $userId;
        $this->email = $email;
    }

    public function getArticleId(): int
    {
        return $this->articleId;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function getBody(): string
    {
        return $this->body;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

}