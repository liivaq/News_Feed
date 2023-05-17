<?php

namespace App\Services\User\Show;

use App\ApiClient;
use App\Exceptions\RecourseNotFoundException;

class ShowUserService
{
    private ApiClient $client;

    public function __construct()
    {
        $this->client = new ApiClient();
    }
    public function execute(ShowUserRequest $request): ShowUserResponse
    {
        $user = $this->client->getSingleUser($request->getUserId());

        if($user == null){
            throw new RecourseNotFoundException('User by id '.$request->getUserId().' not found');
        }

        $articles = $this->client->getArticlesByUserId($user->getId());

        return new ShowUserResponse($user, $articles);
    }

}