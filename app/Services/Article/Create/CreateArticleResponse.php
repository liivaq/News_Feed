<?php declare(strict_types=1);

namespace App\Services\Article\Create;

use App\Models\Article;

class CreateArticleResponse
{
    private Article $response;

    public function __construct(Article $article)
    {
        $this->response = $article;
    }

    public function getResponse(): Article
    {
        return $this->response;
    }

}