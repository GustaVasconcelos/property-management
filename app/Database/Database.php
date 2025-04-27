<?php

namespace App\Database;

use App\Interfaces\DatabaseInterface;
use PDO;

class Database implements DatabaseInterface
{
    private $connection;

    public function __construct()
    {
        $host = getenv('DB_HOST');         
        $dbName = getenv('DB_NAME');       
        $username = getenv('DB_USER');    
        $password = getenv('DB_PASSWORD'); 

        $dsn = "mysql:host=$host;dbname=$dbName";
        $this->connection = new PDO($dsn, $username, $password);

        $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function getConnection(): PDO
    {
        return $this->connection;
    }
}
