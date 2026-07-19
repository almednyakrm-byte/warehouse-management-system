<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use App\Controller\إدارةالمبيعاتController;
use App\Repository\إدارةالمبيعاتRepository;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Testإدارةالمبيعات extends TestCase
{
    private $controller;
    private $repository;
    private $pdo;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(إدارةالمبيعاتRepository::class);
        $this->pdo = $this->createMock(\PDO::class);
        $this->controller = new إدارةالمبيعاتController($this->repository, $this->pdo);
    }

    public function testGetAll()
    {
        $expectedData = ['data' => ['item1', 'item2']];
        $this->repository->expects($this->once())
            ->method('findAll')
            ->willReturn($expectedData);
        $response = $this->controller->getAll();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals($expectedData, json_decode($response->getContent(), true));
    }

    public function testGetOne()
    {
        $expectedData = ['data' => 'item1'];
        $this->repository->expects($this->once())
            ->method('findOne')
            ->with(1)
            ->willReturn($expectedData);
        $response = $this->controller->getOne(1);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals($expectedData, json_decode($response->getContent(), true));
    }

    public function testPost()
    {
        $data = ['name' => 'item1'];
        $expectedData = ['data' => $data];
        $this->repository->expects($this->once())
            ->method('create')
            ->with($data)
            ->willReturn($expectedData);
        $request = new Request([], [], [], [], [], json_encode($data));
        $response = $this->controller->post($request);
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        $this->assertEquals($expectedData, json_decode($response->getContent(), true));
    }

    public function testPut()
    {
        $data = ['name' => 'item1'];
        $expectedData = ['data' => $data];
        $this->repository->expects($this->once())
            ->method('update')
            ->with(1, $data)
            ->willReturn($expectedData);
        $request = new Request([], [], [], [], [], json_encode($data));
        $response = $this->controller->put(1, $request);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals($expectedData, json_decode($response->getContent(), true));
    }

    public function testDelete()
    {
        $this->repository->expects($this->once())
            ->method('delete')
            ->with(1);
        $response = $this->controller->delete(1);
        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }

    public function testNotFound()
    {
        $this->expectException(NotFoundHttpException::class);
        $this->controller->getOne(999);
    }
}


This test file covers the following scenarios:

1. `testGetAll`: Verifies that the `getAll` method returns a list of items.
2. `testGetOne`: Verifies that the `getOne` method returns a single item.
3. `testPost`: Verifies that the `post` method creates a new item.
4. `testPut`: Verifies that the `put` method updates an existing item.
5. `testDelete`: Verifies that the `delete` method deletes an item.
6. `testNotFound`: Verifies that a `NotFoundHttpException` is thrown when trying to retrieve a non-existent item.

Note that this test file assumes that the `إدارةالمبيعاتController` class has the following methods:

* `getAll`: Returns a list of items.
* `getOne`: Returns a single item.
* `post`: Creates a new item.
* `put`: Updates an existing item.
* `delete`: Deletes an item.

Also, this test file assumes that the `إدارةالمبيعاتRepository` class has the following methods:

* `findAll`: Returns a list of items.
* `findOne`: Returns a single item.
* `create`: Creates a new item.
* `update`: Updates an existing item.
* `delete`: Deletes an item.