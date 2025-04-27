<?php

namespace App\Migrations;

use App\Database\Database;

class CreatePropertiesTable
{
    private $db;

    public function __construct()
    {
        $this->db = (new Database())->getConnection();
    }

    public function up()
    {
        try {
            $sql = "
                CREATE TABLE IF NOT EXISTS properties (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    name VARCHAR(255) NOT NULL,
                    address VARCHAR(255) NOT NULL,
                    image VARCHAR(255) NOT NULL,
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
                )
            ";

            $this->db->exec($sql);
            echo "Tabela 'properties' criada com sucesso.\n";
        } catch (\PDOException $e) {
            echo "Error creating table: " . $e->getMessage() . "\n";
        }
    }
}
