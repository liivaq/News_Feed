<?php declare(strict_types=1);

namespace App\Console;

use App\Models\Article;
use App\Models\User;
use App\Services\User\IndexUserService;
use App\Services\User\Show\ShowUserRequest;
use App\Services\User\Show\ShowUserService;

class UserConsoleResponse
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
        $service = new IndexUserService();
        $users = $service->execute();
        $this->printIndex($users);
    }

    public function show(): void
    {
        $service = new ShowUserService();
        $response = $service->execute(new ShowUserRequest($this->id));
        $this->printShow($response->getUser(), $response->getArticles());
    }

    private function printIndex($users): void
    {

        foreach ($users as $user) {
            echo "|| {$user->getName()} ||" . PHP_EOL;
            echo 'Username: ' . $user->getUsername() . PHP_EOL;
            echo 'E-mail: ' . $user->getEmail() . PHP_EOL;
            echo 'Phone: ' . $user->getPhone() . PHP_EOL;
            echo '__________________________________________________' . PHP_EOL;
        }
    }

    private function printShow(User $user, array $articles)
    {
        echo "|| {$user->getName()} ||" . PHP_EOL;
        echo 'Username: ' . $user->getUsername() . PHP_EOL;
        echo 'E-mail: ' . $user->getEmail() . PHP_EOL;
        echo 'Phone: ' . $user->getPhone() . PHP_EOL;
        echo 'All articles by ' . $user->getName() . PHP_EOL;
        echo '__________________________________________________' . PHP_EOL;

        /** * @var Article $article */
        foreach ($articles as $article) {
            echo 'Title: ' . $article->getTitle() . PHP_EOL;
            echo 'Body: ' . $article->getBody() . PHP_EOL;
            echo '__________________________________________________' . PHP_EOL;
        }
    }
}