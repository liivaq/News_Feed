<?php declare(strict_types=1);

use App\Console\Console;

require_once 'vendor/autoload.php';

$console = new Console();
$response = $console->route($argv);

if(!$response){
    echo 'Command not found'.PHP_EOL;
}else {
    $response->execute();
}

