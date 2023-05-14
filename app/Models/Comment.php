<?php

namespace App\Models;

class Comment
{
    private int $postID;
    private int $id;
    private string $name;
    private string $email;
    private string $body;

    public function __construct(
        int    $postID,
        int    $id,
        string $name,
        string $email,
        string $body)
    {
        $this->postID = $postID;
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->body = $body;
    }

    public function getPostID(): int
    {
        return $this->postID;
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

}