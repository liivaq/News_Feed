<?php

namespace App\Services\User;

use App\ApiClient;

class IndexUserService
{
    private ApiClient $client;

    public function __construct()
    {
        $this->client = new ApiClient();
    }
    public function execute(): array
    {
        return $this->client->getUsers();
    }
}