<?php

namespace App\Controllers;

use App\Core\Session;
use App\Core\View;
use App\Exceptions\RecourseNotFoundException;
use App\Services\User\Create\CreateUserRequest;
use App\Services\User\Create\CreateUserService;
use App\Services\User\IndexUserService;
use App\Services\User\Show\LoginUserService;
use App\Services\User\Show\ShowUserRequest;
use App\Services\User\Show\ShowUserResponse;
use App\Services\User\Show\ShowUserService;
use App\Validator;

class UserController
{
    private IndexUserService $indexUserService;
    private ShowUserService $showUserService;
    private CreateUserService $createUserService;
    private LoginUserService $loginUserService;

    public function __construct(
        IndexUserService  $indexUserService,
        ShowUserService   $showUserService,
        CreateUserService $createUserService,
        LoginUserService $loginUserService
    )
    {
        $this->indexUserService = $indexUserService;
        $this->showUserService = $showUserService;
        $this->createUserService = $createUserService;
        $this->loginUserService = $loginUserService;
    }

    public function index(): View
    {
        $service = $this->indexUserService;
        $users = $service->execute();
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
            return new View('notFound', []);
        }
    }

    public function register(): View
    {
        return new View('register', []);
    }

    public function store()
    {
        $email = $_POST['email'];
        $password = $_POST['password'];
        $passwordRepeat = $_POST['password_repeat'];

        if (Validator::registrationForm($email, $password, $passwordRepeat)) {
            Session::flash('email', $email);
            header('Location: /register');
            exit;
        }

        if (!$this->createUserService->execute(new CreateUserRequest($email, $password))) {
            Session::flash('email', $email);
            header('Location: /register');
            exit;
        }

        Session::put('email', $email);
        header('Location: /');
    }

    public function login(): View
    {
        return new View('login', []);
    }

    public function authorize()
    {
        $email = $_POST['email'];
        $password = $_POST['password'];

        $user = $this->loginUserService->execute($email, $password);

        if(!$user){
            Session::flash('email', $email);
            Session::flash('errors', 'Invalid email address or password');
            header('Location: /login');
            exit;
        }

        Session::put('user', $user);

        header('Location: /');
        exit;
    }

    public function logout(){
        Session::destroy();
        header('Location: /');
        exit;
    }
}