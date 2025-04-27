<?php

namespace App;

use App\Repositories\PropertyRepository;
use App\Repositories\PropertyRepositoryInterface;
use App\Services\PropertyService;
use App\Helpers\FormatResult;
use App\Helpers\ImageUploadHelper;
use App\Controllers\PropertyController;
use App\Database\Database; 
use App\Models\Property;

class Container
{
    public static function get($class)
    {
        switch ($class) {
            case PropertyRepositoryInterface::class:
                return new PropertyRepository(self::get(Database::class)); 
            case FormatResult::class:
                return new FormatResult();
            case ImageUploadHelper::class:
                return new ImageUploadHelper();
            case PropertyService::class:
                return new PropertyService(
                    self::get(PropertyRepositoryInterface::class), 
                    self::get(FormatResult::class),
                    self::get(ImageUploadHelper::class)
                );
            case PropertyController::class:
                return new PropertyController(
                    self::get(PropertyService::class),
                    self::get(FormatResult::class)
                );
            case Database::class:
                return new Database();
            default:
                throw new \Exception("Classe não encontrada no container");
        }
    }
}
