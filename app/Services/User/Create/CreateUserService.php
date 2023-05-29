<?php declare(strict_types=1);

namespace App\Services\User\Create;

use App\Core\Session;
use App\Models\User;
use App\Repositories\User\UserRepository;

class CreateUserService
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function execute(CreateUserRequest $request): ?User
    {
        $user = new User(
            $request->getEmail(),
            $request->getPassword()
        );

        if($this->userRepository->authenticate($user)){
            Session::flash('errors', 'User with this email already exists');
            return null;
        };

        $this->userRepository->store($user);
        return $user;
    }

}