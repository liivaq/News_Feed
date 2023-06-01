<?php declare(strict_types=1);

namespace App\Controllers;

use App\Core\Redirect;
use App\Core\Session;
use App\Core\View;
use App\Services\User\Update\UpdateUserRequest;
use App\Services\User\Update\UpdateUserService;
use App\Validator;

class ProfileController
{
    private UpdateUserService $updateUserService;

    public function __construct(UpdateUserService $updateUserService)
    {
        $this->updateUserService = $updateUserService;
    }

    public function edit(): View
    {
        return new View('user/profile', []);
    }

    public function update(array $vars): Redirect
    {
        $id = (int) $vars['id'];
        $email = $_POST['new_email'];
        $username = $_POST['username'];

        if(Validator::email($email)){
            return new Redirect('/profile');
        }

        $response = $this->updateUserService->execute(new UpdateUserRequest($id, $email ,$username));

        Session::flash('update', 'Profile successfully updated!');
        Session::put('user', $response->getUser());

        return new Redirect('/profile');
    }
}