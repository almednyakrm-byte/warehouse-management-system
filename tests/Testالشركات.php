<?php

namespace App\Tests\Controller;

use App\Controller\شركاتController;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Connection;

class Testالشركات extends TestCase
{
    private $controller;
    private $pdo;

    public function setUp(): void
    {
        $this->pdo = $this->createMock(Connection::class);
        $this->controller = new شركاتController($this->pdo);
    }

    public function testGetAll()
    {
        $this->pdo->expects($this->once())
            ->method('query')
            ->with('SELECT * FROM الشركات')
            ->willReturn($this->createMock(\Doctrine\DBAL\Statement::class));

        $response = $this->controller->getAll();
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testGetById()
    {
        $id = 1;
        $this->pdo->expects($this->once())
            ->method('query')
            ->with('SELECT * FROM الشركات WHERE id = ?', [$id])
            ->willReturn($this->createMock(\Doctrine\DBAL\Statement::class));

        $response = $this->controller->getById($id);
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testCreate()
    {
        $data = ['name' => 'Test Company'];
        $this->pdo->expects($this->once())
            ->method('insert')
            ->with('شركات', $data)
            ->willReturn(true);

        $response = $this->controller->create($data);
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
    }

    public function testUpdate()
    {
        $id = 1;
        $data = ['name' => 'Updated Company'];
        $this->pdo->expects($this->once())
            ->method('update')
            ->with('شركات', $data, ['id' => $id])
            ->willReturn(true);

        $response = $this->controller->update($id, $data);
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testDelete()
    {
        $id = 1;
        $this->pdo->expects($this->once())
            ->method('delete')
            ->with('شركات', ['id' => $id])
            ->willReturn(true);

        $response = $this->controller->delete($id);
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }
}


Note: This code assumes that the `شركاتController` class has methods for each CRUD operation, and that the `PDO` object is injected into the controller. The `createMock` method is used to create mock objects for the `PDO` and `Statement` classes. The `expects` method is used to specify the expected behavior of the mock objects. The `willReturn` method is used to specify the return value of the mock objects.