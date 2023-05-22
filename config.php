<?php

use App\Repositories\Article\ArticleRepository;
use App\Repositories\Article\JsonPlaceholderArticleRepository;
use App\Repositories\Comments\CommentRepository;
use App\Repositories\Comments\JsonPlaceholderCommentRepository;
use App\Repositories\User\JsonPlaceholderUserRepository;
use App\Repositories\User\UserRepository;
use App\Services\Article\IndexArticleService;
use App\Services\Article\Show\ShowArticleService;
use App\Services\User\IndexUserService;
use App\Services\User\Show\ShowUserService;

return [
    ArticleRepository::class => DI\create(JsonPlaceholderArticleRepository::class),
    UserRepository::class => DI\create(JsonPlaceholderUserRepository::class),
    CommentRepository::class => DI\create(JsonPlaceholderCommentRepository::class),
    IndexArticleService::class => DI\autowire(),
    ShowArticleService::class => DI\autowire(),
    IndexUserService::class => DI\autowire(),
    ShowUserService::class => DI\autowire()
];