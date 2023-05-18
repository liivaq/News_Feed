<?php

namespace App\Console;

use App\Models\Article;
use App\Services\Article\IndexArticleService;
use App\Services\Article\Show\ShowArticleRequest;
use App\Services\Article\Show\ShowArticleService;

class ArticleConsoleResponse
{
    private ?int $id;
    public function __construct($id){
        $this->id = $id;
    }

    public function execute(){
        if(!$this->id){
            $this->indexArticles();
            exit;
        }
        $this->showArticles();
    }

    public function indexArticles()
    {
        $service = new IndexArticleService();
        $articles = $service->execute();
        $this->printArticles($articles);
    }

    public function showArticles()
    {
        $service = new ShowArticleService();
        $response = $service->execute(new ShowArticleRequest($this->id));
        $this->printArticle($response->getArticle(), $response->getComments());
    }

    private function printArticles($articles)
    {

        foreach ($articles as $article) {
            echo "|| {$article->getTitle()} ||" . PHP_EOL;
            echo $article->getBody() . PHP_EOL;
            echo 'Written by: ' . $article->getAuthor()->getName() . PHP_EOL;
            echo '__________________________________________________' . PHP_EOL;
        }
    }

    private function printArticle(Article $article, array $comments)
    {
        echo "|| {$article->getTitle()} ||" . PHP_EOL;
        echo $article->getBody() . PHP_EOL;
        echo 'Written by: ' . $article->getAuthor()->getName() . PHP_EOL;
        echo '__________________________________________________' . PHP_EOL;
        echo 'Comments: ' . PHP_EOL;

        foreach ($comments as $key => $comment) {
            echo "[$key]" . PHP_EOL;
            echo 'title: ' . $comment->getName() . PHP_EOL;
            echo 'body: ' . $comment->getBody() . PHP_EOL;
            echo 'author: ' . $comment->getEmail() . PHP_EOL;
            echo '__________________________________________________' . PHP_EOL;
        }
    }
}