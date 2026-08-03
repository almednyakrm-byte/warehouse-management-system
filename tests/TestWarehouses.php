<?php

declare(strict_types=1);

namespace App\Tests;

use App\Controllers\WarehousesController;
use App\Models\Warehouse;
use App\Repositories\WarehouseRepository;
use App\Tests\TestCase;
use PDO;
use PDOStatement;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Request;

class TestWarehouses extends TestCase
{
    private WarehouseRepository $repository;
    private PDO $pdo;

    protected function setUp(): void
    {
        $this->pdo = $this->createMock(PDO::class);
        $this->repository = new WarehouseRepository($this->pdo);
    }

    public function testGetWarehouses(): void
    {
        $statement = $this->createMock(PDOStatement::class);
        $statement->expects($this->once())
            ->method('execute')
            ->with([]);

        $statement->expects($this->once())
            ->method('fetchAll')
            ->willReturn([
                ['id' => 1, 'name' => 'Warehouse 1'],
                ['id' => 2, 'name' => 'Warehouse 2'],
            ]);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('SELECT * FROM warehouses')
            ->willReturn($statement);

        $controller = new WarehousesController($this->repository);
        $request = new Request();
        $response = $controller->index($request);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals([
            ['id' => 1, 'name' => 'Warehouse 1'],
            ['id' => 2, 'name' => 'Warehouse 2'],
        ], $response->getContent());
    }

    public function testCreateWarehouse(): void
    {
        $statement = $this->createMock(PDOStatement::class);
        $statement->expects($this->once())
            ->method('execute')
            ->with(['name' => 'New Warehouse']);

        $statement->expects($this->once())
            ->method('rowCount')
            ->willReturn(1);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('INSERT INTO warehouses (name) VALUES (:name)')
            ->willReturn($statement);

        $controller = new WarehousesController($this->repository);
        $request = new Request(['name' => 'New Warehouse'], 'POST');
        $response = $controller->store($request);

        $this->assertEquals(201, $response->getStatusCode());
        $this->assertEquals(['message' => 'Warehouse created successfully'], $response->getContent());
    }

    public function testUpdateWarehouse(): void
    {
        $statement = $this->createMock(PDOStatement::class);
        $statement->expects($this->once())
            ->method('execute')
            ->with(['id' => 1, 'name' => 'Updated Warehouse']);

        $statement->expects($this->once())
            ->method('rowCount')
            ->willReturn(1);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('UPDATE warehouses SET name = :name WHERE id = :id')
            ->willReturn($statement);

        $controller = new WarehousesController($this->repository);
        $request = new Request(['name' => 'Updated Warehouse'], 'PUT');
        $response = $controller->update(1, $request);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(['message' => 'Warehouse updated successfully'], $response->getContent());
    }

    public function testDeleteWarehouse(): void
    {
        $statement = $this->createMock(PDOStatement::class);
        $statement->expects($this->once())
            ->method('execute')
            ->with(['id' => 1]);

        $statement->expects($this->once())
            ->method('rowCount')
            ->willReturn(1);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('DELETE FROM warehouses WHERE id = :id')
            ->willReturn($statement);

        $controller = new WarehousesController($this->repository);
        $response = $controller->destroy(1);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(['message' => 'Warehouse deleted successfully'], $response->getContent());
    }
}