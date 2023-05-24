<?php declare(strict_types=1);

return [
    ['GET', '/', ['App\Controllers\ArticleController', 'index']],
    ['GET', '/articles', ['App\Controllers\ArticleController', 'index']],
    ['GET', '/articles/{id:\d+}', ['App\Controllers\ArticleController', 'show']],
    ['GET', '/articles/create', ['App\Controllers\ArticleController', 'create']],
    ['GET', '/users', ['App\Controllers\UserController', 'index']],
    ['GET', '/users/{id:\d+}', ['App\Controllers\UserController', 'show']],
    ['POST', '/articles/edit/{id:\d+}', ['App\Controllers\ArticleController', 'edit']],
    ['POST', '/articles/create', ['App\Controllers\ArticleController', 'create']],
    ['POST', '/articles/edit', ['App\Controllers\ArticleController', 'update']],
    ['POST', '/articles/delete', ['App\Controllers\ArticleController', 'delete']]
];