<?php

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use PDO;
use App\Shipments;

class TestShipments extends TestCase
{
    private $shipment;
    private $pdo;

    protected function setUp(): void
    {
        $this->pdo = $this->createMock(PDO::class);
        $this->shipment = new Shipments($this->pdo);
    }

    public function testGetAllShipments()
    {
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->expects($this->once())
            ->method('execute')
            ->with([]);

        $stmt->expects($this->once())
            ->method('fetchAll')
            ->willReturn([
                ['id' => 1, 'name' => 'Shipment 1'],
                ['id' => 2, 'name' => 'Shipment 2'],
            ]);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('SELECT * FROM shipments')
            ->willReturn($stmt);

        $result = $this->shipment->getAllShipments();
        $this->assertIsArray($result);
        $this->assertCount(2, $result);
    }

    public function testGetShipmentById()
    {
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->expects($this->once())
            ->method('execute')
            ->with([1]);

        $stmt->expects($this->once())
            ->method('fetch')
            ->willReturn(['id' => 1, 'name' => 'Shipment 1']);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('SELECT * FROM shipments WHERE id = ?')
            ->willReturn($stmt);

        $result = $this->shipment->getShipmentById(1);
        $this->assertIsArray($result);
        $this->assertEquals(1, $result['id']);
    }

    public function testCreateShipment()
    {
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->expects($this->once())
            ->method('execute')
            ->with(['name' => 'New Shipment']);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('INSERT INTO shipments (name) VALUES (?)')
            ->willReturn($stmt);

        $result = $this->shipment->createShipment(['name' => 'New Shipment']);
        $this->assertTrue($result);
    }

    public function testUpdateShipment()
    {
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->expects($this->once())
            ->method('execute')
            ->with([1, 'Updated Shipment']);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('UPDATE shipments SET name = ? WHERE id = ?')
            ->willReturn($stmt);

        $result = $this->shipment->updateShipment(1, ['name' => 'Updated Shipment']);
        $this->assertTrue($result);
    }

    public function testDeleteShipment()
    {
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->expects($this->once())
            ->method('execute')
            ->with([1]);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('DELETE FROM shipments WHERE id = ?')
            ->willReturn($stmt);

        $result = $this->shipment->deleteShipment(1);
        $this->assertTrue($result);
    }
}