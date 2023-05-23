<?php

namespace App\Controllers;

use App\Core\View;
use App\Exceptions\RecourseNotFoundException;
use App\Services\User\IndexUserService;
use App\Services\User\Show\ShowUserRequest;
use App\Services\User\Show\ShowUserResponse;
use App\Services\User\Show\ShowUserService;

class UserController
{
    private IndexUserService $indexService;
    private ShowUserService $showService;

    public function __construct(IndexUserService $indexService, ShowUserService $showService){
        $this->indexService = $indexService;
        $this->showService = $showService;
    }

    public function index(): View
    {
        $service = $this->indexService;
        $users = $service->execute();
        return new View('users', ['users' => $users]);
    }

    public function show(array $vars): View
    {
        try {
            $userId = $vars['id'];
            $service = $this->showService;
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