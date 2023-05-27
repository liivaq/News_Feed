<?php declare(strict_types=1);

use App\Core\Renderer;
use App\Core\Router;

require_once '../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . "/../");
$dotenv->load();

$routes = require_once '../routes.php';
$response = Router::route($routes);
$renderer = new Renderer();

echo $renderer->render($response);
