<?php

use PHPUnit\Framework\TestCase;
use App\Services\PropertyService;
use App\Interfaces\PropertyRepositoryInterface;
use App\Helpers\FormatResult;
use App\Helpers\ImageUploadHelper;
use PHPUnit\Framework\MockObject\MockObject;
use Exception;

class PropertyServiceTest extends TestCase
{
    private PropertyService $propertyService;
    private MockObject $propertyRepositoryMock;
    private MockObject $formatResultMock;
    private MockObject $imageUploadHelperMock;

    protected function setUp(): void
    {
        $this->propertyRepositoryMock = $this->createMock(PropertyRepositoryInterface::class);
        $this->formatResultMock = $this->createMock(FormatResult::class);
        $this->imageUploadHelperMock = $this->createMock(ImageUploadHelper::class);

        $this->propertyService = new PropertyService(
            $this->propertyRepositoryMock,
            $this->formatResultMock,
            $this->imageUploadHelperMock
        );
    }

    public function testCreatePropertySuccess(): void
    {
        $data = [
            'name' => 'Property 1',
            'image' => [
                'name' => 'image.jpg',
                'tmp_name' => '/tmp/phpYzdqkD',
                'error' => UPLOAD_ERR_OK
            ]
        ];

        $uploadedPath = 'uploads/properties/property_image.jpg';

        $this->imageUploadHelperMock->method('uploadImage')->willReturn($uploadedPath);
        $this->propertyRepositoryMock->method('create')->willReturn(1);
        $this->propertyRepositoryMock->method('find')->willReturn([
            'id' => 1, 'name' => 'Property 1', 'image' => $uploadedPath
        ]);

        $this->formatResultMock->method('success')->willReturn([
            'status' => 'success',
            'data' => ['id' => 1, 'name' => 'Property 1', 'image' => $uploadedPath],
            'message' => 'Propriedade criada com sucesso'
        ]);

        $result = $this->propertyService->createProperty($data);

        $this->assertEquals('success', $result['status']);
    }

    public function testCreatePropertyFailure(): void
    {
        $data = [
            'name' => 'Property 2',
            'image' => [
                'name' => 'img.jpg',
                'tmp_name' => '/tmp/a',
                'error' => UPLOAD_ERR_OK
            ]
        ];

        $this->imageUploadHelperMock->method('uploadImage')->willThrowException(new Exception('Erro no envio da imagem.'));

        $this->formatResultMock->method('error')->willReturn([
            'status' => 'error',
            'message' => 'Erro ao criar propriedade: Erro no envio da imagem.',
            'code' => 500
        ]);

        $result = $this->propertyService->createProperty($data);

        $this->assertEquals('error', $result['status']);
    }

    public function testGetPropertyByIdSuccess(): void
    {
        $property = ['id' => 1, 'name' => 'Casa'];

        $this->propertyRepositoryMock->method('find')->willReturn($property);

        $this->formatResultMock->method('success')->willReturn([
            'status' => 'success',
            'data' => $property,
            'message' => 'Propriedade encontrada'
        ]);

        $result = $this->propertyService->getPropertyById(1);

        $this->assertEquals('success', $result['status']);
    }

    public function testGetPropertyByIdNotFound(): void
    {
        $this->propertyRepositoryMock->method('find')->willReturn(null);

        $this->formatResultMock->method('error')->willReturn([
            'status' => 'error',
            'message' => 'Propriedade não encontrada',
            'code' => 404
        ]);

        $result = $this->propertyService->getPropertyById(99);

        $this->assertEquals('error', $result['status']);
    }

    public function testGetPropertyByIdException(): void
    {
        $this->propertyRepositoryMock->method('find')->willThrowException(new Exception('Erro inesperado'));

        $this->formatResultMock->method('error')->willReturn([
            'status' => 'error',
            'message' => 'Erro ao buscar propriedade: Erro inesperado',
            'code' => 500
        ]);

        $result = $this->propertyService->getPropertyById(1);

        $this->assertEquals('error', $result['status']);
    }

    public function testUpdatePropertySuccess(): void
    {
        $data = [
            'name' => 'Atualizada',
            'image' => [
                'name' => 'new.jpg',
                'tmp_name' => '/tmp/x',
                'error' => UPLOAD_ERR_OK
            ]
        ];

        $this->propertyRepositoryMock->method('find')->willReturn(['image' => 'old.jpg']);
        $this->imageUploadHelperMock->method('uploadImage')->willReturn('uploads/new.jpg');
        $this->propertyRepositoryMock->method('update')->willReturn(true);

        $this->formatResultMock->method('success')->willReturn([
            'status' => 'success',
            'message' => 'Propriedade atualizada com sucesso'
        ]);

        $result = $this->propertyService->updateProperty(1, $data);

        $this->assertEquals('success', $result['status']);
    }

    public function testUpdatePropertyFailure(): void
    {
        $this->propertyRepositoryMock->method('find')->willReturn(['image' => 'old.jpg']);
        $this->imageUploadHelperMock->method('uploadImage')->willReturn('uploads/new.jpg');
        $this->propertyRepositoryMock->method('update')->willReturn(false);

        $this->formatResultMock->method('error')->willReturn([
            'status' => 'error',
            'message' => 'Falha ao atualizar propriedade',
            'code' => 400
        ]);

        $result = $this->propertyService->updateProperty(1, ['name' => 'x']);

        $this->assertEquals('error', $result['status']);
    }

    public function testUpdatePropertyException(): void
    {
        $this->propertyRepositoryMock->method('find')->willThrowException(new Exception('Erro ao atualizar'));

        $this->formatResultMock->method('error')->willReturn([
            'status' => 'error',
            'message' => 'Erro ao atualizar propriedade: Erro ao atualizar',
            'code' => 500
        ]);

        $result = $this->propertyService->updateProperty(1, []);

        $this->assertEquals('error', $result['status']);
    }

    public function testDeletePropertySuccess(): void
    {
        $this->propertyRepositoryMock->method('find')->willReturn(['image' => null]);
        $this->propertyRepositoryMock->method('delete')->willReturn(true);

        $this->formatResultMock->method('success')->willReturn([
            'status' => 'success',
            'message' => 'Propriedade deletada com sucesso'
        ]);

        $result = $this->propertyService->deleteProperty(1);

        $this->assertEquals('success', $result['status']);
    }

    public function testDeletePropertyFailure(): void
    {
        $this->propertyRepositoryMock->method('find')->willReturn(['image' => null]);
        $this->propertyRepositoryMock->method('delete')->willReturn(false);

        $this->formatResultMock->method('error')->willReturn([
            'status' => 'error',
            'message' => 'Falha ao deletar propriedade',
            'code' => 400
        ]);

        $result = $this->propertyService->deleteProperty(1);

        $this->assertEquals('error', $result['status']);
    }

    public function testDeletePropertyException(): void
    {
        $this->propertyRepositoryMock->method('find')->willThrowException(new Exception('Erro inesperado'));

        $this->formatResultMock->method('error')->willReturn([
            'status' => 'error',
            'message' => 'Erro ao deletar propriedade: Erro inesperado',
            'code' => 500
        ]);

        $result = $this->propertyService->deleteProperty(1);

        $this->assertEquals('error', $result['status']);
    }

    public function testGetAllPropertiesSuccess(): void
    {
        $properties = [
            ['id' => 1, 'name' => 'Imóvel A'],
            ['id' => 2, 'name' => 'Imóvel B']
        ];

        $this->propertyRepositoryMock->method('all')->willReturn($properties);

        $this->formatResultMock->method('success')->willReturn([
            'status' => 'success',
            'data' => $properties,
            'message' => 'Propriedades recuperadas com sucesso'
        ]);

        $result = $this->propertyService->getAllProperties();

        $this->assertEquals('success', $result['status']);
    }

    public function testGetAllPropertiesException(): void
    {
        $this->propertyRepositoryMock->method('all')->willThrowException(new Exception('Erro inesperado'));

        $this->formatResultMock->method('error')->willReturn([
            'status' => 'error',
            'message' => 'Erro ao buscar propriedades: Erro inesperado',
            'code' => 500
        ]);

        $result = $this->propertyService->getAllProperties();

        $this->assertEquals('error', $result['status']);
    }
}
