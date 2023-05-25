<?php declare(strict_types=1);

namespace App\Models;

class Article
{
    private int $id;
    private int $authorID;
    private string $title;
    private string $body;
    private ?string $imageUrl;
    private ?User $author;
    private string $date;

    public function __construct(
        int    $id,
        int    $authorId,
        string $title,
        string $body,
        ?string $imageUrl,
        string $date,
        ?User  $author = null

    )
    {
        $this->id = $id;
        $this->authorID = $authorId;
        $this->title = $title;
        $this->body = $body;
        $this->imageUrl = $imageUrl;
        $this->date = $date;
        $this->author = $author;
    }

    public function getAuthor(): ?User
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

    public function getImageUrl(): ?string
    {
        return $this->imageUrl;
    }

    public function getDate(): string
    {
        return $this->date;
    }

    public function getAuthorId(): int
    {
        return $this->authorID;
    }

    public function setAuthor(User $author)
    {
        $this->author = $author;
    }


}