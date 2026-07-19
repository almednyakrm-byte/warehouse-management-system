<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use App\Controller\إدارة تسجيلController;
use App\Repository\إدارة تسجيلRepository;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class Testإدارة-تسجيل extends TestCase
{
    private $controller;
    private $repository;
    private $mockPDO;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(إدارة تسجيلRepository::class);
        $this->mockPDO = $this->createMock(\PDO::class);
        $this->controller = new إدارة تسجيلController($this->repository, $this->mockPDO);
    }

    public function testGetAll()
    {
        $expectedResponse = new Response(json_encode(['data' => []]));
        $this->repository->expects($this->once())
            ->method('findAll')
            ->willReturn([]);
        $response = $this->controller->getAll();
        $this->assertEquals($expectedResponse, $response);
    }

    public function testGetOne()
    {
        $id = 1;
        $expectedResponse = new Response(json_encode(['data' => ['id' => $id]]));
        $this->repository->expects($this->once())
            ->method('find')
            ->with($id)
            ->willReturn(['id' => $id]);
        $response = $this->controller->getOne($id);
        $this->assertEquals($expectedResponse, $response);
    }

    public function testCreate()
    {
        $data = ['name' => 'Test'];
        $expectedResponse = new Response(json_encode(['data' => $data]));
        $this->repository->expects($this->once())
            ->method('create')
            ->with($data)
            ->willReturn($data);
        $response = $this->controller->create($data);
        $this->assertEquals($expectedResponse, $response);
    }

    public function testUpdate()
    {
        $id = 1;
        $data = ['name' => 'Test'];
        $expectedResponse = new Response(json_encode(['data' => $data]));
        $this->repository->expects($this->once())
            ->method('update')
            ->with($id, $data)
            ->willReturn($data);
        $response = $this->controller->update($id, $data);
        $this->assertEquals($expectedResponse, $response);
    }

    public function testDelete()
    {
        $id = 1;
        $this->repository->expects($this->once())
            ->method('delete')
            ->with($id);
        $response = $this->controller->delete($id);
        $this->assertEquals(new Response('', Response::HTTP_NO_CONTENT), $response);
    }
}



// App\Controller\إدارة تسجيلController.php
namespace App\Controller;

use App\Repository\إدارة تسجيلRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class إدارة تسجيلController
{
    private $repository;
    private $pdo;

    public function __construct(إدارة تسجيلRepository $repository, \PDO $pdo)
    {
        $this->repository = $repository;
        $this->pdo = $pdo;
    }

    public function getAll()
    {
        return new Response(json_encode($this->repository->findAll()));
    }

    public function getOne($id)
    {
        return new Response(json_encode($this->repository->find($id)));
    }

    public function create($data)
    {
        return new Response(json_encode($this->repository->create($data)));
    }

    public function update($id, $data)
    {
        return new Response(json_encode($this->repository->update($id, $data)));
    }

    public function delete($id)
    {
        $this->repository->delete($id);
        return new Response('', Response::HTTP_NO_CONTENT);
    }
}



// App\Repository\إدارة تسجيلRepository.php
namespace App\Repository;

class إدارة تسجيلRepository
{
    public function findAll()
    {
        // Implement logic to fetch all data from database
        return [];
    }

    public function find($id)
    {
        // Implement logic to fetch data by id from database
        return ['id' => $id];
    }

    public function create($data)
    {
        // Implement logic to create new data in database
        return $data;
    }

    public function update($id, $data)
    {
        // Implement logic to update data in database
        return $data;
    }

    public function delete($id)
    {
        // Implement logic to delete data from database
    }
}