<?php

namespace App\Interfaces;

use App\Models\Property;
use App\Interfaces\BaseRepositoryInterface;

interface PropertyRepositoryInterface extends BaseRepositoryInterface
{
    public function findByName(string $name): ?Property;
}
