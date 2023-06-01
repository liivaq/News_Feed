<?php declare(strict_types=1);

namespace App\Controllers;

use App\Core\Redirect;
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
        return new View('user/login', []);
    }

    public function login(): Redirect
    {
        $email = $_POST['email'];
        $password = $_POST['password'];

        $user = $this->loginUserService->execute($email, $password);

        if (!$user) {
            Session::flash('email', $email);
            Session::flash('errors', 'Invalid email address or password');
            return new Redirect('/login');
        }

        Session::put('user', $user);

        return new Redirect('/');
    }

    public function logout(): Redirect
    {
        Session::destroy();
        return new Redirect('/');
    }

}