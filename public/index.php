<?php declare(strict_types=1);

use App\Core\Renderer;
use App\Core\Router;

require_once '../vendor/autoload.php';

$routes = require_once '../routes.php';
$path = Router::route($routes);

$renderer = new Renderer();

echo $renderer->render($path);