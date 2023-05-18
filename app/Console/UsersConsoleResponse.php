<?php

namespace App\Console;

use App\Models\User;
use App\Services\User\IndexUserService;
use App\Services\User\Show\ShowUserRequest;
use App\Services\User\Show\ShowUserService;

class UsersConsoleResponse
{
    private ?int $id;
    public function __construct($id){
        $this->id = $id;
    }

    public function execute(){
        if(!$this->id){
            $this->indexUsers();
            exit;
        }
        $this->showUsers();
    }


    public function indexUsers()
    {
        $service = new IndexUserService();
        $users = $service->execute();
        $this->printUsers($users);
    }

    public function showUsers()
    {
        $service = new ShowUserService();
        $response = $service->execute(new ShowUserRequest($this->id));
        $this->printUser($response->getUser(), $response->getArticles());
    }

    private function printUsers($users)
    {

        foreach ($users as $user) {
            echo "|| {$user->getName()} ||" . PHP_EOL;
            echo 'Username: ' . $user->getUsername() . PHP_EOL;
            echo 'E-mail: ' . $user->getEmail() . PHP_EOL;
            echo 'Phone: ' . $user->getPhone() . PHP_EOL;
            echo '__________________________________________________' . PHP_EOL;
        }
    }

    private function printUser(User $user, array $articles)
    {
        echo 'All articles by :' . $user->getName() . '(user id:' . $user->getId() . ')' . PHP_EOL;
        echo '__________________________________________________' . PHP_EOL;

        foreach ($articles as $key => $article) {
            echo "[$key]" . PHP_EOL;
            echo 'Title: ' . $article->getTitle() . PHP_EOL;
            echo 'Body: ' . $article->getBody() . PHP_EOL;
            echo '__________________________________________________' . PHP_EOL;
        }
    }
}