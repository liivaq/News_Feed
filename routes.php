<?php declare(strict_types=1);

return [
    ['GET', '/', ['App\Controllers\ArticleController', 'index']],
    ['GET', '/articles', ['App\Controllers\ArticleController', 'index']],
    ['GET', '/articles/{id:\d+}', ['App\Controllers\ArticleController', 'show']],

    ['GET', '/articles/edit/{id:\d+}', ['App\Controllers\ArticleController', 'edit']],
    ['POST', '/articles/edit/{id:\d+}', ['App\Controllers\ArticleController', 'update']],

    ['GET', '/articles/create', ['App\Controllers\ArticleController', 'create']],
    ['POST', '/articles', ['App\Controllers\ArticleController', 'store']],

    ['POST', '/articles/delete/{id:\d+}', ['App\Controllers\ArticleController', 'delete']],

    ['GET', '/users', ['App\Controllers\UserController', 'index']],
    ['GET', '/users/{id:\d+}', ['App\Controllers\UserController', 'show']],

    ['GET', '/register', ['App\Controllers\UserController', 'register']],
    ['POST', '/register', ['App\Controllers\UserController', 'store']],

    ['GET', '/login', ['App\Controllers\LoginController', 'index']],
    ['POST', '/login', ['App\Controllers\LoginController', 'login']],
    ['POST', '/logout', ['App\Controllers\LoginController', 'logout']],

    ['POST', '/comment/{id:\d+}', ['App\Controllers\CommentController', 'create']],
];