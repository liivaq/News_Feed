<?php declare(strict_types=1);

namespace App\Core;
use DI\ContainerBuilder;

class Container
{
   private \DI\Container $container;

   public function __construct(){
       $containerBuilder = new ContainerBuilder();
       $containerBuilder->addDefinitions(dirname(__DIR__, 2).'/config.php');
       $this->container = $containerBuilder->build();
   }

    public function getContainer(): \DI\Container
    {
        return $this->container;
    }
}