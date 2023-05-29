<?php declare(strict_types=1);

namespace App\Controllers;

use App\Core\Session;
use App\Core\View;
use App\Services\User\LoginUserService;

class LoginController
{
    private LoginUserService $loginUserService;

    public function __construct(
        LoginUserService $loginUserService
    )
    {
        $this->loginUserService = $loginUserService;
    }

    public function index(): View
    {
        if(Session::get('user')){
            return new View('errors/notAuthorized', []);
        }
        return new View('login', []);
    }

    public function login()
    {
        $email = $_POST['email'];
        $password = $_POST['password'];

        $user = $this->loginUserService->execute($email, $password);

        if (!$user) {
            Session::flash('email', $email);
            Session::flash('errors', 'Invalid email address or password');
            header('Location: /login');
            exit();
        }

        Session::put('user', $user);

        header('Location: /');
        exit();
    }

    public function logout()
    {
        Session::destroy();
        header('Location: /');
        exit();
    }

}