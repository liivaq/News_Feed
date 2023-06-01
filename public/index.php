<?php declare(strict_types=1);

use App\Core\Renderer;
use App\Core\Router;
use App\Core\Session;
use App\Core\View;

require_once '../vendor/autoload.php';

session_start();

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . "/../");
$dotenv->load();

$routes = require_once '../routes.php';
$response = Router::route($routes);

if($response instanceof View) {
    $renderer = new Renderer();

    echo $renderer->render($response);
    Session::unflash();
}

if($response instanceof \App\Core\Redirect){
    header('Location: '.$response->getPath());
}
