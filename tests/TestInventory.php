<?php

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use App\Inventory;

class TestInventory extends TestCase
{
    private $inventory;
    private $pdo;

    protected function setUp(): void
    {
        $this->pdo = $this->createMock(\PDO::class);
        $this->inventory = new Inventory($this->pdo);
    }

    public function testGetAllInventory()
    {
        $stmt = $this->createMock(\PDOStatement::class);
        $stmt->expects($this->once())
            ->method('execute')
            ->with($this->equalTo([]));

        $stmt->expects($this->once())
            ->method('fetchAll')
            ->willReturn([
                ['id' => 1, 'name' => 'Product 1', 'quantity' => 10],
                ['id' => 2, 'name' => 'Product 2', 'quantity' => 20],
            ]);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with($this->equalTo('SELECT * FROM inventory'))
            ->willReturn($stmt);

        $request = $this->createMock(ServerRequestInterface::class);
        $response = $this->createMock(ResponseInterface::class);

        $result = $this->inventory->getAllInventory($request, $response);

        $this->assertIsArray($result);
        $this->assertCount(2, $result);
    }

    public function testGetInventoryById()
    {
        $stmt = $this->createMock(\PDOStatement::class);
        $stmt->expects($this->once())
            ->method('execute')
            ->with($this->equalTo([1]));

        $stmt->expects($this->once())
            ->method('fetch')
            ->willReturn(['id' => 1, 'name' => 'Product 1', 'quantity' => 10]);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with($this->equalTo('SELECT * FROM inventory WHERE id = ?'))
            ->willReturn($stmt);

        $request = $this->createMock(ServerRequestInterface::class);
        $request->expects($this->once())
            ->method('getAttribute')
            ->with($this->equalTo('id'))
            ->willReturn(1);

        $response = $this->createMock(ResponseInterface::class);

        $result = $this->inventory->getInventoryById($request, $response);

        $this->assertIsArray($result);
        $this->assertEquals(1, $result['id']);
    }

    public function testCreateInventory()
    {
        $stmt = $this->createMock(\PDOStatement::class);
        $stmt->expects($this->once())
            ->method('execute')
            ->with($this->equalTo(['Product 1', 10]));

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with($this->equalTo('INSERT INTO inventory (name, quantity) VALUES (?, ?)'))
            ->willReturn($stmt);

        $request = $this->createMock(ServerRequestInterface::class);
        $request->expects($this->once())
            ->method('getParsedBody')
            ->willReturn(['name' => 'Product 1', 'quantity' => 10]);

        $response = $this->createMock(ResponseInterface::class);

        $result = $this->inventory->createInventory($request, $response);

        $this->assertIsArray($result);
        $this->assertEquals('Product 1', $result['name']);
    }

    public function testUpdateInventory()
    {
        $stmt = $this->createMock(\PDOStatement::class);
        $stmt->expects($this->once())
            ->method('execute')
            ->with($this->equalTo([10, 1]));

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with($this->equalTo('UPDATE inventory SET quantity = ? WHERE id = ?'))
            ->willReturn($stmt);

        $request = $this->createMock(ServerRequestInterface::class);
        $request->expects($this->once())
            ->method('getAttribute')
            ->with($this->equalTo('id'))
            ->willReturn(1);

        $request->expects($this->once())
            ->method('getParsedBody')
            ->willReturn(['quantity' => 10]);

        $response = $this->createMock(ResponseInterface::class);

        $result = $this->inventory->updateInventory($request, $response);

        $this->assertIsArray($result);
        $this->assertEquals(10, $result['quantity']);
    }

    public function testDeleteInventory()
    {
        $stmt = $this->createMock(\PDOStatement::class);
        $stmt->expects($this->once())
            ->method('execute')
            ->with($this->equalTo([1]));

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with($this->equalTo('DELETE FROM inventory WHERE id = ?'))
            ->willReturn($stmt);

        $request = $this->createMock(ServerRequestInterface::class);
        $request->expects($this->once())
            ->method('getAttribute')
            ->with($this->equalTo('id'))
            ->willReturn(1);

        $response = $this->createMock(ResponseInterface::class);

        $result = $this->inventory->deleteInventory($request, $response);

        $this->assertIsArray($result);
        $this->assertEquals(1, $result['id']);
    }
}