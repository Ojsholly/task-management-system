<?php

namespace Tests\Unit;

use App\Services\AuthService;
use App\Services\UserService;
use Mockery;
use PHPUnit\Framework\TestCase;

class AuthServiceTest extends TestCase
{
    private $userServiceMock;

    private $authService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->userServiceMock = Mockery::mock(UserService::class);
        $this->authService = new AuthService($this->userServiceMock);
    }

    public function testLoginFailureDueToUserNotFound()
    {
        $this->userServiceMock
            ->shouldReceive('getUserByField')
            ->with('email', 'nonexistent@example.com')
            ->andReturn(null);

        $result = $this->authService->login('nonexistent@example.com', 'password123', 'device-name');

        $this->assertNull($result);
    }
}
