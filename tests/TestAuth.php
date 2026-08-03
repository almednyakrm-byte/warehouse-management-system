<?php

namespace App\Tests;

use App\Auth;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class TestAuth extends TestCase
{
    private $auth;
    private $session;
    private $logger;

    protected function setUp(): void
    {
        $this->session = $this->createMock(Session::class);
        $this->logger = $this->createMock(LoggerInterface::class);
        $this->auth = new Auth($this->session, $this->logger);
    }

    public function testLoginSuccess()
    {
        $this->session->expects($this->once())
            ->method('set')
            ->with('username', 'testUser');

        $this->logger->expects($this->once())
            ->method('info')
            ->with('User testUser logged in successfully');

        $result = $this->auth->login('testUser', 'testPassword');
        $this->assertTrue($result);
    }

    public function testLoginFailure()
    {
        $this->session->expects($this->never())
            ->method('set');

        $this->logger->expects($this->once())
            ->method('error')
            ->with('Invalid username or password');

        $result = $this->auth->login('wrongUser', 'wrongPassword');
        $this->assertFalse($result);
    }

    public function testRegisterSuccess()
    {
        $this->session->expects($this->once())
            ->method('set')
            ->with('username', 'newUser');

        $this->logger->expects($this->once())
            ->method('info')
            ->with('User newUser registered successfully');

        $result = $this->auth->register('newUser', 'newPassword');
        $this->assertTrue($result);
    }

    public function testRegisterFailure()
    {
        $this->session->expects($this->never())
            ->method('set');

        $this->logger->expects($this->once())
            ->method('error')
            ->with('Username already exists');

        $result = $this->auth->register('existingUser', 'password');
        $this->assertFalse($result);
    }

    public function testGetLoggedInUser()
    {
        $this->session->expects($this->once())
            ->method('get')
            ->with('username')
            ->willReturn('testUser');

        $result = $this->auth->getLoggedInUser();
        $this->assertEquals('testUser', $result);
    }
}