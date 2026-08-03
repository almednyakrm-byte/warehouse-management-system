<?php

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use PDO;

class TestSuppliers extends TestCase
{
    private $supplierController;
    private $mockPdo;

    protected function setUp(): void
    {
        $this->mockPdo = $this->createMock(PDO::class);
        $this->supplierController = new SupplierController($this->mockPdo);
    }

    public function testGetSuppliers()
    {
        $mockStatement = $this->createMock(PDOStatement::class);
        $mockStatement->expects($this->once())
            ->method('execute')
            ->with($this->equalTo([':id' => null]));

        $mockStatement->expects($this->once())
            ->method('fetchAll')
            ->willReturn([
                ['id' => 1, 'name' => 'Supplier 1'],
                ['id' => 2, 'name' => 'Supplier 2'],
            ]);

        $this->mockPdo->expects($this->once())
            ->method('prepare')
            ->with($this->equalTo('SELECT * FROM suppliers'))
            ->willReturn($mockStatement);

        $request = $this->createMock(ServerRequestInterface::class);
        $response = $this->createMock(ResponseInterface::class);

        $result = $this->supplierController->getSuppliers($request, $response);
        $this->assertIsArray($result);
        $this->assertCount(2, $result);
    }

    public function testGetSupplierById()
    {
        $mockStatement = $this->createMock(PDOStatement::class);
        $mockStatement->expects($this->once())
            ->method('execute')
            ->with($this->equalTo([':id' => 1]));

        $mockStatement->expects($this->once())
            ->method('fetch')
            ->willReturn(['id' => 1, 'name' => 'Supplier 1']);

        $this->mockPdo->expects($this->once())
            ->method('prepare')
            ->with($this->equalTo('SELECT * FROM suppliers WHERE id = :id'))
            ->willReturn($mockStatement);

        $request = $this->createMock(ServerRequestInterface::class);
        $request->expects($this->once())
            ->method('getAttribute')
            ->with($this->equalTo('id'))
            ->willReturn(1);

        $response = $this->createMock(ResponseInterface::class);

        $result = $this->supplierController->getSupplierById($request, $response);
        $this->assertIsArray($result);
        $this->assertEquals(1, $result['id']);
    }

    public function testCreateSupplier()
    {
        $mockStatement = $this->createMock(PDOStatement::class);
        $mockStatement->expects($this->once())
            ->method('execute')
            ->with($this->equalTo([':name' => 'New Supplier']));

        $this->mockPdo->expects($this->once())
            ->method('prepare')
            ->with($this->equalTo('INSERT INTO suppliers (name) VALUES (:name)'))
            ->willReturn($mockStatement);

        $request = $this->createMock(ServerRequestInterface::class);
        $request->expects($this->once())
            ->method('getParsedBody')
            ->willReturn(['name' => 'New Supplier']);

        $response = $this->createMock(ResponseInterface::class);

        $result = $this->supplierController->createSupplier($request, $response);
        $this->assertIsArray($result);
        $this->assertEquals(201, $result['status']);
    }

    public function testUpdateSupplier()
    {
        $mockStatement = $this->createMock(PDOStatement::class);
        $mockStatement->expects($this->once())
            ->method('execute')
            ->with($this->equalTo([':id' => 1, ':name' => 'Updated Supplier']));

        $this->mockPdo->expects($this->once())
            ->method('prepare')
            ->with($this->equalTo('UPDATE suppliers SET name = :name WHERE id = :id'))
            ->willReturn($mockStatement);

        $request = $this->createMock(ServerRequestInterface::class);
        $request->expects($this->once())
            ->method('getAttribute')
            ->with($this->equalTo('id'))
            ->willReturn(1);

        $request->expects($this->once())
            ->method('getParsedBody')
            ->willReturn(['name' => 'Updated Supplier']);

        $response = $this->createMock(ResponseInterface::class);

        $result = $this->supplierController->updateSupplier($request, $response);
        $this->assertIsArray($result);
        $this->assertEquals(200, $result['status']);
    }

    public function testDeleteSupplier()
    {
        $mockStatement = $this->createMock(PDOStatement::class);
        $mockStatement->expects($this->once())
            ->method('execute')
            ->with($this->equalTo([':id' => 1]));

        $this->mockPdo->expects($this->once())
            ->method('prepare')
            ->with($this->equalTo('DELETE FROM suppliers WHERE id = :id'))
            ->willReturn($mockStatement);

        $request = $this->createMock(ServerRequestInterface::class);
        $request->expects($this->once())
            ->method('getAttribute')
            ->with($this->equalTo('id'))
            ->willReturn(1);

        $response = $this->createMock(ResponseInterface::class);

        $result = $this->supplierController->deleteSupplier($request, $response);
        $this->assertIsArray($result);
        $this->assertEquals(204, $result['status']);
    }
}