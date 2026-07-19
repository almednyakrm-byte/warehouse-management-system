<?php

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use App\Controllers\WarehousesController;
use App\Models\Warehouse;
use PDO;
use PDOStatement;

class TestWarehouses extends TestCase
{
    private $warehouseController;
    private $warehouseModel;
    private $pdo;

    protected function setUp(): void
    {
        $this->pdo = $this->createMock(PDO::class);
        $this->warehouseModel = new Warehouse($this->pdo);
        $this->warehouseController = new WarehousesController($this->warehouseModel);
    }

    public function testGetAllWarehouses()
    {
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->expects($this->once())
            ->method('execute')
            ->with([]);
        $stmt->expects($this->once())
            ->method('fetchAll')
            ->willReturn([
                ['id' => 1, 'name' => 'Warehouse 1'],
                ['id' => 2, 'name' => 'Warehouse 2'],
            ]);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('SELECT * FROM warehouses')
            ->willReturn($stmt);

        $request = $this->createMock(ServerRequestInterface::class);
        $response = $this->createMock(ResponseInterface::class);

        $result = $this->warehouseController->getAllWarehouses($request, $response);
        $this->assertEquals(200, $result->getStatusCode());
    }

    public function testGetWarehouseById()
    {
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->expects($this->once())
            ->method('execute')
            ->with([1]);
        $stmt->expects($this->once())
            ->method('fetch')
            ->willReturn(['id' => 1, 'name' => 'Warehouse 1']);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('SELECT * FROM warehouses WHERE id = ?')
            ->willReturn($stmt);

        $request = $this->createMock(ServerRequestInterface::class);
        $request->expects($this->once())
            ->method('getAttribute')
            ->with('id')
            ->willReturn(1);
        $response = $this->createMock(ResponseInterface::class);

        $result = $this->warehouseController->getWarehouseById($request, $response);
        $this->assertEquals(200, $result->getStatusCode());
    }

    public function testCreateWarehouse()
    {
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->expects($this->once())
            ->method('execute')
            ->with(['Warehouse 1']);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('INSERT INTO warehouses (name) VALUES (?)')
            ->willReturn($stmt);

        $request = $this->createMock(ServerRequestInterface::class);
        $request->expects($this->once())
            ->method('getParsedBody')
            ->willReturn(['name' => 'Warehouse 1']);
        $response = $this->createMock(ResponseInterface::class);

        $result = $this->warehouseController->createWarehouse($request, $response);
        $this->assertEquals(201, $result->getStatusCode());
    }

    public function testUpdateWarehouse()
    {
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->expects($this->once())
            ->method('execute')
            ->with(['Warehouse 1', 1]);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('UPDATE warehouses SET name = ? WHERE id = ?')
            ->willReturn($stmt);

        $request = $this->createMock(ServerRequestInterface::class);
        $request->expects($this->once())
            ->method('getAttribute')
            ->with('id')
            ->willReturn(1);
        $request->expects($this->once())
            ->method('getParsedBody')
            ->willReturn(['name' => 'Warehouse 1']);
        $response = $this->createMock(ResponseInterface::class);

        $result = $this->warehouseController->updateWarehouse($request, $response);
        $this->assertEquals(200, $result->getStatusCode());
    }

    public function testDeleteWarehouse()
    {
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->expects($this->once())
            ->method('execute')
            ->with([1]);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('DELETE FROM warehouses WHERE id = ?')
            ->willReturn($stmt);

        $request = $this->createMock(ServerRequestInterface::class);
        $request->expects($this->once())
            ->method('getAttribute')
            ->with('id')
            ->willReturn(1);
        $response = $this->createMock(ResponseInterface::class);

        $result = $this->warehouseController->deleteWarehouse($request, $response);
        $this->assertEquals(204, $result->getStatusCode());
    }
}