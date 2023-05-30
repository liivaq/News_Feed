<?php declare(strict_types=1);

namespace App\Services\Article\Update;

class UpdateArticleRequest
{
    private string $title;
    private string $body;
    private int $id;

    public function __construct(
        string $title,
        string $body,
        int $id)
    {
        $this->title = $title;
        $this->id = $id;
        $this->body = $body;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getBody(): string
    {
        return $this->body;
    }

    public function getId(): int
    {
        return $this->id;
    }

}