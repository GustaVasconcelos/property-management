<?php

namespace App\Repositories;

use App\Interfaces\PropertyRepositoryInterface;
use App\Interfaces\DatabaseInterface;
use App\Models\Property;
use PDO;

class PropertyRepository extends BaseRepository implements PropertyRepositoryInterface
{
    private $database;

    public function __construct(DatabaseInterface $database)
    {
        $this->database = $database;
        parent::__construct($database);
    }

    protected function getTable(): string
    {
        return 'properties';
    }

    protected function mapToModel(array $data): Property
    {
        return new Property(
            $data['id'],
            $data['name'],
            $data['address'],
            $data['image'],
            $data['created_at'],
            $data['updated_at']
        );
    }

    public function findByName(string $name): ?Property
    {
        $sql = "SELECT * FROM " . $this->getTable() . " WHERE name = :name";
        $stmt = $this->database->prepare($sql);
        $stmt->bindParam(':name', $name);
        $stmt->execute();

        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $data ? $this->mapToModel($data) : null;
    }
}
