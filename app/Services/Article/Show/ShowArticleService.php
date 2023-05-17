<?php

namespace App\Services\Article\Show;

use App\ApiClient;
use App\Exceptions\RecourseNotFoundException;

class ShowArticleService
{
    private ApiClient $client;

    public function __construct()
    {
        $this->client = new ApiClient();
    }
    public function execute(ShowArticleRequest $request): ShowArticleResponse
    {
        $article = $this->client->getSingleArticle($request->getArticleId());

        if($article == null){
            throw new RecourseNotFoundException('Article by id '.$request->getArticleId().' not found');
        }

        $comments = $this->client->getCommentsByArticleId($article->getId());

        return new ShowArticleResponse($article, $comments);
    }

}