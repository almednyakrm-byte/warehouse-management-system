<?php

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use App\Suppliers;

class TestSuppliers extends TestCase
{
    private $supplier;
    private $request;
    private $response;

    protected function setUp(): void
    {
        $this->supplier = new Suppliers();
        $this->request = $this->createMock(ServerRequestInterface::class);
        $this->response = $this->createMock(ResponseInterface::class);
    }

    public function testGetSuppliers()
    {
        $pdo = $this->createMock(\PDO::class);
        $pdo->expects($this->once())
            ->method('query')
            ->with('SELECT * FROM suppliers')
            ->willReturn($this->createMock(\PDOStatement::class));

        $this->supplier->setPdo($pdo);
        $result = $this->supplier->getSuppliers($this->request, $this->response);
        $this->assertIsArray($result);
    }

    public function testGetSupplierById()
    {
        $pdo = $this->createMock(\PDO::class);
        $pdo->expects($this->once())
            ->method('prepare')
            ->with('SELECT * FROM suppliers WHERE id = :id')
            ->willReturn($this->createMock(\PDOStatement::class));

        $this->supplier->setPdo($pdo);
        $result = $this->supplier->getSupplierById($this->request, $this->response, 1);
        $this->assertIsArray($result);
    }

    public function testCreateSupplier()
    {
        $pdo = $this->createMock(\PDO::class);
        $pdo->expects($this->once())
            ->method('prepare')
            ->with('INSERT INTO suppliers (name, email, phone) VALUES (:name, :email, :phone)')
            ->willReturn($this->createMock(\PDOStatement::class));

        $this->supplier->setPdo($pdo);
        $data = [
            'name' => 'Test Supplier',
            'email' => 'test@example.com',
            'phone' => '1234567890',
        ];
        $result = $this->supplier->createSupplier($this->request, $this->response, $data);
        $this->assertIsArray($result);
    }

    public function testUpdateSupplier()
    {
        $pdo = $this->createMock(\PDO::class);
        $pdo->expects($this->once())
            ->method('prepare')
            ->with('UPDATE suppliers SET name = :name, email = :email, phone = :phone WHERE id = :id')
            ->willReturn($this->createMock(\PDOStatement::class));

        $this->supplier->setPdo($pdo);
        $data = [
            'name' => 'Updated Supplier',
            'email' => 'updated@example.com',
            'phone' => '0987654321',
        ];
        $result = $this->supplier->updateSupplier($this->request, $this->response, 1, $data);
        $this->assertIsArray($result);
    }

    public function testDeleteSupplier()
    {
        $pdo = $this->createMock(\PDO::class);
        $pdo->expects($this->once())
            ->method('prepare')
            ->with('DELETE FROM suppliers WHERE id = :id')
            ->willReturn($this->createMock(\PDOStatement::class));

        $this->supplier->setPdo($pdo);
        $result = $this->supplier->deleteSupplier($this->request, $this->response, 1);
        $this->assertIsArray($result);
    }
}