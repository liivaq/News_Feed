<?php declare(strict_types=1);

return [
    ['GET', '/', ['App\Controllers\ArticleController', 'index']],
    ['GET', '/articles', ['App\Controllers\ArticleController', 'index']],
    ['GET', '/articles/{id:\d+}', ['App\Controllers\ArticleController', 'show']],
    ['GET', '/users', ['App\Controllers\UserController', 'index']],
    ['GET', '/users/{id:\d+}', ['App\Controllers\UserController', 'show']]
];