<?php

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class TestProducts extends TestCase
{
    private $mockPDO;
    private $mockRequest;
    private $mockResponse;
    private $mockStream;

    protected function setUp(): void
    {
        $this->mockPDO = $this->createMock(\PDO::class);
        $this->mockRequest = $this->createMock(ServerRequestInterface::class);
        $this->mockResponse = $this->createMock(ResponseInterface::class);
        $this->mockStream = $this->createMock(StreamInterface::class);
    }

    public function testGetProducts()
    {
        $this->mockPDO->expects($this->once())
            ->method('query')
            ->with('SELECT * FROM products')
            ->willReturn($this->mockPDO);

        $this->mockPDO->expects($this->once())
            ->method('fetchAll')
            ->with(\PDO::FETCH_ASSOC)
            ->willReturn([
                ['id' => 1, 'name' => 'Product 1'],
                ['id' => 2, 'name' => 'Product 2'],
            ]);

        $this->mockRequest->expects($this->once())
            ->method('getMethod')
            ->willReturn('GET');

        $this->mockResponse->expects($this->once())
            ->method('getBody')
            ->willReturn($this->mockStream);

        $this->mockStream->expects($this->once())
            ->method('write')
            ->with(json_encode([
                ['id' => 1, 'name' => 'Product 1'],
                ['id' => 2, 'name' => 'Product 2'],
            ]));

        $products = new Products($this->mockPDO);
        $response = $products->getProducts($this->mockRequest, $this->mockResponse);

        $this->assertInstanceOf(ResponseInterface::class, $response);
    }

    public function testCreateProduct()
    {
        $this->mockPDO->expects($this->once())
            ->method('prepare')
            ->with('INSERT INTO products (name) VALUES (:name)')
            ->willReturn($this->mockPDO);

        $this->mockPDO->expects($this->once())
            ->method('bindParam')
            ->with(':name', 'New Product');

        $this->mockPDO->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $this->mockRequest->expects($this->once())
            ->method('getMethod')
            ->willReturn('POST');

        $this->mockRequest->expects($this->once())
            ->method('getParsedBody')
            ->willReturn(['name' => 'New Product']);

        $this->mockResponse->expects($this->once())
            ->method('getBody')
            ->willReturn($this->mockStream);

        $this->mockStream->expects($this->once())
            ->method('write')
            ->with(json_encode(['message' => 'Product created successfully']));

        $products = new Products($this->mockPDO);
        $response = $products->createProduct($this->mockRequest, $this->mockResponse);

        $this->assertInstanceOf(ResponseInterface::class, $response);
    }

    public function testUpdateProduct()
    {
        $this->mockPDO->expects($this->once())
            ->method('prepare')
            ->with('UPDATE products SET name = :name WHERE id = :id')
            ->willReturn($this->mockPDO);

        $this->mockPDO->expects($this->once())
            ->method('bindParam')
            ->with(':name', 'Updated Product');

        $this->mockPDO->expects($this->once())
            ->method('bindParam')
            ->with(':id', 1);

        $this->mockPDO->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $this->mockRequest->expects($this->once())
            ->method('getMethod')
            ->willReturn('PUT');

        $this->mockRequest->expects($this->once())
            ->method('getParsedBody')
            ->willReturn(['name' => 'Updated Product']);

        $this->mockRequest->expects($this->once())
            ->method('getAttribute')
            ->with('id')
            ->willReturn(1);

        $this->mockResponse->expects($this->once())
            ->method('getBody')
            ->willReturn($this->mockStream);

        $this->mockStream->expects($this->once())
            ->method('write')
            ->with(json_encode(['message' => 'Product updated successfully']));

        $products = new Products($this->mockPDO);
        $response = $products->updateProduct($this->mockRequest, $this->mockResponse);

        $this->assertInstanceOf(ResponseInterface::class, $response);
    }

    public function testDeleteProduct()
    {
        $this->mockPDO->expects($this->once())
            ->method('prepare')
            ->with('DELETE FROM products WHERE id = :id')
            ->willReturn($this->mockPDO);

        $this->mockPDO->expects($this->once())
            ->method('bindParam')
            ->with(':id', 1);

        $this->mockPDO->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $this->mockRequest->expects($this->once())
            ->method('getMethod')
            ->willReturn('DELETE');

        $this->mockRequest->expects($this->once())
            ->method('getAttribute')
            ->with('id')
            ->willReturn(1);

        $this->mockResponse->expects($this->once())
            ->method('getBody')
            ->willReturn($this->mockStream);

        $this->mockStream->expects($this->once())
            ->method('write')
            ->with(json_encode(['message' => 'Product deleted successfully']));

        $products = new Products($this->mockPDO);
        $response = $products->deleteProduct($this->mockRequest, $this->mockResponse);

        $this->assertInstanceOf(ResponseInterface::class, $response);
    }
}