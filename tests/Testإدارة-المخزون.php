<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use App\Controller\StockController;
use App\Repository\StockRepository;
use App\Entity\Stock;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\RouterInterface;

class Testإدارةالمخزون extends TestCase
{
    private $controller;
    private $repository;
    private $router;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(StockRepository::class);
        $this->router = $this->createMock(RouterInterface::class);
        $this->controller = new StockController($this->repository, $this->router);
    }

    public function testGetAllStocks()
    {
        $expectedResponse = new JsonResponse(['stocks' => []]);
        $this->repository->expects($this->once())
            ->method('findAll')
            ->willReturn([]);
        $response = $this->controller->getAllStocks();
        $this->assertEquals($expectedResponse, $response);
    }

    public function testGetStockById()
    {
        $stock = new Stock();
        $stock->setId(1);
        $stock->setName('Stock 1');
        $this->repository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn($stock);
        $response = $this->controller->getStockById(1);
        $this->assertEquals(new JsonResponse(['stock' => $stock]), $response);
    }

    public function testCreateStock()
    {
        $stock = new Stock();
        $stock->setName('Stock 1');
        $this->repository->expects($this->once())
            ->method('save')
            ->with($stock);
        $request = new Request();
        $request->request->set('name', 'Stock 1');
        $response = $this->controller->createStock($request);
        $this->assertEquals(new JsonResponse(['message' => 'Stock created successfully']), $response);
    }

    public function testUpdateStock()
    {
        $stock = new Stock();
        $stock->setId(1);
        $stock->setName('Stock 1');
        $this->repository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn($stock);
        $this->repository->expects($this->once())
            ->method('save')
            ->with($stock);
        $request = new Request();
        $request->request->set('name', 'Stock 1 Updated');
        $response = $this->controller->updateStock(1, $request);
        $this->assertEquals(new JsonResponse(['message' => 'Stock updated successfully']), $response);
    }

    public function testDeleteStock()
    {
        $stock = new Stock();
        $stock->setId(1);
        $this->repository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn($stock);
        $this->repository->expects($this->once())
            ->method('remove')
            ->with($stock);
        $response = $this->controller->deleteStock(1);
        $this->assertEquals(new JsonResponse(['message' => 'Stock deleted successfully']), $response);
    }
}


This test file covers the CRUD operations for the 'إدارة المخزون' module. It uses mocked PDO statements to simulate database interactions. The tests cover the following scenarios:

- `testGetAllStocks`: Tests the `getAllStocks` method, which retrieves all stocks from the database.
- `testGetStockById`: Tests the `getStockById` method, which retrieves a stock by its ID.
- `testCreateStock`: Tests the `createStock` method, which creates a new stock in the database.
- `testUpdateStock`: Tests the `updateStock` method, which updates an existing stock in the database.
- `testDeleteStock`: Tests the `deleteStock` method, which deletes a stock from the database.

Each test method uses the `createMock` method to create a mock object for the `StockRepository` class, which is used to simulate database interactions. The `expects` method is used to specify the expected behavior of the mock object, and the `willReturn` method is used to specify the expected return value.