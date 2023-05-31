<?php

namespace App\Controllers;

use App\Core\Session;
use App\Core\View;
use App\Exceptions\RecourseNotFoundException;
use App\Services\User\Create\CreateUserRequest;
use App\Services\User\Create\CreateUserService;
use App\Services\User\IndexUserService;
use App\Services\User\Show\ShowUserRequest;
use App\Services\User\Show\ShowUserResponse;
use App\Services\User\Show\ShowUserService;
use App\Validator;

class UserController
{
    private IndexUserService $indexUserService;
    private ShowUserService $showUserService;
    private CreateUserService $createUserService;

    public function __construct(
        IndexUserService  $indexUserService,
        ShowUserService   $showUserService,
        CreateUserService $createUserService
    )
    {
        $this->indexUserService = $indexUserService;
        $this->showUserService = $showUserService;
        $this->createUserService = $createUserService;
    }

    public function index(): View
    {
        $users = $this->indexUserService->execute();
        return new View('user/index', ['users' => $users]);
    }

    public function show(array $vars): View
    {
        try {
            $userId = $vars['id'];
            $service = $this->showUserService;
            $request = $service->execute(new ShowUserRequest((int)$userId));
            $response = new ShowUserResponse($request->getUser(), $request->getArticles());
            return new View('user/show',
                [
                    'user' => $response->getUser(),
                    'articles' => $response->getArticles()
                ]);
        } catch (RecourseNotFoundException $exception) {
            return new View('errors/notFound', []);
        }
    }

    public function register(): View
    {
        if(Session::get('user')){
            return new View('errors/notAuthorized', []);
        }
        return new View('user/register', []);
    }

    public function store()
    {
        $email = $_POST['email'];
        $username = $_POST['username'];
        $password = $_POST['password'];
        $passwordRepeat = $_POST['password_repeat'];

        if (Validator::registrationForm($email, $username, $password, $passwordRepeat)) {
            Session::flash('email', $email);
            Session::flash('username', $username);
            header('Location: /register');
            exit;
        }

        $user = $this->createUserService->execute(new CreateUserRequest($email, $username, $password));

        if (!$user) {
            Session::flash('email', $email);
            header('Location: /register');
            exit;
        }

        Session::put('user', $user);
        header('Location: /');
    }
}