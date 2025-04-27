<?php

namespace App\Models;

use App\Interfaces\PropertyInterface;

class Property implements PropertyInterface
{
    private $id;
    private $name;
    private $address;
    private $image;
    private $createdAt;
    private $updatedAt;

    public function __construct(int $id, string $name, string $address, string $image, string $createdAt, string $updatedAt)
    {
        $this->id = $id;
        $this->name = $name;
        $this->address = $address;
        $this->image = $image;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getAddress(): string
    {
        return $this->address;
    }

    public function getImage(): string
    {
        return $this->image;
    }

    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): string
    {
        return $this->updatedAt;
    }
}
