<?php declare(strict_types=1);

return [
    ['GET', '/', ['App\Controllers\ArticleController', 'index']],
    ['GET', '/articles', ['App\Controllers\ArticleController', 'index']],
    ['GET', '/articles/{id:\d+}', ['App\Controllers\ArticleController', 'show']],

    ['GET', '/articles/edit/{id:\d+}', ['App\Controllers\ArticleController', 'updateView']],
    ['POST', '/articles/edit/{id:\d+}', ['App\Controllers\ArticleController', 'update']],

    ['GET', '/articles/create', ['App\Controllers\ArticleController', 'createView']],
    ['POST', '/articles/create', ['App\Controllers\ArticleController', 'create']],

    ['POST', '/articles/delete/{id:\d+}', ['App\Controllers\ArticleController', 'delete']],


    ['GET', '/users', ['App\Controllers\UserController', 'index']],
    ['GET', '/users/{id:\d+}', ['App\Controllers\UserController', 'show']],

    ['POST', '/comment/{id:\d+}', ['App\Controllers\CommentController', 'create']],
];