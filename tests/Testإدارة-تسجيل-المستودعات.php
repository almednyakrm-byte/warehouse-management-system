<?php

namespace App\Tests\Controller;

use App\Controller\إدارة_تسجيل_المستودعاتController;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class Testإدارة_تسجيل_المستودعات extends TestCase
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

        $this->controller = new إدارة_تسجيل_المستودعاتController($this->router, $this->tokenStorage, $this->pdo);
    }

    public function testGetAll()
    {
        $this->pdo->expects($this->once())
            ->method('query')
            ->with('SELECT * FROM المستودعات')
            ->willReturn($this->createMock(\PDOStatement::class));

        $request = new Request();
        $request->attributes->set('page', 1);
        $request->attributes->set('limit', 10);

        $response = $this->controller->getAll($request);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testGetOne()
    {
        $this->pdo->expects($this->once())
            ->method('query')
            ->with('SELECT * FROM المستودعات WHERE id = :id')
            ->willReturn($this->createMock(\PDOStatement::class));

        $request = new Request();
        $request->attributes->set('id', 1);

        $response = $this->controller->getOne($request);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testCreate()
    {
        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('INSERT INTO المستودعات (name, address) VALUES (:name, :address)')
            ->willReturn($this->createMock(\PDOStatement::class));

        $request = new Request();
        $request->request->set('name', 'مستودع جديد');
        $request->request->set('address', 'عنوان المستودع الجديد');

        $response = $this->controller->create($request);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
    }

    public function testUpdate()
    {
        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('UPDATE المستودعات SET name = :name, address = :address WHERE id = :id')
            ->willReturn($this->createMock(\PDOStatement::class));

        $request = new Request();
        $request->request->set('id', 1);
        $request->request->set('name', 'مستودع مُحديث');
        $request->request->set('address', 'عنوان المستودع المُحدث');

        $response = $this->controller->update($request);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testDelete()
    {
        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('DELETE FROM المستودعات WHERE id = :id')
            ->willReturn($this->createMock(\PDOStatement::class));

        $request = new Request();
        $request->attributes->set('id', 1);

        $response = $this->controller->delete($request);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }
}