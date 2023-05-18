<?php

use App\Console\ConsoleRouter;

require_once 'vendor/autoload.php';

$response = ConsoleRouter::route($argv);
if(!$response){
    echo "Command not found";
}else {
    $response->execute();
}

