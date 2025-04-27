<?php

namespace App\Controllers;

use App\Services\PropertyService;
use App\Helpers\FormatResult;
use App\Requests\CreatePropertyRequest;
use App\Requests\UpdatePropertyRequest; 
use Exception;

class PropertyController
{
    private $propertyService;
    private $formatResult;

    public function __construct(PropertyService $propertyService, FormatResult $formatResult)
    {
        $this->propertyService = $propertyService;
        $this->formatResult = $formatResult;
    }

    public function create()
    {
        return $this->formatResult->view('properties/create');
    }

    public function store(CreatePropertyRequest $request)
    {
        try {
            $data = $request->validated();

            $response = $this->propertyService->createProperty($data);

            return $this->formatResult->view('properties/create', [
                'message' => $response['message']
            ]);
        } catch (Exception $e) {
            return $this->formatResult->error("Erro ao criar propriedade: " . $e->getMessage(), 500);
        }
    }

    public function show($id)
    {
        try {
            $response = $this->propertyService->getPropertyById($id);

            return $this->formatResult->view('properties/show', [
                'property' => $response['data'],
                'message' => $response['message'] ?? null
            ]);
        } catch (Exception $e) {
            return $this->formatResult->error("Erro ao buscar propriedade: " . $e->getMessage(), 500);
        }
    }

    public function edit($id)
    {
        try {
            $response = $this->propertyService->getPropertyById($id);

            return $this->formatResult->view('properties/edit', [
                'property' => $response['data']
            ]);
        } catch (Exception $e) {
            return $this->formatResult->error("Erro ao carregar a edição: " . $e->getMessage(), 500);
        }
    }

    public function update($id, UpdatePropertyRequest $request) 
    {
        try {
            $data = $request->validated();

            $response = $this->propertyService->updateProperty($id, $data);
            $property = $this->propertyService->getPropertyById($id);

            return $this->formatResult->view('properties/edit', [
                'property' => $property['data'] ?? null,
                'message' => $response['message']
            ]);
        } catch (Exception $e) {
            return $this->formatResult->error("Erro ao atualizar propriedade: " . $e->getMessage(), 500);
        }
    }

    public function destroy($id)
    {
        try {
            $response = $this->propertyService->deleteProperty($id);

            $properties = $this->propertyService->getAllProperties();

            return json_encode([
                'success' => true,
                'message' => $response['message']
            ]);
        } catch (Exception $e) {
            return json_encode([
                'success' => false,
                'message' => "Erro ao deletar a propriedade: " . $e->getMessage()
            ]);        
        }
    }

    public function index()
    {
        try {
            $response = $this->propertyService->getAllProperties();

            return $this->formatResult->view('properties/index', [
                'properties' => $response['data'],
                'message' => null
            ]);
        } catch (Exception $e) {
            return $this->formatResult->error("Erro ao buscar propriedades: " . $e->getMessage(), 500);
        }
    }
}
