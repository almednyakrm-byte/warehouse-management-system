<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use App\Controller\ProductsController;
use App\Repository\ProductsRepository;
use PHPUnit\Framework\MockObject\MockObject;
use PDO;

class Testالمنتجات extends TestCase
{
    private $controller;
    private $repository;
    private $pdo;

    protected function setUp(): void
    {
        $this->pdo = $this->createMock(PDO::class);
        $this->repository = $this->createMock(ProductsRepository::class);
        $this->controller = new ProductsController($this->repository);
    }

    public function testGetProducts()
    {
        $expectedResponse = ['products' => []];
        $this->pdo->expects($this->once())
            ->method('query')
            ->with('SELECT * FROM products')
            ->willReturn($this->createMock(\PDOStatement::class));
        $this->repository->expects($this->once())
            ->method('getAll')
            ->willReturn($expectedResponse);
        $response = $this->controller->getProducts();
        $this->assertEquals($expectedResponse, $response);
    }

    public function testCreateProduct()
    {
        $productData = ['name' => 'Product 1', 'price' => 10.99];
        $expectedResponse = ['message' => 'Product created successfully'];
        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('INSERT INTO products (name, price) VALUES (:name, :price)')
            ->willReturn($this->createMock(\PDOStatement::class));
        $this->repository->expects($this->once())
            ->method('create')
            ->with($productData)
            ->willReturn($expectedResponse);
        $response = $this->controller->createProduct($productData);
        $this->assertEquals($expectedResponse, $response);
    }

    public function testUpdateProduct()
    {
        $productId = 1;
        $productData = ['name' => 'Product 1', 'price' => 10.99];
        $expectedResponse = ['message' => 'Product updated successfully'];
        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('UPDATE products SET name = :name, price = :price WHERE id = :id')
            ->willReturn($this->createMock(\PDOStatement::class));
        $this->repository->expects($this->once())
            ->method('update')
            ->with($productId, $productData)
            ->willReturn($expectedResponse);
        $response = $this->controller->updateProduct($productId, $productData);
        $this->assertEquals($expectedResponse, $response);
    }

    public function testDeleteProduct()
    {
        $productId = 1;
        $expectedResponse = ['message' => 'Product deleted successfully'];
        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('DELETE FROM products WHERE id = :id')
            ->willReturn($this->createMock(\PDOStatement::class));
        $this->repository->expects($this->once())
            ->method('delete')
            ->with($productId)
            ->willReturn($expectedResponse);
        $response = $this->controller->deleteProduct($productId);
        $this->assertEquals($expectedResponse, $response);
    }
}


This test file uses PHPUnit to test the CRUD API operations on the 'المنتجات' module. It creates a mock PDO object and a mock ProductsRepository object to simulate the database interactions. The test methods cover the GET, POST, PUT, and DELETE requests.