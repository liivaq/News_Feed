<?php declare(strict_types=1);

namespace App\Core;

use DI\ContainerBuilder;

class Container
{
    public static function get(): \DI\Container
    {
        $containerBuilder = new ContainerBuilder();
        $definitions = require_once dirname(__DIR__, 2) . '/config.php';
        $containerBuilder->addDefinitions($definitions['classes']);
        return $containerBuilder->build();
    }
}