<?php

namespace App\Services;

use App\Interfaces\PropertyRepositoryInterface;
use App\Helpers\FormatResult;
use App\Helpers\ImageUploadHelper;
use Exception;

class PropertyService
{
    private $propertyRepository;
    private $formatResult;
    private $imageUploadHelper;

    public function __construct(PropertyRepositoryInterface $propertyRepository, FormatResult $formatResult, ImageUploadHelper $imageUploadHelper)
    {
        $this->propertyRepository = $propertyRepository;
        $this->formatResult = $formatResult;
        $this->imageUploadHelper = $imageUploadHelper;
    }

    public function createProperty(array $data): array
    {
        try {
            $imagePath = $this->imageUploadHelper->uploadImage($data['image']);
            $data['image'] = $imagePath;

            $propertyId = $this->propertyRepository->create($data);

            $property = $this->propertyRepository->find($propertyId);
            return $this->formatResult->success($property, 'Propriedade criada com sucesso');
        } catch (Exception $e) {
            return $this->formatResult->error('Erro ao criar propriedade: ' . $e->getMessage(), 500);
        }
    }

    public function getPropertyById(int $id): array
    {
        try {
            $property = $this->propertyRepository->find($id);
            if ($property) {
                return $this->formatResult->success($property, 'Propriedade encontrada');
            }
            return $this->formatResult->error('Propriedade não encontrada', 404);
        } catch (Exception $e) {
            return $this->formatResult->error('Erro ao buscar propriedade: ' . $e->getMessage(), 500);
        }
    }

    public function updateProperty(int $id, array $data): array
    {
        try {
            $property = $this->propertyRepository->find($id);

            if (isset($data['image']) && $data['image']) {
                $oldImagePath = $property['image'] ?? null; 
                $imagePath = $this->imageUploadHelper->uploadImage($data['image'], $oldImagePath); 
                $data['image'] = $imagePath;
            }

            $updated = $this->propertyRepository->update($id, $data);

            if ($updated) {
                return $this->formatResult->success(null, 'Propriedade atualizada com sucesso');
            }

            return $this->formatResult->error('Falha ao atualizar propriedade', 400);
        } catch (Exception $e) {
            return $this->formatResult->error('Erro ao atualizar propriedade: ' . $e->getMessage(), 500);
        }
    }

    public function deleteProperty(int $id): array
    {
        try {
            $property = $this->propertyRepository->find($id);

            if ($property['image'] && file_exists($property['image'])) {
                unlink($property['image']);
            }

            $deleted = $this->propertyRepository->delete($id);
            
            if ($deleted) {
                return $this->formatResult->success(null, 'Propriedade deletada com sucesso');
            }
            return $this->formatResult->error('Falha ao deletar propriedade', 400);
        } catch (Exception $e) {
            return $this->formatResult->error('Erro ao deletar propriedade: ' . $e->getMessage(), 500);
        }
    }

    public function getAllProperties(): array
    {
        try {
            $properties = $this->propertyRepository->all();
            return $this->formatResult->success($properties, 'Propriedades recuperadas com sucesso');
        } catch (Exception $e) {
            return $this->formatResult->error('Erro ao buscar propriedades: ' . $e->getMessage(), 500);
        }
    }
}
