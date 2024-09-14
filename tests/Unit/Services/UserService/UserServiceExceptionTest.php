<?php

namespace Tests\Unit\Services\UserService;

use App\Http\Resources\UserResource;
use App\Http\Services\ServiceResult;
use App\Http\Services\UserService;
use App\Models\User;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Mockery;
use Tests\Unit\Services\BaseService;

class UserServiceExceptionTest extends BaseService
{
    /**
     * Test if the in case of failures the system behaves as expected.
     *
     * @return void
     */
    public function testGetInvalidUserID()
    {
        // Model Mock.
        $mock = Mockery::mock(User::class);
        $mock->shouldReceive('findOrFail')->andThrow(new ModelNotFoundException());
        $this->app->instance(User::class, $mock);

        $service = app(UserService::class);
        $result  = $service->get(-1);

        $this->assertInstanceOf(ServiceResult::class, $result);
        $this->assertFalse($result->success);
        $this->assertEquals(__('messages.failedOperation'), $result->message);
        $this->assertNull($result->data);
    }

    /**
     * Test if the in case of failures the system behaves as expected.
     *
     * @return void
     */
    public function testCreateSaveFailure()
    {
        // Model Mock.
        $mock = Mockery::mock(User::class);
        $mock->shouldReceive('save')->andThrow(new Exception());
        $this->app->instance(User::class, $mock);

        $service = app(UserService::class);
        $result  = $service->create(
            $this->userInstance->name,
            $this->userInstance->email,
            'password',
            []
        );

        $this->assertInstanceOf(ServiceResult::class, $result);
        $this->assertFalse($result->success);
        $this->assertEquals(__('messages.failedOperation'), $result->message);
        $this->assertNull($result->data);
    }

    /**
     * Test if the in case of failures the system behaves as expected.
     *
     * @return void
     */
    public function testUpdateInvalidRole()
    {
        // Model Mock.
        $mock = Mockery::mock(User::class);
        $mock->shouldReceive('roles')->andThrow(new Exception());
        $this->app->instance(User::class, $mock);

        $service = app(UserService::class);
        $result  = $service->update(
            $this->userInstance->id,
            $this->userInstance->name,
            $this->userInstance->email,
            'password',
            ['invalid Role']
        );

        $this->assertInstanceOf(ServiceResult::class, $result);
        $this->assertFalse($result->success);
        $this->assertEquals(__('messages.failedOperation'), $result->message);
        $this->assertNull($result->data);
    }

    /**
     * Test if the in case of failures the system behaves as expected.
     *
     * @return void
     */
    public function testSuspendInvalidUserID()
    {
        // Model Mock.
        $mock = Mockery::mock(User::class);
        $mock->shouldReceive('findOrFail')->andThrow(new ModelNotFoundException());
        $this->app->instance(User::class, $mock);

        $service = app(UserService::class);
        $result  = $service->suspend(-1);

        $this->assertInstanceOf(ServiceResult::class, $result);
        $this->assertFalse($result->success);
        $this->assertEquals(__('messages.failedOperation'), $result->message);
        $this->assertNull($result->data);
    }
}
