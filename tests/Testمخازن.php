<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use App\Controller\StockController;
use App\Repository\StockRepository;
use PHPUnit\Framework\MockObject\MockObject;
use PDO;

class Testمخازن extends TestCase
{
    private $controller;
    private $repository;
    private $pdo;

    protected function setUp(): void
    {
        $this->pdo = $this->createMock(PDO::class);
        $this->repository = $this->createMock(StockRepository::class);
        $this->controller = new StockController($this->repository);
    }

    public function testGetAll()
    {
        $expectedData = [
            ['id' => 1, 'name' => 'Stock 1'],
            ['id' => 2, 'name' => 'Stock 2'],
        ];

        $this->repository->expects($this->once())
            ->method('getAll')
            ->willReturn($expectedData);

        $response = $this->controller->getAll();
        $this->assertEquals($expectedData, $response);
    }

    public function testGetById()
    {
        $id = 1;
        $expectedData = ['id' => 1, 'name' => 'Stock 1'];

        $this->repository->expects($this->once())
            ->method('getById')
            ->with($id)
            ->willReturn($expectedData);

        $response = $this->controller->getById($id);
        $this->assertEquals($expectedData, $response);
    }

    public function testCreate()
    {
        $data = ['name' => 'Stock 3'];
        $expectedId = 3;

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('INSERT INTO stocks (name) VALUES (:name)')
            ->willReturn($this->createMock(\PDOStatement::class));

        $this->pdo->expects($this->once())
            ->method('lastInsertId')
            ->willReturn($expectedId);

        $response = $this->controller->create($data);
        $this->assertEquals($expectedId, $response);
    }

    public function testUpdate()
    {
        $id = 1;
        $data = ['name' => 'Stock 1 Updated'];

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('UPDATE stocks SET name = :name WHERE id = :id')
            ->willReturn($this->createMock(\PDOStatement::class));

        $response = $this->controller->update($id, $data);
        $this->assertTrue($response);
    }

    public function testDelete()
    {
        $id = 1;

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('DELETE FROM stocks WHERE id = :id')
            ->willReturn($this->createMock(\PDOStatement::class));

        $response = $this->controller->delete($id);
        $this->assertTrue($response);
    }
}


Note: This test file assumes that the `StockController` class has methods for CRUD operations and that the `StockRepository` class has methods for interacting with the database. The `PDO` class is used to simulate database interactions. The `createMock` method is used to create mock objects for the `PDO` and `StockRepository` classes.