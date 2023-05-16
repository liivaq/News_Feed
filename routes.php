<?php declare(strict_types=1);

return [
    ['GET', '/', ['App\Controllers\ArticleController', 'articles']],
    ['GET', '/articles', ['App\Controllers\ArticleController', 'articles']],
    ['GET', '/articles/{id:\d+}', ['App\Controllers\ArticleController', 'singleArticle']],
    ['GET', '/users', ['App\Controllers\ArticleController', 'users']],
    ['GET', '/users/{id:\d+}', ['App\Controllers\ArticleController', 'singleUser']]
];