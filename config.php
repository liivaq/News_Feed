<?php

use App\Repositories\Article\ArticleRepository;
use App\Repositories\Article\DatabaseArticleRepository;
use App\Repositories\Comments\CommentRepository;
use App\Repositories\Comments\JsonPlaceholderCommentRepository;
use App\Repositories\User\JsonPlaceholderUserRepository;
use App\Repositories\User\UserRepository;

return [
    'classes' => [
        ArticleRepository::class => DI\create(DatabaseArticleRepository::class),
        UserRepository::class => DI\create(JsonPlaceholderUserRepository::class),
        CommentRepository::class => DI\create(JsonPlaceholderCommentRepository::class),
    ],
];