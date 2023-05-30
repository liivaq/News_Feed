<?php declare(strict_types=1);

namespace App\Controllers;

use App\Core\Session;
use App\Core\View;
use App\Services\User\Update\UpdateUserRequest;
use App\Services\User\Update\UpdateUserService;

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

    public function update(array $vars)
    {
        $id = (int) $vars['id'];
        $response = $this->updateUserService->execute(new UpdateUserRequest($id, $_POST['name'], $_POST['username']));
        Session::flash('update', 'Profile successfully updated!');
        Session::put('user', $response->getUser());

        header('Location: /profile');
        exit();
    }

}