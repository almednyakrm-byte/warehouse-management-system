<?php

namespace App\Tests\Controller;

use App\Controller\إدارة_وصول_المستودعاتController;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class Testإدارة_وصول_المستودعات extends TestCase
{
    private $controller;
    private $router;
    private $tokenStorage;
    private $pdo;

    protected function setUp(): void
    {
        $this->router = $this->createMock(RouterInterface::class);
        $this->tokenStorage = $this->createMock(TokenStorageInterface::class);
        $this->pdo = $this->createMock(\PDO::class);

        $this->controller = new إدارة_وصول_المستودعاتController($this->router, $this->tokenStorage, $this->pdo);
    }

    public function testGetAll(): void
    {
        $this->pdo->expects($this->once())
            ->method('query')
            ->with('SELECT * FROM إدارة_وصول_المستودعات')
            ->willReturn($this->createMock(\PDOStatement::class));

        $request = new Request();
        $request->attributes->set('page', 1);
        $request->attributes->set('limit', 10);

        $response = $this->controller->getAll($request);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testGetOne(): void
    {
        $id = 1;
        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('SELECT * FROM إدارة_وصول_المستودعات WHERE id = :id')
            ->willReturn($this->createMock(\PDOStatement::class));

        $this->pdo->expects($this->once())
            ->method('execute')
            ->with(['id' => $id])
            ->willReturn($this->createMock(\PDOStatement::class));

        $request = new Request();
        $request->attributes->set('id', $id);

        $response = $this->controller->getOne($request);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testCreate(): void
    {
        $data = ['name' => 'إدارة وصول المستودعات'];
        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('INSERT INTO إدارة_وصول_المستودعات (name) VALUES (:name)')
            ->willReturn($this->createMock(\PDOStatement::class));

        $request = new Request();
        $request->request->replace($data);

        $response = $this->controller->create($request);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
    }

    public function testUpdate(): void
    {
        $id = 1;
        $data = ['name' => 'إدارة وصول المستودعات'];
        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('UPDATE إدارة_وصول_المستودعات SET name = :name WHERE id = :id')
            ->willReturn($this->createMock(\PDOStatement::class));

        $request = new Request();
        $request->attributes->set('id', $id);
        $request->request->replace($data);

        $response = $this->controller->update($request);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testDelete(): void
    {
        $id = 1;
        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('DELETE FROM إدارة_وصول_المستودعات WHERE id = :id')
            ->willReturn($this->createMock(\PDOStatement::class));

        $request = new Request();
        $request->attributes->set('id', $id);

        $response = $this->controller->delete($request);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }
}