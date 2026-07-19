<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use PDO;
use PDOStatement;

class Testأوامر_شحن extends TestCase
{
    private $pdo;
    private $stmt;

    protected function setUp(): void
    {
        $this->pdo = $this->createMock(PDO::class);
        $this->stmt = $this->createMock(PDOStatement::class);
    }

    public function testGetأوامر_شحن()
    {
        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('SELECT * FROM أوامر_شحن')
            ->willReturn($this->stmt);

        $this->stmt->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $this->stmt->expects($this->once())
            ->method('fetchAll')
            ->willReturn([['id' => 1, 'name' => 'test']]);

        $result = $this->pdo->query('SELECT * FROM أوامر_شحن');
        $this->assertIsArray($result->fetchAll());
    }

    public function testPostأوامر_شحن()
    {
        $data = ['name' => 'test'];

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('INSERT INTO أوامر_شحن (name) VALUES (:name)')
            ->willReturn($this->stmt);

        $this->stmt->expects($this->once())
            ->method('bindParam')
            ->with(':name', $data['name']);

        $this->stmt->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $this->pdo->prepare('INSERT INTO أوامر_شحن (name) VALUES (:name)');
        $this->stmt->bindParam(':name', $data['name']);
        $this->stmt->execute();
        $this->assertTrue($this->stmt->execute());
    }

    public function testPutأوامر_شحن()
    {
        $id = 1;
        $data = ['name' => 'test'];

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('UPDATE أوامر_شحن SET name = :name WHERE id = :id')
            ->willReturn($this->stmt);

        $this->stmt->expects($this->once())
            ->method('bindParam')
            ->with(':name', $data['name']);

        $this->stmt->expects($this->once())
            ->method('bindParam')
            ->with(':id', $id);

        $this->stmt->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $this->pdo->prepare('UPDATE أوامر_شحن SET name = :name WHERE id = :id');
        $this->stmt->bindParam(':name', $data['name']);
        $this->stmt->bindParam(':id', $id);
        $this->stmt->execute();
        $this->assertTrue($this->stmt->execute());
    }

    public function testDeleteأوامر_شحن()
    {
        $id = 1;

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('DELETE FROM أوامر_شحن WHERE id = :id')
            ->willReturn($this->stmt);

        $this->stmt->expects($this->once())
            ->method('bindParam')
            ->with(':id', $id);

        $this->stmt->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $this->pdo->prepare('DELETE FROM أوامر_شحن WHERE id = :id');
        $this->stmt->bindParam(':id', $id);
        $this->stmt->execute();
        $this->assertTrue($this->stmt->execute());
    }
}