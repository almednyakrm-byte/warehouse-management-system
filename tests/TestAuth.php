<?php

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\RequestHandlerInterface;
use App\Auth;

class TestAuth extends TestCase
{
    private $auth;
    private $mockDatabase;

    protected function setUp(): void
    {
        $this->mockDatabase = $this->createMock(\PDO::class);
        $this->auth = new Auth($this->mockDatabase);
    }

    public function testLoginSuccess()
    {
        $this->mockDatabase->expects($this->once())
            ->method('prepare')
            ->with('SELECT * FROM users WHERE username = :username AND password = :password')
            ->willReturn($this->createMock(\PDOStatement::class));

        $this->mockDatabase->expects($this->once())
            ->method('execute')
            ->with([':username' => 'testUser', ':password' => 'testPassword']);

        $result = $this->auth->login('testUser', 'testPassword');
        $this->assertTrue($result);
    }

    public function testLoginFailure()
    {
        $this->mockDatabase->expects($this->once())
            ->method('prepare')
            ->with('SELECT * FROM users WHERE username = :username AND password = :password')
            ->willReturn($this->createMock(\PDOStatement::class));

        $this->mockDatabase->expects($this->once())
            ->method('execute')
            ->with([':username' => 'testUser', ':password' => 'testPassword']);

        $result = $this->auth->login('testUser', 'wrongPassword');
        $this->assertFalse($result);
    }

    public function testRegisterSuccess()
    {
        $this->mockDatabase->expects($this->once())
            ->method('prepare')
            ->with('INSERT INTO users (username, password) VALUES (:username, :password)')
            ->willReturn($this->createMock(\PDOStatement::class));

        $this->mockDatabase->expects($this->once())
            ->method('execute')
            ->with([':username' => 'testUser', ':password' => 'testPassword']);

        $result = $this->auth->register('testUser', 'testPassword');
        $this->assertTrue($result);
    }

    public function testRegisterFailure()
    {
        $this->mockDatabase->expects($this->once())
            ->method('prepare')
            ->with('INSERT INTO users (username, password) VALUES (:username, :password)')
            ->willReturn($this->createMock(\PDOStatement::class));

        $this->mockDatabase->expects($this->once())
            ->method('execute')
            ->with([':username' => 'testUser', ':password' => 'testPassword'])
            ->willThrowException(new \PDOException('Duplicate entry'));

        $result = $this->auth->register('testUser', 'testPassword');
        $this->assertFalse($result);
    }

    public function testSessionLogin()
    {
        $_SESSION['username'] = 'testUser';
        $result = $this->auth->isLogged();
        $this->assertTrue($result);
    }

    public function testSessionLogout()
    {
        $_SESSION['username'] = 'testUser';
        $this->auth->logout();
        $result = $this->auth->isLogged();
        $this->assertFalse($result);
    }
}