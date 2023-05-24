<?php declare(strict_types=1);

namespace App\Core;

use Medoo\Medoo;

class Database
{
    private Medoo $database;

    public function __construct(){
        $params = [
            'type' => 'mysql',
            'host' => 'localhost',
            'database' => 'news_feed',
            'username' => 'root',
            'password' => '',
        ];
        $this->database  = new Medoo($params);
    }

    public function getDatabase(): Medoo
    {
        return $this->database;
    }

}