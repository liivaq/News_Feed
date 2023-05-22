<?php declare(strict_types=1);

namespace App\Console;

class Console
{
    public static function route(array $argv)
    {
        $command = $argv[1] ?? null;
        $id = isset($argv[2]) ? (int)$argv[2] : null;

        switch ($command) {
            case 'articles';
                return new ArticleResponse($id);
            case 'users';
                return new UserResponse($id);
            default:
                return null;
        }
    }

}