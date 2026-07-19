<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use PDO;
use PDOStatement;

class Testشحنات extends TestCase
{
    private MockObject $pdo;
    private MockObject $statement;

    protected function setUp(): void
    {
        $this->pdo = $this->createMock(PDO::class);
        $this->statement = $this->createMock(PDOStatement::class);
    }

    public function testGetشحنات(): void
    {
        $this->statement->expects($this->once())
            ->method('execute')
            ->with([':id' => 1]);

        $this->statement->expects($this->once())
            ->method('fetchAll')
            ->willReturn([['id' => 1, 'name' => 'شحنة 1']]);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('SELECT * FROM شحنات WHERE id = :id')
            ->willReturn($this->statement);

        $result = $this->pdo->query('SELECT * FROM شحنات WHERE id = 1');
        $this->assertEquals([['id' => 1, 'name' => 'شحنة 1']], $result->fetchAll());
    }

    public function testPostشحنات(): void
    {
        $this->statement->expects($this->once())
            ->method('execute')
            ->with([':name' => 'شحنة جديدة']);

        $this->statement->expects($this->once())
            ->method('rowCount')
            ->willReturn(1);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('INSERT INTO شحنات (name) VALUES (:name)')
            ->willReturn($this->statement);

        $result = $this->pdo->exec('INSERT INTO شحنات (name) VALUES ("شحنة جديدة")');
        $this->assertEquals(1, $result);
    }

    public function testPutشحنات(): void
    {
        $this->statement->expects($this->once())
            ->method('execute')
            ->with([':id' => 1, ':name' => 'شحنة محدثة']);

        $this->statement->expects($this->once())
            ->method('rowCount')
            ->willReturn(1);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('UPDATE شحنات SET name = :name WHERE id = :id')
            ->willReturn($this->statement);

        $result = $this->pdo->exec('UPDATE شحنات SET name = "شحنة محدثة" WHERE id = 1');
        $this->assertEquals(1, $result);
    }

    public function testDeleteشحنات(): void
    {
        $this->statement->expects($this->once())
            ->method('execute')
            ->with([':id' => 1]);

        $this->statement->expects($this->once())
            ->method('rowCount')
            ->willReturn(1);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('DELETE FROM شحنات WHERE id = :id')
            ->willReturn($this->statement);

        $result = $this->pdo->exec('DELETE FROM شحنات WHERE id = 1');
        $this->assertEquals(1, $result);
    }
}