<?php declare(strict_types=1);

namespace App\Console;

use App\Models\Article;
use App\Models\Comment;
use App\Models\User;
use App\Services\Article\IndexArticleService;
use App\Services\Article\Show\ShowArticleRequest;
use App\Services\Article\Show\ShowArticleService;
use App\Services\User\IndexUserService;
use App\Services\User\Show\ShowUserRequest;
use App\Services\User\Show\ShowUserService;

class Console
{
    private string $command;
    private ?int $id;

    public function __construct(array $argv)
    {
        $this->command = $argv[1];
        $this->id = isset($argv[2]) ? (int)$argv[2] : null;
    }

    public function route()
    {
        if($this->command === 'articles' || $this->command ==='users'){
            return $this->{$this->command}($this->id);
        }
        echo "Command not found".PHP_EOL;
        exit;
    }

    public function articles(?int $id)
    {
        if(!$id) {
            $service = new IndexArticleService();
            $articles = $service->execute();
            $this->printArticles($articles);
            exit;
        }

        $service = new ShowArticleService();
        $response = $service->execute(new ShowArticleRequest($this->id));
        $this->showArticle($response->getArticle(), $response->getComments());
    }

    public function users(?int $id)
    {
        if(!$id) {
            $service = new IndexUserService();
            $users = $service->execute();
            $this->printUsers($users);
            exit;
        }

        $service = new ShowUserService();
        $response = $service->execute(new ShowUserRequest($this->id));
        $this->showUser($response->getUser(), $response->getArticles());
    }

    private function printArticles($articles){
        /** @var Article $article */
        foreach ($articles as $article){
            echo "|| {$article->getTitle()} ||".PHP_EOL;
            echo $article->getBody().PHP_EOL;
            echo 'Written by: '.$article->getAuthor()->getName().PHP_EOL;
            echo '__________________________________________________'.PHP_EOL;
        }
    }

    private function showArticle(Article $article, array $comments){
        echo "|| {$article->getTitle()} ||".PHP_EOL;
        echo $article->getBody().PHP_EOL;
        echo 'Written by: '.$article->getAuthor()->getName().PHP_EOL;
        echo '__________________________________________________'.PHP_EOL;
        echo 'Comments: '.PHP_EOL;
        /** * @var Comment  $comment */
        foreach ($comments as $key => $comment){
            echo "[$key]".PHP_EOL;
            echo 'title: '.$comment->getName().PHP_EOL;
            echo 'body: '.$comment->getBody().PHP_EOL;
            echo 'author: '.$comment->getEmail().PHP_EOL;
            echo '__________________________________________________'.PHP_EOL;
        }
    }

    private function printUsers($users){
        /** @var User $user */
        foreach ($users as $user){
            echo "|| {$user->getName()} ||".PHP_EOL;
            echo 'Username: '.$user->getUsername().PHP_EOL;
            echo 'E-mail: '.$user->getEmail().PHP_EOL;
            echo 'Phone: '.$user->getPhone().PHP_EOL;
            echo '__________________________________________________'.PHP_EOL;
        }
    }

    private function showUser(User $user, array $articles){
        echo 'All articles by:'.$user->getName().PHP_EOL;
        echo '__________________________________________________'.PHP_EOL;
        echo 'Comments: '.PHP_EOL;
        /** * @var Article  $article */
        foreach ($articles as $key => $article){
            echo "[$key]".PHP_EOL;
            echo 'Title: '.$article->getTitle().PHP_EOL;
            echo 'Body: '.$article->getBody().PHP_EOL;
            echo '__________________________________________________'.PHP_EOL;
        }
    }
}