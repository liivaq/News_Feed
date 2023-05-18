<?php

use App\Console\Console;

require_once 'vendor/autoload.php';

$console = new Console($argv);
$console->route();
