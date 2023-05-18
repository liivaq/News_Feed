<?php declare(strict_types=1);

namespace App\Console;

use App\Models\Article;
use App\Models\Comment;
use App\Services\Article\IndexArticleService;
use App\Services\Article\Show\ShowArticleRequest;
use App\Services\Article\Show\ShowArticleService;

class ArticleConsoleResponse
{
    private ?int $id;

    public function __construct(?int $id)
    {
        $this->id = $id;
    }

    public function execute(): void
    {
        if (!$this->id) {
            $this->index();
            exit;
        }
        $this->show();
    }

    public function index(): void
    {
        $service = new IndexArticleService();
        $articles = $service->execute();
        $this->printIndex($articles);
    }

    public function show(): void
    {
        $service = new ShowArticleService();
        $response = $service->execute(new ShowArticleRequest($this->id));
        $this->printShow($response->getArticle(), $response->getComments());
    }

    private function printIndex(array $articles): void
    {
        /** @var Article $article */
        foreach ($articles as $article) {
            echo "|| {$article->getTitle()} ||" . PHP_EOL;
            echo $article->getBody() . PHP_EOL;
            echo 'Written by: ' . $article->getAuthor()->getName() . PHP_EOL;
            echo '__________________________________________________' . PHP_EOL;
        }
    }

    private function printShow(Article $article, array $comments)
    {
        echo "|| {$article->getTitle()} ||" . PHP_EOL;
        echo $article->getBody() . PHP_EOL;
        echo 'Written by: ' . $article->getAuthor()->getName() . PHP_EOL;
        echo '__________________________________________________' . PHP_EOL;
        echo 'Comments: ' . PHP_EOL;
        /** *  @var Comment $comment */
        foreach ($comments as $key => $comment) {
            echo "[$key]" . PHP_EOL;
            echo 'title: ' . $comment->getName() . PHP_EOL;
            echo 'body: ' . $comment->getBody() . PHP_EOL;
            echo 'author: ' . $comment->getEmail() . PHP_EOL;
            echo '__________________________________________________' . PHP_EOL;
        }
    }
}