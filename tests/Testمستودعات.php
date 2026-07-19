<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use PDO;
use PDOStatement;

class Testمستودعات extends TestCase
{
    private $pdo;
    private $stmt;

    protected function setUp(): void
    {
        $this->pdo = $this->createMock(PDO::class);
        $this->stmt = $this->createMock(PDOStatement::class);
    }

    public function testGetمستودعات(): void
    {
        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('SELECT * FROM مستودعات')
            ->willReturn($this->stmt);

        $this->stmt->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $this->stmt->expects($this->once())
            ->method('fetchAll')
            ->willReturn([['id' => 1, 'name' => 'مستودع 1']]);

        $result = $this->getمستودعات($this->pdo);
        $this->assertEquals([['id' => 1, 'name' => 'مستودع 1']], $result);
    }

    public function testPostمستودعات(): void
    {
        $data = ['name' => 'مستودع 2'];

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('INSERT INTO مستودعات (name) VALUES (:name)')
            ->willReturn($this->stmt);

        $this->stmt->expects($this->once())
            ->method('bindParam')
            ->with(':name', $data['name']);

        $this->stmt->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $this->pdo->expects($this->once())
            ->method('lastInsertId')
            ->willReturn(2);

        $result = $this->postمستودعات($this->pdo, $data);
        $this->assertEquals(2, $result);
    }

    public function testPutمستودعات(): void
    {
        $id = 1;
        $data = ['name' => 'مستودع 1 updated'];

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('UPDATE مستودعات SET name = :name WHERE id = :id')
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

        $result = $this->putمستودعات($this->pdo, $id, $data);
        $this->assertEquals(true, $result);
    }

    public function testDeleteمستودعات(): void
    {
        $id = 1;

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('DELETE FROM مستودعات WHERE id = :id')
            ->willReturn($this->stmt);

        $this->stmt->expects($this->once())
            ->method('bindParam')
            ->with(':id', $id);

        $this->stmt->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $result = $this->deleteمستودعات($this->pdo, $id);
        $this->assertEquals(true, $result);
    }

    private function getمستودعات(PDO $pdo): array
    {
        $stmt = $pdo->prepare('SELECT * FROM مستودعات');
        $stmt->execute();
        return $stmt->fetchAll();
    }

    private function postمستودعات(PDO $pdo, array $data): int
    {
        $stmt = $pdo->prepare('INSERT INTO مستودعات (name) VALUES (:name)');
        $stmt->bindParam(':name', $data['name']);
        $stmt->execute();
        return $pdo->lastInsertId();
    }

    private function putمستودعات(PDO $pdo, int $id, array $data): bool
    {
        $stmt = $pdo->prepare('UPDATE مستودعات SET name = :name WHERE id = :id');
        $stmt->bindParam(':name', $data['name']);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    private function deleteمستودعات(PDO $pdo, int $id): bool
    {
        $stmt = $pdo->prepare('DELETE FROM مستودعات WHERE id = :id');
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}