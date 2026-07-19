<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Models\Access;
use App\Repositories\AccessRepository;
use Mockery;
use Mockery\LegacyMockInterface;
use Mockery\MockInterface;
use PDO;

class Testإدارة-وصول extends TestCase
{
    /**
     * @var LegacyMockInterface|MockInterface|PDO
     */
    protected $pdo;

    /**
     * @var LegacyMockInterface|MockInterface|AccessRepository
     */
    protected $accessRepository;

    protected function setUp(): void
    {
        $this->pdo = Mockery::mock(PDO::class);
        $this->accessRepository = new AccessRepository($this->pdo);
    }

    public function testGetAllAccess()
    {
        $expectedResult = [
            ['id' => 1, 'name' => 'Access 1'],
            ['id' => 2, 'name' => 'Access 2'],
        ];

        $this->pdo->shouldReceive('query')
            ->with('SELECT * FROM accesses')
            ->andReturn($this->pdo);

        $this->pdo->shouldReceive('fetchAll')
            ->andReturn($expectedResult);

        $result = $this->accessRepository->getAllAccess();

        $this->assertEquals($expectedResult, $result);
    }

    public function testCreateAccess()
    {
        $data = ['name' => 'Access 3'];

        $this->pdo->shouldReceive('prepare')
            ->with('INSERT INTO accesses (name) VALUES (:name)')
            ->andReturn($this->pdo);

        $this->pdo->shouldReceive('execute')
            ->with($data)
            ->andReturn(true);

        $result = $this->accessRepository->createAccess($data);

        $this->assertTrue($result);
    }

    public function testUpdateAccess()
    {
        $id = 1;
        $data = ['name' => 'Access 1 Updated'];

        $this->pdo->shouldReceive('prepare')
            ->with('UPDATE accesses SET name = :name WHERE id = :id')
            ->andReturn($this->pdo);

        $this->pdo->shouldReceive('execute')
            ->with($data)
            ->andReturn(true);

        $result = $this->accessRepository->updateAccess($id, $data);

        $this->assertTrue($result);
    }

    public function testDeleteAccess()
    {
        $id = 1;

        $this->pdo->shouldReceive('prepare')
            ->with('DELETE FROM accesses WHERE id = :id')
            ->andReturn($this->pdo);

        $this->pdo->shouldReceive('execute')
            ->with(['id' => $id])
            ->andReturn(true);

        $result = $this->accessRepository->deleteAccess($id);

        $this->assertTrue($result);
    }
}


This test file uses the `Mockery` library to mock the `PDO` object and the `AccessRepository` class. It tests the CRUD operations for the 'إدارة وصول' module, including GET, POST, PUT, and DELETE requests. The tests verify that the expected results are returned from the repository methods.