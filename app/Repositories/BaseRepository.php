<?php

namespace App\Repositories;

use App\Interfaces\BaseRepositoryInterface;
use App\Interfaces\DatabaseInterface;
use PDO;

abstract class BaseRepository implements BaseRepositoryInterface
{
    protected $db;

    public function __construct(DatabaseInterface $db)
    {
        $this->db = $db->getConnection(); 
    }

    public function create(array $data): int
    {
        $columns = implode(", ", array_keys($data));
        $placeholders = ":" . implode(", :", array_keys($data));

        $sql = "INSERT INTO " . $this->getTable() . " ($columns) VALUES ($placeholders)";
        $stmt = $this->db->prepare($sql);
        
        foreach ($data as $key => $value) {
            $stmt->bindValue(":$key", $value); 
        }

        $stmt->execute();
        
        return $this->db->lastInsertId();
    }

    public function find(int $id): ?array
    {
        $sql = "SELECT * FROM " . $this->getTable() . " WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function update(int $id, array $data): bool
    {
        $setClause = "";
        foreach ($data as $key => $value) {
            $setClause .= "$key = :$key, ";
        }
        $setClause = rtrim($setClause, ", ");

        $sql = "UPDATE " . $this->getTable() . " SET $setClause, updated_at = CURRENT_TIMESTAMP WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id);


        foreach ($data as $key => $value) {
            $stmt->bindValue(":$key", $value); 
        }

        $stmt->execute();
        
        return $stmt->rowCount() > 0;
    }

    public function delete(int $id): bool
    {
        $sql = "DELETE FROM " . $this->getTable() . " WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        
        return $stmt->rowCount() > 0;
    }

    public function all(): array
    {
        $sql = "SELECT * FROM " . $this->getTable();
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findByField(string $field, $value): ?array
    {
        $sql = "SELECT * FROM " . $this->getTable() . " WHERE $field = :value";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':value', $value);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    abstract protected function getTable(): string;
}
