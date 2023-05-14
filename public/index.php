<?php declare(strict_types=1);

use App\Core\Renderer;
use App\Core\Router;

require_once '../vendor/autoload.php';

$path = Router::route();

$renderer = new Renderer();

echo $renderer->render($path);