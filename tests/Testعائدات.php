<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use App\Controller\عائداتController;
use App\Repository\عائداتRepository;
use App\Entity\عائدات;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class Testعائدات extends TestCase
{
    private $controller;
    private $repository;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(عائداتRepository::class);
        $this->controller = new عائداتController($this->repository);
    }

    public function testGetAll()
    {
        $expectedResponse = ['data' => []];
        $this->repository->expects($this->once())
            ->method('findAll')
            ->willReturn($expectedResponse['data']);

        $response = $this->controller->getAll();

        $this->assertEquals($expectedResponse, $response->getContent());
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testGetOne()
    {
        $expectedResponse = ['data' => []];
        $id = 1;
        $this->repository->expects($this->once())
            ->method('find')
            ->with($id)
            ->willReturn($expectedResponse['data']);

        $response = $this->controller->getOne($id);

        $this->assertEquals($expectedResponse, $response->getContent());
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testPost()
    {
        $expectedResponse = ['data' => []];
        $data = ['name' => 'Test'];
        $this->repository->expects($this->once())
            ->method('save')
            ->with($data)
            ->willReturn($expectedResponse['data']);

        $request = new Request();
        $request->request->set('name', $data['name']);
        $response = $this->controller->post($request);

        $this->assertEquals($expectedResponse, $response->getContent());
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
    }

    public function testPut()
    {
        $expectedResponse = ['data' => []];
        $id = 1;
        $data = ['name' => 'Test'];
        $this->repository->expects($this->once())
            ->method('update')
            ->with($id, $data)
            ->willReturn($expectedResponse['data']);

        $request = new Request();
        $request->request->set('name', $data['name']);
        $response = $this->controller->put($id, $request);

        $this->assertEquals($expectedResponse, $response->getContent());
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testDelete()
    {
        $expectedResponse = ['data' => []];
        $id = 1;
        $this->repository->expects($this->once())
            ->method('delete')
            ->with($id)
            ->willReturn($expectedResponse['data']);

        $response = $this->controller->delete($id);

        $this->assertEquals($expectedResponse, $response->getContent());
        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }
}



// عائداتController.php
namespace App\Controller;

use App\Repository\عائداتRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class عائداتController
{
    private $repository;

    public function __construct(عائداتRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getAll()
    {
        $data = $this->repository->findAll();
        return new Response(json_encode(['data' => $data]));
    }

    public function getOne($id)
    {
        $data = $this->repository->find($id);
        return new Response(json_encode(['data' => $data]));
    }

    public function post(Request $request)
    {
        $data = $request->request->all();
        $data = $this->repository->save($data);
        return new Response(json_encode(['data' => $data]), Response::HTTP_CREATED);
    }

    public function put($id, Request $request)
    {
        $data = $request->request->all();
        $data = $this->repository->update($id, $data);
        return new Response(json_encode(['data' => $data]));
    }

    public function delete($id)
    {
        $this->repository->delete($id);
        return new Response('', Response::HTTP_NO_CONTENT);
    }
}