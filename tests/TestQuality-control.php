<?php

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use QualityControlModule;

class TestQualityControl extends TestCase
{
    private $qualityControlModule;
    private $mockPdo;

    protected function setUp(): void
    {
        $this->mockPdo = $this->createMock(\PDO::class);
        $this->qualityControlModule = new QualityControlModule($this->mockPdo);
    }

    public function testGetAllQualityControlRecords()
    {
        $this->mockPdo->expects($this->once())
            ->method('query')
            ->with('SELECT * FROM quality_control')
            ->willReturn($this->createMock(\PDOStatement::class));

        $request = $this->createMock(ServerRequestInterface::class);
        $response = $this->createMock(ResponseInterface::class);

        $result = $this->qualityControlModule->getAllQualityControlRecords($request, $response);
        $this->assertInstanceOf(ResponseInterface::class, $result);
    }

    public function testGetQualityControlRecordById()
    {
        $this->mockPdo->expects($this->once())
            ->method('prepare')
            ->with('SELECT * FROM quality_control WHERE id = :id')
            ->willReturn($this->createMock(\PDOStatement::class));

        $request = $this->createMock(ServerRequestInterface::class);
        $request->expects($this->once())
            ->method('getAttribute')
            ->with('id')
            ->willReturn(1);

        $response = $this->createMock(ResponseInterface::class);

        $result = $this->qualityControlModule->getQualityControlRecordById($request, $response);
        $this->assertInstanceOf(ResponseInterface::class, $result);
    }

    public function testCreateQualityControlRecord()
    {
        $this->mockPdo->expects($this->once())
            ->method('prepare')
            ->with('INSERT INTO quality_control (name, description) VALUES (:name, :description)')
            ->willReturn($this->createMock(\PDOStatement::class));

        $request = $this->createMock(ServerRequestInterface::class);
        $request->expects($this->once())
            ->method('getParsedBody')
            ->willReturn(['name' => 'Test', 'description' => 'Test Description']);

        $response = $this->createMock(ResponseInterface::class);

        $result = $this->qualityControlModule->createQualityControlRecord($request, $response);
        $this->assertInstanceOf(ResponseInterface::class, $result);
    }

    public function testUpdateQualityControlRecord()
    {
        $this->mockPdo->expects($this->once())
            ->method('prepare')
            ->with('UPDATE quality_control SET name = :name, description = :description WHERE id = :id')
            ->willReturn($this->createMock(\PDOStatement::class));

        $request = $this->createMock(ServerRequestInterface::class);
        $request->expects($this->once())
            ->method('getAttribute')
            ->with('id')
            ->willReturn(1);
        $request->expects($this->once())
            ->method('getParsedBody')
            ->willReturn(['name' => 'Test', 'description' => 'Test Description']);

        $response = $this->createMock(ResponseInterface::class);

        $result = $this->qualityControlModule->updateQualityControlRecord($request, $response);
        $this->assertInstanceOf(ResponseInterface::class, $result);
    }

    public function testDeleteQualityControlRecord()
    {
        $this->mockPdo->expects($this->once())
            ->method('prepare')
            ->with('DELETE FROM quality_control WHERE id = :id')
            ->willReturn($this->createMock(\PDOStatement::class));

        $request = $this->createMock(ServerRequestInterface::class);
        $request->expects($this->once())
            ->method('getAttribute')
            ->with('id')
            ->willReturn(1);

        $response = $this->createMock(ResponseInterface::class);

        $result = $this->qualityControlModule->deleteQualityControlRecord($request, $response);
        $this->assertInstanceOf(ResponseInterface::class, $result);
    }
}