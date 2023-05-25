<?php declare(strict_types=1);

use App\Core\Renderer;
use App\Core\Router;
use App\Core\View;
use App\Redirect;
use App\Services\Article\Modify\ModifyResponse;

require_once '../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . "/../");
$dotenv->load();

$routes = require_once '../routes.php';
$response = Router::route($routes);
$renderer = new Renderer();

if ($response instanceof View) {
    echo $renderer->render($response);
}

if($response instanceof ModifyResponse){
    [$task] = $response->getResponse();

    if($task instanceof Redirect){
        header('Location: '.$task->getUrl());
    }

    if($task instanceof View){
        echo $renderer->render($task);
    }
}