<?php declare(strict_types=1);

use App\Console\ConsoleRouter;

require_once 'vendor/autoload.php';

$response = ConsoleRouter::route($argv);

if(!$response){
    echo 'Command not found'.PHP_EOL;
}else {
    $response->execute();
}

