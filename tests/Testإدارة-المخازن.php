<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use App\Controller\StoreController;
use App\Repository\StoreRepository;
use PHPUnit\Framework\MockObject\MockObject;
use PDO;

class Testإدارةالمخازن extends TestCase
{
    private $controller;
    private $repository;
    private $pdo;

    protected function setUp(): void
    {
        $this->pdo = $this->createMock(PDO::class);
        $this->repository = $this->createMock(StoreRepository::class);
        $this->controller = new StoreController($this->repository);
    }

    public function testGetStores()
    {
        $expectedResponse = ['stores' => []];
        $this->repository->expects($this->once())
            ->method('getAllStores')
            ->willReturn($expectedResponse);
        $response = $this->controller->getStores();
        $this->assertEquals($expectedResponse, $response);
    }

    public function testCreateStore()
    {
        $storeId = 1;
        $storeName = 'Test Store';
        $expectedResponse = ['storeId' => $storeId, 'storeName' => $storeName];
        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('INSERT INTO stores (store_name) VALUES (:store_name)')
            ->willReturn($this->createMock(\PDOStatement::class));
        $this->pdo->expects($this->once())
            ->method('lastInsertId')
            ->willReturn($storeId);
        $response = $this->controller->createStore($storeName);
        $this->assertEquals($expectedResponse, $response);
    }

    public function testUpdateStore()
    {
        $storeId = 1;
        $storeName = 'Updated Test Store';
        $expectedResponse = ['storeId' => $storeId, 'storeName' => $storeName];
        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('UPDATE stores SET store_name = :store_name WHERE id = :id')
            ->willReturn($this->createMock(\PDOStatement::class));
        $response = $this->controller->updateStore($storeId, $storeName);
        $this->assertEquals($expectedResponse, $response);
    }

    public function testDeleteStore()
    {
        $storeId = 1;
        $expectedResponse = ['message' => 'Store deleted successfully'];
        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('DELETE FROM stores WHERE id = :id')
            ->willReturn($this->createMock(\PDOStatement::class));
        $response = $this->controller->deleteStore($storeId);
        $this->assertEquals($expectedResponse, $response);
    }
}


Note: This code assumes that the `StoreController` class has methods for each of the CRUD operations and that the `StoreRepository` class has methods for interacting with the database. The `PDO` class is used to simulate database interactions. The `MockObject` class is used to create mock objects for the `StoreRepository` and `PDO` classes.