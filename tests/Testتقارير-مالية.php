<?php

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class Testتقارير_مالية extends TestCase
{
    private $mockPDO;
    private $request;
    private $response;
    private $stream;

    protected function setUp(): void
    {
        $this->mockPDO = $this->createMock(\PDO::class);
        $this->request = $this->createMock(ServerRequestInterface::class);
        $this->response = $this->createMock(ResponseInterface::class);
        $this->stream = $this->createMock(StreamInterface::class);
    }

    public function testGetAllتقارير_مالية()
    {
        $this->mockPDO->expects($this->once())
            ->method('query')
            ->with('SELECT * FROM تقارير_مالية')
            ->willReturn($this->createMock(\PDOStatement::class));

        $controller = new تقارير_ماليةController($this->mockPDO);
        $response = $controller->getAll($this->request, $this->response);

        $this->assertInstanceOf(ResponseInterface::class, $response);
    }

    public function testGetتقارير_ماليةById()
    {
        $id = 1;
        $this->mockPDO->expects($this->once())
            ->method('prepare')
            ->with('SELECT * FROM تقارير_مالية WHERE id = :id')
            ->willReturn($this->createMock(\PDOStatement::class));

        $this->request->expects($this->once())
            ->method('getAttribute')
            ->with('id')
            ->willReturn($id);

        $controller = new تقارير_ماليةController($this->mockPDO);
        $response = $controller->getById($this->request, $this->response);

        $this->assertInstanceOf(ResponseInterface::class, $response);
    }

    public function testCreateتقارير_مالية()
    {
        $data = ['name' => 'test', 'description' => 'test'];
        $this->mockPDO->expects($this->once())
            ->method('prepare')
            ->with('INSERT INTO تقارير_مالية (name, description) VALUES (:name, :description)')
            ->willReturn($this->createMock(\PDOStatement::class));

        $this->request->expects($this->once())
            ->method('getParsedBody')
            ->willReturn($data);

        $controller = new تقارير_ماليةController($this->mockPDO);
        $response = $controller->create($this->request, $this->response);

        $this->assertInstanceOf(ResponseInterface::class, $response);
    }

    public function testUpdateتقارير_مالية()
    {
        $id = 1;
        $data = ['name' => 'test', 'description' => 'test'];
        $this->mockPDO->expects($this->once())
            ->method('prepare')
            ->with('UPDATE تقارير_مالية SET name = :name, description = :description WHERE id = :id')
            ->willReturn($this->createMock(\PDOStatement::class));

        $this->request->expects($this->once())
            ->method('getAttribute')
            ->with('id')
            ->willReturn($id);

        $this->request->expects($this->once())
            ->method('getParsedBody')
            ->willReturn($data);

        $controller = new تقارير_ماليةController($this->mockPDO);
        $response = $controller->update($this->request, $this->response);

        $this->assertInstanceOf(ResponseInterface::class, $response);
    }

    public function testDeleteتقارير_مالية()
    {
        $id = 1;
        $this->mockPDO->expects($this->once())
            ->method('prepare')
            ->with('DELETE FROM تقارير_مالية WHERE id = :id')
            ->willReturn($this->createMock(\PDOStatement::class));

        $this->request->expects($this->once())
            ->method('getAttribute')
            ->with('id')
            ->willReturn($id);

        $controller = new تقارير_ماليةController($this->mockPDO);
        $response = $controller->delete($this->request, $this->response);

        $this->assertInstanceOf(ResponseInterface::class, $response);
    }
}