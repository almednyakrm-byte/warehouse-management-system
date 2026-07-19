<?php

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use PDO;
use PDOStatement;

class TestProducts extends TestCase
{
    private $pdo;
    private $request;
    private $response;

    protected function setUp(): void
    {
        $this->pdo = $this->createMock(PDO::class);
        $this->request = $this->createMock(ServerRequestInterface::class);
        $this->response = $this->createMock(ResponseInterface::class);
    }

    public function testGetProducts()
    {
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->expects($this->once())
            ->method('execute')
            ->with([]);
        $stmt->expects($this->once())
            ->method('fetchAll')
            ->willReturn([
                ['id' => 1, 'name' => 'Product 1'],
                ['id' => 2, 'name' => 'Product 2'],
            ]);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('SELECT * FROM products')
            ->willReturn($stmt);

        $products = $this->getProducts($this->pdo);
        $this->assertCount(2, $products);
    }

    public function testGetProductById()
    {
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->expects($this->once())
            ->method('execute')
            ->with([1]);
        $stmt->expects($this->once())
            ->method('fetch')
            ->willReturn(['id' => 1, 'name' => 'Product 1']);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('SELECT * FROM products WHERE id = ?')
            ->willReturn($stmt);

        $product = $this->getProductById($this->pdo, 1);
        $this->assertEquals(1, $product['id']);
    }

    public function testCreateProduct()
    {
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->expects($this->once())
            ->method('execute')
            ->with(['name' => 'New Product']);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('INSERT INTO products (name) VALUES (?)')
            ->willReturn($stmt);

        $this->createProduct($this->pdo, 'New Product');
    }

    public function testUpdateProduct()
    {
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->expects($this->once())
            ->method('execute')
            ->with([1, 'Updated Product']);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('UPDATE products SET name = ? WHERE id = ?')
            ->willReturn($stmt);

        $this->updateProduct($this->pdo, 1, 'Updated Product');
    }

    public function testDeleteProduct()
    {
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->expects($this->once())
            ->method('execute')
            ->with([1]);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('DELETE FROM products WHERE id = ?')
            ->willReturn($stmt);

        $this->deleteProduct($this->pdo, 1);
    }

    private function getProducts(PDO $pdo)
    {
        $stmt = $pdo->prepare('SELECT * FROM products');
        $stmt->execute();
        return $stmt->fetchAll();
    }

    private function getProductById(PDO $pdo, int $id)
    {
        $stmt = $pdo->prepare('SELECT * FROM products WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    private function createProduct(PDO $pdo, string $name)
    {
        $stmt = $pdo->prepare('INSERT INTO products (name) VALUES (?)');
        $stmt->execute([$name]);
    }

    private function updateProduct(PDO $pdo, int $id, string $name)
    {
        $stmt = $pdo->prepare('UPDATE products SET name = ? WHERE id = ?');
        $stmt->execute([$name, $id]);
    }

    private function deleteProduct(PDO $pdo, int $id)
    {
        $stmt = $pdo->prepare('DELETE FROM products WHERE id = ?');
        $stmt->execute([$id]);
    }
}