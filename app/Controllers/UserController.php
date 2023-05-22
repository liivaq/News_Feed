<?php

namespace App\Controllers;

use App\Core\Container;
use App\Core\View;
use App\Exceptions\RecourseNotFoundException;
use App\Services\Article\IndexArticleService;
use App\Services\User\IndexUserService;
use App\Services\User\Show\ShowUserRequest;
use App\Services\User\Show\ShowUserResponse;
use App\Services\User\Show\ShowUserService;

class UserController
{
    private Container $container;
    public function __construct(){
        $this->container = new Container();
    }

    public function index(): View
    {
        $service = $this->container->getContainer()->get(IndexUserService::class);
        $users = $service->execute();
        return new View('users', ['users' => $users]);
    }

    public function show(array $vars): View
    {
        try {
            $userId = $vars['id'];
            $service = $this->container->getContainer()->get(ShowUserService::class);
            $request = $service->execute(new ShowUserRequest((int)$userId));
            $response = new ShowUserResponse($request->getUser(), $request->getArticles());
            return new View('singleUser',
                [
                    'user' => $response->getUser(),
                    'articles' => $response->getArticles()
                ]);
        }catch (RecourseNotFoundException $exception){
            return new View('notFound', []);
        }
    }
}