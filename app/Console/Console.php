<?php declare(strict_types=1);

namespace App\Console;

use App\Core\Container;

class Console
{
    public function route(array $argv)
    {
        $commands = [
            'articles' => ArticleResponse::class,
            'users' => UserResponse::class
        ];

        $command = $argv[1] ?? null;
        $id = isset($argv[2]) ? (int)$argv[2] : null;

        if(array_key_exists($command, $commands)){
            $response = Container::get()->get($commands[$command]);
            return $response->execute($id);
        }
        return null;
    }
}