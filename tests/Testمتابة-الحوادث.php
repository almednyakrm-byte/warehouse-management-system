<?php

namespace App\Tests\Controller;

use App\Controller\MetabaAlhawadithController;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use PDO;
use PDOStatement;

class Testمتابةالحوادث extends TestCase
{
    private $controller;
    private $pdoMock;

    protected function setUp(): void
    {
        $this->pdoMock = $this->createMock(PDO::class);
        $this->controller = new MetabaAlhawadithController($this->pdoMock);
    }

    public function testGetAllMetabaAlhawadith()
    {
        $expectedData = [
            ['id' => 1, 'name' => 'Metaba Alhawadith 1'],
            ['id' => 2, 'name' => 'Metaba Alhawadith 2'],
        ];

        $this->pdoMock->expects($this->once())
            ->method('query')
            ->with('SELECT * FROM metaba_alhawadith')
            ->willReturn($this->createMock(PDOStatement::class));

        $stmtMock = $this->createMock(PDOStatement::class);
        $stmtMock->expects($this->once())
            ->method('fetchAll')
            ->willReturn($expectedData);

        $this->pdoMock->expects($this->once())
            ->method('prepare')
            ->with('SELECT * FROM metaba_alhawadith')
            ->willReturn($stmtMock);

        $response = $this->controller->getAllMetabaAlhawadith();
        $this->assertEquals($expectedData, $response);
    }

    public function testCreateMetabaAlhawadith()
    {
        $data = ['name' => 'Metaba Alhawadith 3'];

        $this->pdoMock->expects($this->once())
            ->method('prepare')
            ->with('INSERT INTO metaba_alhawadith (name) VALUES (:name)')
            ->willReturn($this->createMock(PDOStatement::class));

        $stmtMock = $this->createMock(PDOStatement::class);
        $stmtMock->expects($this->once())
            ->method('execute')
            ->with(['name' => $data['name']]);

        $this->pdoMock->expects($this->once())
            ->method('prepare')
            ->with('INSERT INTO metaba_alhawadith (name) VALUES (:name)')
            ->willReturn($stmtMock);

        $response = $this->controller->createMetabaAlhawadith($data);
        $this->assertTrue($response);
    }

    public function testUpdateMetabaAlhawadith()
    {
        $id = 1;
        $data = ['name' => 'Metaba Alhawadith 1 Updated'];

        $this->pdoMock->expects($this->once())
            ->method('prepare')
            ->with('UPDATE metaba_alhawadith SET name = :name WHERE id = :id')
            ->willReturn($this->createMock(PDOStatement::class));

        $stmtMock = $this->createMock(PDOStatement::class);
        $stmtMock->expects($this->once())
            ->method('execute')
            ->with(['name' => $data['name'], 'id' => $id]);

        $this->pdoMock->expects($this->once())
            ->method('prepare')
            ->with('UPDATE metaba_alhawadith SET name = :name WHERE id = :id')
            ->willReturn($stmtMock);

        $response = $this->controller->updateMetabaAlhawadith($id, $data);
        $this->assertTrue($response);
    }

    public function testDeleteMetabaAlhawadith()
    {
        $id = 1;

        $this->pdoMock->expects($this->once())
            ->method('prepare')
            ->with('DELETE FROM metaba_alhawadith WHERE id = :id')
            ->willReturn($this->createMock(PDOStatement::class));

        $stmtMock = $this->createMock(PDOStatement::class);
        $stmtMock->expects($this->once())
            ->method('execute')
            ->with(['id' => $id]);

        $this->pdoMock->expects($this->once())
            ->method('prepare')
            ->with('DELETE FROM metaba_alhawadith WHERE id = :id')
            ->willReturn($stmtMock);

        $response = $this->controller->deleteMetabaAlhawadith($id);
        $this->assertTrue($response);
    }
}


This test file covers the CRUD operations for the 'متابة الحوادث' module. It uses PHPUnit's mocking capabilities to simulate the behavior of the PDO object. The tests verify that the controller methods return the expected results when interacting with the database.