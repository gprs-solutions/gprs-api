<?php

namespace Tests\Unit\Services\AuthService;

use App\Http\Resources\UserResource;
use App\Http\Services\AuthService;
use App\Http\Services\ServiceResult;
use App\Models\User;
use Mockery;
use Tests\Unit\Services\BaseService;

class AuthServiceTest extends BaseService
{
    /**
     * Test if the "happy path" works as expected.
     *
     * @return void
     */
    public function testLogin()
    {
        // Model Mock.
        $mock = Mockery::mock(User::class)->makePartial();
        $mock->shouldReceive('where')->andReturn($this->userInstance);
        $mock->shouldReceive('firstOrFail')->andReturn($this->userInstance);
        $this->app->instance(User::class, $mock);

        $service = app(AuthService::class);
        $result  = $service->login($this->userInstance->email, 'P4$$w0rd');

        $this->assertInstanceOf(ServiceResult::class, $result);
        $this->assertTrue($result->success);
        $this->assertEquals(null, $result->message);
        $this->assertIsArray($result->data);
        $this->assertArrayHasKey('token', $result->data);
        $this->assertNotEmpty($result->data['token']);
    }
}
