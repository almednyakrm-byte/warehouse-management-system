<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use App\Controller\StockController;
use App\Repository\StockRepository;
use App\Service\StockService;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;

class Testالمخزون extends TestCase
{
    private $controller;
    private $repository;
    private $service;
    private $router;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(StockRepository::class);
        $this->service = $this->createMock(StockService::class);
        $this->router = $this->createMock(RouterInterface::class);
        $this->controller = new StockController($this->repository, $this->service, $this->router);
    }

    public function testGetAllStocks()
    {
        $this->repository->expects($this->once())
            ->method('findAll')
            ->willReturn([
                ['id' => 1, 'name' => 'Stock 1'],
                ['id' => 2, 'name' => 'Stock 2'],
            ]);

        $request = new Request();
        $response = $this->controller->getAllStocks($request);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
    }

    public function testCreateStock()
    {
        $this->service->expects($this->once())
            ->method('createStock')
            ->with(['name' => 'New Stock'])
            ->willReturn(['id' => 3, 'name' => 'New Stock']);

        $request = new Request([], [], ['name' => 'New Stock']);
        $response = $this->controller->createStock($request);

        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
    }

    public function testUpdateStock()
    {
        $this->repository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(['id' => 1, 'name' => 'Stock 1']);

        $this->service->expects($this->once())
            ->method('updateStock')
            ->with(1, ['name' => 'Updated Stock'])
            ->willReturn(['id' => 1, 'name' => 'Updated Stock']);

        $request = new Request([], [], ['name' => 'Updated Stock']);
        $response = $this->controller->updateStock(1, $request);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
    }

    public function testDeleteStock()
    {
        $this->repository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(['id' => 1, 'name' => 'Stock 1']);

        $this->repository->expects($this->once())
            ->method('remove')
            ->with(['id' => 1, 'name' => 'Stock 1']);

        $response = $this->controller->deleteStock(1);

        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }
}


This test file covers the following scenarios:

1.  `testGetAllStocks`: Tests the `getAllStocks` method by mocking the `findAll` method of the `StockRepository` to return a list of stocks.
2.  `testCreateStock`: Tests the `createStock` method by mocking the `createStock` method of the `StockService` to return a new stock.
3.  `testUpdateStock`: Tests the `updateStock` method by mocking the `find` method of the `StockRepository` to return a stock and the `updateStock` method of the `StockService` to update the stock.
4.  `testDeleteStock`: Tests the `deleteStock` method by mocking the `find` method of the `StockRepository` to return a stock and the `remove` method of the `StockRepository` to delete the stock.

Each test case verifies the expected HTTP status code and response content type.