<?php declare(strict_types=1);

namespace App\Console;

use App\Models\Article;
use App\Models\Comment;
use App\Services\Article\IndexArticleService;
use App\Services\Article\Show\ShowArticleRequest;
use App\Services\Article\Show\ShowArticleService;

class ArticleResponse
{
    private IndexArticleService $indexArticleService;
    private ShowArticleService $showArticleService;

    public function __construct(IndexArticleService $indexArticleService, ShowArticleService $showArticleService)
    {
        $this->showArticleService = $showArticleService;
        $this->indexArticleService = $indexArticleService;
    }

    public function execute($id): void
    {
        if (!$id) {
            $this->index();
            exit;
        }
        $this->show($id);
    }

    public function index(): void
    {
        $service = $this->indexArticleService;
        $articles = $service->execute();
        $this->printIndex($articles);
    }

    public function show($id): void
    {
        $service = $this->showArticleService;
        $response = $service->execute(new ShowArticleRequest($id));
        $this->printShow($response->getArticle(), $response->getComments());
    }

    private function printIndex(array $articles): void
    {
        /** @var Article $article */
        foreach ($articles as $article) {
            echo '[ id ]: ' . $article->getId() . PHP_EOL;
            echo '[ title ]: ' . $article->getTitle() . PHP_EOL;
            echo $article->getBody() . PHP_EOL;
            echo '[ written by ]: ' . $article->getAuthor()->getName() . PHP_EOL;
            echo '__________________________________________________' . PHP_EOL;
        }
    }

    private function printShow(Article $article, array $comments)
    {
        echo '[ Article title ] ' . $article->getTitle() . PHP_EOL;
        echo '[ body ] ' . $article->getBody() . PHP_EOL;
        echo '[ written by ] ' . $article->getAuthor()->getName() . PHP_EOL;
        echo '__________________________________________________' . PHP_EOL;
        echo 'Comments: ' . PHP_EOL;
        /** *  @var Comment $comment */
        foreach ($comments as $comment) {
            echo '[ Comment title ]: ' . $comment->getTitle() . PHP_EOL;
            echo '[ body ]: ' . $comment->getBody() . PHP_EOL;
            echo '[ author ]: ' . $comment->getEmail() . PHP_EOL;
            echo '__________________________________________________' . PHP_EOL;
        }
    }
}