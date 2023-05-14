<?php

namespace App\Core;

use FastRoute;
class Router
{
    public static function route(){
        $dispatcher = FastRoute\simpleDispatcher(function(FastRoute\RouteCollector $r) {
            $r->addRoute('GET', '/', ['App\Controllers\ArticleController', 'articles']);
            $r->addRoute('GET', '/articles', ['App\Controllers\ArticleController', 'articles']);
            $r->addRoute('GET', '/article[/{id:\d+}]', ['App\Controllers\ArticleController', 'singleArticle']);
        });

        $httpMethod = $_SERVER['REQUEST_METHOD'];
        $uri = $_SERVER['REQUEST_URI'];

        if (false !== $pos = strpos($uri, '?')) {
            $uri = substr($uri, 0, $pos);
        }
        $uri = rawurldecode($uri);

        $routeInfo = $dispatcher->dispatch($httpMethod, $uri);
        switch ($routeInfo[0]) {
            case FastRoute\Dispatcher::NOT_FOUND:
                return "not found";
            case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
                return "not allowed";
            case FastRoute\Dispatcher::FOUND:
                $handler = $routeInfo[1];
                $vars = $routeInfo[2];
                [$controller, $method] = $handler;
                return (new $controller)->{$method}($vars);
        }
        return null;
    }
}