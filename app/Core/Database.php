<?php declare(strict_types=1);

namespace App\Core;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Medoo\Medoo;
use Doctrine\DBAL\DriverManager;

class Database
{
    private Connection $database;
    private QueryBuilder $builder;

    public function __construct(){
        $params = [
            'dbname' => 'news_feed',
            'user' => 'root',
            'password' => '',
            'host' => 'localhost',
            'driver' => 'pdo_mysql'
        ];
        $this->database  = DriverManager::getConnection($params);
        $this->builder = $this->database->createQueryBuilder();
    }

    public function getDatabase(): Connection
    {
        return $this->database;
    }

    public function getBuilder(): QueryBuilder
    {
        return $this->builder;
    }

}