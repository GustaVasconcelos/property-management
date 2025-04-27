<?php

use PHPUnit\Framework\TestCase;
use App\Controllers\PropertyController;
use App\Services\PropertyService;
use App\Helpers\FormatResult;
use App\Requests\CreatePropertyRequest;
use App\Requests\UpdatePropertyRequest;
use PHPUnit\Framework\MockObject\MockObject;
use Exception;

class PropertyControllerTest extends TestCase
{
    private PropertyController $propertyController;
    private MockObject $propertyServiceMock;
    private MockObject $formatResultMock;

    protected function setUp(): void
    {
        $this->propertyServiceMock = $this->createMock(PropertyService::class);
        $this->formatResultMock = $this->createMock(FormatResult::class);

        $this->propertyController = new PropertyController(
            $this->propertyServiceMock,
            $this->formatResultMock
        );
    }

    public function testCreateView(): void
    {
        $this->formatResultMock->expects($this->once())
            ->method('view')
            ->with('properties/create')
            ->willReturn('view_rendered');

        $result = $this->propertyController->create();

        $this->assertEquals('view_rendered', $result);
    }

    public function testStoreSuccess(): void
    {
        $request = $this->createMock(CreatePropertyRequest::class);

        $data = ['name' => 'Property 1'];

        $request->expects($this->once())
            ->method('validated')
            ->willReturn($data);

        $this->propertyServiceMock->expects($this->once())
            ->method('createProperty')
            ->with($data)
            ->willReturn(['message' => 'Propriedade criada com sucesso']);

        $this->formatResultMock->expects($this->once())
            ->method('view')
            ->with('properties/create', ['message' => 'Propriedade criada com sucesso'])
            ->willReturn('view_rendered');

        $result = $this->propertyController->store($request);

        $this->assertEquals('view_rendered', $result);
    }

    public function testStoreFailure(): void
    {
        $request = $this->createMock(CreatePropertyRequest::class);

        $request->expects($this->once())
            ->method('validated')
            ->willThrowException(new Exception('Erro ao validar'));

        $this->formatResultMock->expects($this->once())
            ->method('error')
            ->with('Erro ao criar propriedade: Erro ao validar', 500)
            ->willReturn('error_response');

        $result = $this->propertyController->store($request);

        $this->assertEquals('error_response', $result);
    }

    public function testShowSuccess(): void
    {
        $property = ['id' => 1, 'name' => 'Property 1'];

        $this->propertyServiceMock->expects($this->once())
            ->method('getPropertyById')
            ->with(1)
            ->willReturn(['data' => $property]);

        $this->formatResultMock->expects($this->once())
            ->method('view')
            ->with('properties/show', [
                'property' => $property,
                'message' => null
            ])
            ->willReturn('view_rendered');

        $result = $this->propertyController->show(1);

        $this->assertEquals('view_rendered', $result);
    }

    public function testShowFailure(): void
    {
        $this->propertyServiceMock->expects($this->once())
            ->method('getPropertyById')
            ->with(1)
            ->willThrowException(new Exception('Erro'));

        $this->formatResultMock->expects($this->once())
            ->method('error')
            ->with('Erro ao buscar propriedade: Erro', 500)
            ->willReturn('error_response');

        $result = $this->propertyController->show(1);

        $this->assertEquals('error_response', $result);
    }

    public function testEditSuccess(): void
    {
        $property = ['id' => 1, 'name' => 'Property 1'];

        $this->propertyServiceMock->expects($this->once())
            ->method('getPropertyById')
            ->with(1)
            ->willReturn(['data' => $property]);

        $this->formatResultMock->expects($this->once())
            ->method('view')
            ->with('properties/edit', ['property' => $property])
            ->willReturn('view_rendered');

        $result = $this->propertyController->edit(1);

        $this->assertEquals('view_rendered', $result);
    }

    public function testEditFailure(): void
    {
        $this->propertyServiceMock->expects($this->once())
            ->method('getPropertyById')
            ->with(1)
            ->willThrowException(new Exception('Erro'));

        $this->formatResultMock->expects($this->once())
            ->method('error')
            ->with('Erro ao carregar a edição: Erro', 500)
            ->willReturn('error_response');

        $result = $this->propertyController->edit(1);

        $this->assertEquals('error_response', $result);
    }

    public function testUpdateSuccess(): void
    {
        $request = $this->createMock(UpdatePropertyRequest::class);

        $data = ['name' => 'Updated Property'];

        $request->expects($this->once())
            ->method('validated')
            ->willReturn($data);

        $this->propertyServiceMock->expects($this->once())
            ->method('updateProperty')
            ->with(1, $data)
            ->willReturn(['message' => 'Atualizado com sucesso']);

        $property = ['id' => 1, 'name' => 'Updated Property'];

        $this->propertyServiceMock->expects($this->once())
            ->method('getPropertyById')
            ->with(1)
            ->willReturn(['data' => $property]);

        $this->formatResultMock->expects($this->once())
            ->method('view')
            ->with('properties/edit', [
                'property' => $property,
                'message' => 'Atualizado com sucesso'
            ])
            ->willReturn('view_rendered');

        $result = $this->propertyController->update(1, $request);

        $this->assertEquals('view_rendered', $result);
    }

    public function testUpdateFailure(): void
    {
        $request = $this->createMock(UpdatePropertyRequest::class);

        $request->expects($this->once())
            ->method('validated')
            ->willThrowException(new Exception('Erro'));

        $this->formatResultMock->expects($this->once())
            ->method('error')
            ->with('Erro ao atualizar propriedade: Erro', 500)
            ->willReturn('error_response');

        $result = $this->propertyController->update(1, $request);

        $this->assertEquals('error_response', $result);
    }

    public function testDestroySuccess(): void
    {
        $this->propertyServiceMock->expects($this->once())
            ->method('deleteProperty')
            ->with(1)
            ->willReturn(['message' => 'Deletado com sucesso']);

        $this->propertyServiceMock->expects($this->once())
            ->method('getAllProperties')
            ->willReturn(['data' => [['id' => 1, 'name' => 'Property 1']]]);

        $result = $this->propertyController->destroy(1);

        $expectedResponse = json_encode([
            'success' => true,
            'message' => 'Deletado com sucesso'
        ]);

        $this->assertEquals($expectedResponse, $result);
    }

    public function testDestroyFailure(): void
    {
        $this->propertyServiceMock->expects($this->once())
            ->method('deleteProperty')
            ->with(1)
            ->willThrowException(new Exception('Erro'));

        $result = $this->propertyController->destroy(1);

        $expectedResponse = json_encode([
            'success' => false,
            'message' => 'Erro ao deletar a propriedade: Erro'
        ]);

        $this->assertEquals($expectedResponse, $result);
    }

    public function testIndexSuccess(): void
    {
        $properties = [['id' => 1, 'name' => 'Property 1']];

        $this->propertyServiceMock->expects($this->once())
            ->method('getAllProperties')
            ->willReturn(['data' => $properties]);

        $this->formatResultMock->expects($this->once())
            ->method('view')
            ->with('properties/index', [
                'properties' => $properties,
                'message' => null
            ])
            ->willReturn('view_rendered');

        $result = $this->propertyController->index();

        $this->assertEquals('view_rendered', $result);
    }

    public function testIndexFailure(): void
    {
        $this->propertyServiceMock->expects($this->once())
            ->method('getAllProperties')
            ->willThrowException(new Exception('Erro'));

        $this->formatResultMock->expects($this->once())
            ->method('error')
            ->with('Erro ao buscar propriedades: Erro', 500)
            ->willReturn('error_response');

        $result = $this->propertyController->index();

        $this->assertEquals('error_response', $result);
    }
}
