<?php

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use OrdersModule;

class TestOrders extends TestCase
{
    private $ordersModule;
    private $mockPdo;

    protected function setUp(): void
    {
        $this->mockPdo = $this->createMock(\PDO::class);
        $this->ordersModule = new OrdersModule($this->mockPdo);
    }

    public function testGetOrders()
    {
        $request = $this->createMock(ServerRequestInterface::class);
        $response = $this->createMock(ResponseInterface::class);

        $this->mockPdo
            ->expects($this->once())
            ->method('query')
            ->with('SELECT * FROM orders')
            ->willReturn($this->createMock(\PDOStatement::class));

        $result = $this->ordersModule->getOrders($request, $response);
        $this->assertInstanceOf(ResponseInterface::class, $result);
    }

    public function testGetOrderById()
    {
        $request = $this->createMock(ServerRequestInterface::class);
        $response = $this->createMock(ResponseInterface::class);
        $request->method('getAttribute')->with('id')->willReturn(1);

        $this->mockPdo
            ->expects($this->once())
            ->method('prepare')
            ->with('SELECT * FROM orders WHERE id = :id')
            ->willReturn($this->createMock(\PDOStatement::class));

        $result = $this->ordersModule->getOrderById($request, $response);
        $this->assertInstanceOf(ResponseInterface::class, $result);
    }

    public function testCreateOrder()
    {
        $request = $this->createMock(ServerRequestInterface::class);
        $response = $this->createMock(ResponseInterface::class);
        $request->method('getParsedBody')->willReturn(['customer_id' => 1, 'order_date' => '2022-01-01']);

        $this->mockPdo
            ->expects($this->once())
            ->method('prepare')
            ->with('INSERT INTO orders (customer_id, order_date) VALUES (:customer_id, :order_date)')
            ->willReturn($this->createMock(\PDOStatement::class));

        $result = $this->ordersModule->createOrder($request, $response);
        $this->assertInstanceOf(ResponseInterface::class, $result);
    }

    public function testUpdateOrder()
    {
        $request = $this->createMock(ServerRequestInterface::class);
        $response = $this->createMock(ResponseInterface::class);
        $request->method('getAttribute')->with('id')->willReturn(1);
        $request->method('getParsedBody')->willReturn(['customer_id' => 1, 'order_date' => '2022-01-01']);

        $this->mockPdo
            ->expects($this->once())
            ->method('prepare')
            ->with('UPDATE orders SET customer_id = :customer_id, order_date = :order_date WHERE id = :id')
            ->willReturn($this->createMock(\PDOStatement::class));

        $result = $this->ordersModule->updateOrder($request, $response);
        $this->assertInstanceOf(ResponseInterface::class, $result);
    }

    public function testDeleteOrder()
    {
        $request = $this->createMock(ServerRequestInterface::class);
        $response = $this->createMock(ResponseInterface::class);
        $request->method('getAttribute')->with('id')->willReturn(1);

        $this->mockPdo
            ->expects($this->once())
            ->method('prepare')
            ->with('DELETE FROM orders WHERE id = :id')
            ->willReturn($this->createMock(\PDOStatement::class));

        $result = $this->ordersModule->deleteOrder($request, $response);
        $this->assertInstanceOf(ResponseInterface::class, $result);
    }
}