<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use PDO;
use PDOStatement;

class Testمستودعات extends TestCase
{
    private MockObject $pdo;
    private MockObject $stmt;

    protected function setUp(): void
    {
        $this->pdo = $this->createMock(PDO::class);
        $this->stmt = $this->createMock(PDOStatement::class);
    }

    public function testGetمستودعات(): void
    {
        $this->stmt->expects($this->once())
            ->method('execute')
            ->with([]);

        $this->stmt->expects($this->once())
            ->method('fetchAll')
            ->willReturn([
                ['id' => 1, 'name' => 'مستودع 1'],
                ['id' => 2, 'name' => 'مستودع 2'],
            ]);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('SELECT * FROM مستودعات')
            ->willReturn($this->stmt);

        $result = $this->pdo->query('SELECT * FROM مستودعات')->fetchAll();

        $this->assertCount(2, $result);
    }

    public function testPostمستودعات(): void
    {
        $data = ['name' => 'مستودع 3'];

        $this->stmt->expects($this->once())
            ->method('execute')
            ->with($data);

        $this->stmt->expects($this->once())
            ->method('rowCount')
            ->willReturn(1);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('INSERT INTO مستودعات (name) VALUES (:name)')
            ->willReturn($this->stmt);

        $result = $this->pdo->prepare('INSERT INTO مستودعات (name) VALUES (:name)')->execute($data);

        $this->assertTrue($result);
    }

    public function testPutمستودعات(): void
    {
        $id = 1;
        $data = ['name' => 'مستودع 1 updated'];

        $this->stmt->expects($this->once())
            ->method('execute')
            ->with(array_merge($data, ['id' => $id]));

        $this->stmt->expects($this->once())
            ->method('rowCount')
            ->willReturn(1);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('UPDATE مستودعات SET name = :name WHERE id = :id')
            ->willReturn($this->stmt);

        $result = $this->pdo->prepare('UPDATE مستودعات SET name = :name WHERE id = :id')->execute(array_merge($data, ['id' => $id]));

        $this->assertTrue($result);
    }

    public function testDeleteمستودعات(): void
    {
        $id = 1;

        $this->stmt->expects($this->once())
            ->method('execute')
            ->with(['id' => $id]);

        $this->stmt->expects($this->once())
            ->method('rowCount')
            ->willReturn(1);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('DELETE FROM مستودعات WHERE id = :id')
            ->willReturn($this->stmt);

        $result = $this->pdo->prepare('DELETE FROM مستودعات WHERE id = :id')->execute(['id' => $id]);

        $this->assertTrue($result);
    }
}