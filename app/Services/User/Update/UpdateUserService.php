<?php declare(strict_types=1);

namespace App\Services\User\Update;

use App\Repositories\User\UserRepository;
use App\Services\Article\Update\UpdateArticleResponse;

class UpdateUserService
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function execute(UpdateUserRequest $request): UpdateUserResponse
    {
        $user = $this->userRepository->getById($request->getId());

        $user->update([
            'email' => $request->getEmail(),
            'username' => $request->getUsername()
        ]);

        $this->userRepository->update($user);

        return new UpdateUserResponse($user);
    }

}