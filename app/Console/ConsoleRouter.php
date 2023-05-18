<?php declare(strict_types=1);

namespace App\Console;

class ConsoleRouter
{    public static function route(array $argv)
    {
        $command = $argv[1];
        $id = $argv[2] ?? null;

        switch ($command) {
            case 'articles';
                return new ArticleConsoleResponse($id);
            case 'users';
                return new UsersConsoleResponse($id);
            default:
                return null;
        }
    }
}