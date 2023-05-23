<?php declare(strict_types=1);

namespace App\Console;

use App\Models\Article;
use App\Models\User;
use App\Services\User\IndexUserService;
use App\Services\User\Show\ShowUserRequest;
use App\Services\User\Show\ShowUserService;

class UserResponse
{
    private IndexUserService $indexUserService;
    private ShowUserService $showUserService;

    public function __construct(IndexUserService $indexUserService, ShowUserService $showUserService)
    {
        $this->indexUserService = $indexUserService;
        $this->showUserService = $showUserService;

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
        $service = $this->indexUserService;
        $users = $service->execute();
        $this->printIndex($users);
    }

    public function show($id): void
    {
        $service = $this->showUserService;
        $response = $service->execute(new ShowUserRequest($id));
        $this->printShow($response->getUser(), $response->getArticles());
    }

    private function printIndex($users): void
    {
        /** @var User $user */
        foreach ($users as $user) {
            echo '[ id ] ' . $user->getId() . PHP_EOL;
            echo '[ name ] ' . $user->getName() . PHP_EOL;
            echo '[ username ] ' . $user->getUsername() . PHP_EOL;
            echo '[ e-mail ] ' . $user->getEmail() . PHP_EOL;
            echo '[ phone ] ' . $user->getPhone() . PHP_EOL;
            echo '__________________________________________________' . PHP_EOL;
        }
    }

    private function printShow(User $user, array $articles)
    {
        echo '[ name ] ' . $user->getName() . PHP_EOL;
        echo '[ username ] ' . $user->getUsername() . PHP_EOL;
        echo '[ e-mail ] ' . $user->getEmail() . PHP_EOL;
        echo '[ phone ] ' . $user->getPhone() . PHP_EOL;
        echo '__________________________________________________' . PHP_EOL;
        echo 'User articles: ' . PHP_EOL;
        /** * @var Article $article */
        foreach ($articles as $article) {
            echo '[ title ] ' . $article->getTitle() . PHP_EOL;
            echo '[ body ] ' . $article->getBody() . PHP_EOL;
            echo '__________________________________________________' . PHP_EOL;
        }
    }
}