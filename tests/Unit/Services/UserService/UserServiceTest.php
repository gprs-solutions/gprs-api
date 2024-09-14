<?php

namespace Tests\Unit\Services\UserService;

use App\Http\Resources\UserResource;
use App\Http\Services\ServiceResult;
use App\Http\Services\UserService;
use App\Models\User;
use Mockery;
use Tests\Unit\Services\BaseService;

class UserServiceTest extends BaseService
{
    /**
     * Test if the "happy path" works as expected.
     *
     * @return void
     */
    public function testGet()
    {
        // Model Mock.
        $mock = Mockery::mock(User::class);
        $mock->shouldReceive('findOrFail')->andReturn(new User());
        $this->app->instance(User::class, $mock);

        // Resource mock.
        $mock = Mockery::mock(UserResource::class);
        $mock->shouldReceive('toArray')->andReturn($this->defaultSuccessReturn);
        $this->app->instance(UserResource::class, $mock);

        $service = app(UserService::class);
        $result  = $service->get($this->userInstance->id);

        $this->assertInstanceOf(ServiceResult::class, $result);
        $this->assertTrue($result->success);
        $this->assertEquals(null, $result->message);
        $this->assertInstanceOf(UserResource::class, $result->data);
        $this->assertInstanceOf(User::class, $result->data->resource);
        $this->assertNull($result->data->resource->id);
    }

    /**
     * Test if the "happy path" works as expected.
     *
     * @return void
     */
    public function testCreate()
    {
        // Model Mock.
        $mock = Mockery::mock(User::class)->makePartial();
        $mock->shouldReceive('save')->andReturn(true);
        $mock->shouldReceive('attach')->andReturn(true);
        $this->app->instance(User::class, $mock);

        // Resource mock.
        $mock = Mockery::mock(UserResource::class);
        $mock->shouldReceive('toArray')->andReturn($this->defaultSuccessReturn);
        $this->app->instance(UserResource::class, $mock);

        $service = app(UserService::class);
        $result  = $service->create(
            'John Doe',
            'johndoe@gprs.com',
            'P4$$uu0Rd',
            []
        );

        $this->assertInstanceOf(ServiceResult::class, $result);
        $this->assertTrue($result->success);
        $this->assertEquals(null, $result->message);
        $this->assertEquals(null, $result->data);
    }

    /**
     * Test if the "happy path" works as expected.
     *
     * @return void
     */
    public function testUpdate()
    {
        // Model Mock.
        $mock = Mockery::mock(User::class)->makePartial();
        $mock->shouldReceive('findOrFail')->andReturn(new User());
        $mock->shouldReceive('save')->andReturn(true);
        $mock->shouldReceive('attach')->andReturn(true);
        $this->app->instance(User::class, $mock);

        // Resource mock.
        $mock = Mockery::mock(UserResource::class);
        $mock->shouldReceive('toArray')->andReturn($this->defaultSuccessReturn);
        $this->app->instance(UserResource::class, $mock);

        $service = app(UserService::class);
        $result  = $service->update(
            $this->userInstance->id,
            'John Doe',
            'johndoe@gprs.com',
            'P4$$uu0Rd',
            []
        );

        $this->assertInstanceOf(ServiceResult::class, $result);
        $this->assertTrue($result->success);
        $this->assertEquals(null, $result->message);
        $this->assertEquals(null, $result->data);
    }

    /**
     * Test if the "happy path" works as expected.
     *
     * @return void
     */
    public function testSuspend()
    {
        // Model Mock.
        $mock = Mockery::mock(User::class);
        $mock->shouldReceive('findOrFail')->andReturn(new User());
        $mock->shouldReceive('delete')->andReturn(true);
        $this->app->instance(User::class, $mock);

        // Resource mock.
        $mock = Mockery::mock(UserResource::class);
        $mock->shouldReceive('toArray')->andReturn($this->defaultSuccessReturn);
        $this->app->instance(UserResource::class, $mock);

        $service = app(UserService::class);
        $result  = $service->suspend($this->userInstance->id);

        $this->assertInstanceOf(ServiceResult::class, $result);
        $this->assertTrue($result->success);
        $this->assertEquals(null, $result->message);
        $this->assertEquals(null, $result->data);
    }
}
