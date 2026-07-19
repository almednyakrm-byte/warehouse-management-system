<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use PDO;
use PDOStatement;

class TestOrders extends TestCase
{
    private MockObject $pdo;
    private MockObject $pdoStatement;

    protected function setUp(): void
    {
        $this->pdo = $this->createMock(PDO::class);
        $this->pdoStatement = $this->createMock(PDOStatement::class);
    }

    public function testGetOrders(): void
    {
        $this->pdo
            ->expects($this->once())
            ->method('prepare')
            ->with('SELECT * FROM orders')
            ->willReturn($this->pdoStatement);

        $this->pdoStatement
            ->expects($this->once())
            ->method('execute')
            ->with([]);

        $this->pdoStatement
            ->expects($this->once())
            ->method('fetchAll')
            ->willReturn([
                ['id' => 1, 'customer_id' => 1, 'order_date' => '2022-01-01'],
                ['id' => 2, 'customer_id' => 2, 'order_date' => '2022-01-02'],
            ]);

        $request = $this->createMock(ServerRequestInterface::class);
        $response = $this->createMock(ResponseInterface::class);
        $stream = $this->createMock(StreamInterface::class);

        $response
            ->expects($this->once())
            ->method('getBody')
            ->willReturn($stream);

        $stream
            ->expects($this->once())
            ->method('write')
            ->with(json_encode([
                ['id' => 1, 'customer_id' => 1, 'order_date' => '2022-01-01'],
                ['id' => 2, 'customer_id' => 2, 'order_date' => '2022-01-02'],
            ]));

        $orders = new Orders($this->pdo);
        $orders->getOrders($request, $response);
    }

    public function testGetOrderById(): void
    {
        $this->pdo
            ->expects($this->once())
            ->method('prepare')
            ->with('SELECT * FROM orders WHERE id = :id')
            ->willReturn($this->pdoStatement);

        $this->pdoStatement
            ->expects($this->once())
            ->method('execute')
            ->with([':id' => 1]);

        $this->pdoStatement
            ->expects($this->once())
            ->method('fetch')
            ->willReturn(['id' => 1, 'customer_id' => 1, 'order_date' => '2022-01-01']);

        $request = $this->createMock(ServerRequestInterface::class);
        $request
            ->expects($this->once())
            ->method('getAttribute')
            ->with('id')
            ->willReturn(1);

        $response = $this->createMock(ResponseInterface::class);
        $stream = $this->createMock(StreamInterface::class);

        $response
            ->expects($this->once())
            ->method('getBody')
            ->willReturn($stream);

        $stream
            ->expects($this->once())
            ->method('write')
            ->with(json_encode(['id' => 1, 'customer_id' => 1, 'order_date' => '2022-01-01']));

        $orders = new Orders($this->pdo);
        $orders->getOrderById($request, $response);
    }

    public function testCreateOrder(): void
    {
        $this->pdo
            ->expects($this->once())
            ->method('prepare')
            ->with('INSERT INTO orders (customer_id, order_date) VALUES (:customer_id, :order_date)')
            ->willReturn($this->pdoStatement);

        $this->pdoStatement
            ->expects($this->once())
            ->method('execute')
            ->with([':customer_id' => 1, ':order_date' => '2022-01-01']);

        $request = $this->createMock(ServerRequestInterface::class);
        $request
            ->expects($this->once())
            ->method('getParsedBody')
            ->willReturn(['customer_id' => 1, 'order_date' => '2022-01-01']);

        $response = $this->createMock(ResponseInterface::class);
        $stream = $this->createMock(StreamInterface::class);

        $response
            ->expects($this->once())
            ->method('getBody')
            ->willReturn($stream);

        $stream
            ->expects($this->once())
            ->method('write')
            ->with(json_encode(['message' => 'Order created successfully']));

        $orders = new Orders($this->pdo);
        $orders->createOrder($request, $response);
    }

    public function testUpdateOrder(): void
    {
        $this->pdo
            ->expects($this->once())
            ->method('prepare')
            ->with('UPDATE orders SET customer_id = :customer_id, order_date = :order_date WHERE id = :id')
            ->willReturn($this->pdoStatement);

        $this->pdoStatement
            ->expects($this->once())
            ->method('execute')
            ->with([':id' => 1, ':customer_id' => 1, ':order_date' => '2022-01-01']);

        $request = $this->createMock(ServerRequestInterface::class);
        $request
            ->expects($this->once())
            ->method('getAttribute')
            ->with('id')
            ->willReturn(1);

        $request
            ->expects($this->once())
            ->method('getParsedBody')
            ->willReturn(['customer_id' => 1, 'order_date' => '2022-01-01']);

        $response = $this->createMock(ResponseInterface::class);
        $stream = $this->createMock(StreamInterface::class);

        $response
            ->expects($this->once())
            ->method('getBody')
            ->willReturn($stream);

        $stream
            ->expects($this->once())
            ->method('write')
            ->with(json_encode(['message' => 'Order updated successfully']));

        $orders = new Orders($this->pdo);
        $orders->updateOrder($request, $response);
    }

    public function testDeleteOrder(): void
    {
        $this->pdo
            ->expects($this->once())
            ->method('prepare')
            ->with('DELETE FROM orders WHERE id = :id')
            ->willReturn($this->pdoStatement);

        $this->pdoStatement
            ->expects($this->once())
            ->method('execute')
            ->with([':id' => 1]);

        $request = $this->createMock(ServerRequestInterface::class);
        $request
            ->expects($this->once())
            ->method('getAttribute')
            ->with('id')
            ->willReturn(1);

        $response = $this->createMock(ResponseInterface::class);
        $stream = $this->createMock(StreamInterface::class);

        $response
            ->expects($this->once())
            ->method('getBody')
            ->willReturn($stream);

        $stream
            ->expects($this->once())
            ->method('write')
            ->with(json_encode(['message' => 'Order deleted successfully']));

        $orders = new Orders($this->pdo);
        $orders->deleteOrder($request, $response);
    }
}