<?php

namespace Tests\Unit\Services\ExpService;

use App\Http\Services\ServiceResult;
use App\Http\Services\ExpService;
use App\Models\Experience;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Mockery;
use Tests\Unit\Services\BaseService;

class ExpServiceExceptionTest extends BaseService
{
    /**
     * Test if the in case of failures the system behaves as expected.
     *
     * @return void
     */
    public function testGetInvalidExpID()
    {
        // Model Mock.
        $mock = Mockery::mock(Experience::class);
        $mock->shouldReceive('findOrFail')->andThrow(new ModelNotFoundException());
        $this->app->instance(Experience::class, $mock);

        $service = app(ExpService::class);
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
        $mock = Mockery::mock(Experience::class);
        $mock->shouldReceive('save')->andThrow(new Exception());
        $this->app->instance(Experience::class, $mock);

        $service = app(ExpService::class);
        $result  = $service->create(
            'http://image',
            '2020-10-07',
            '2022-10-07',
            [
                [
                    'id'          => 1,
                    'name'        => 'teste',
                    'lang'        => 'PT',
                    'description' => 'teste',
                ],
            ]
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
    public function testUpdateException()
    {
        // Model Mock.
        $mock = Mockery::mock(Experience::class);
        $mock->shouldReceive('roles')->andThrow(new Exception());
        $this->app->instance(Experience::class, $mock);

        $service = app(ExpService::class);
        $result  = $service->update(
            1,
            'http://image',
            '2020-10-07',
            '2022-10-07',
            [
                [
                    'id'          => 1,
                    'name'        => 'teste',
                    'lang'        => 'PT',
                    'description' => 'teste',
                ],
            ]
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
    public function testSuspendInvalidExpID()
    {
        // Model Mock.
        $mock = Mockery::mock(Experience::class);
        $mock->shouldReceive('findOrFail')->andThrow(new ModelNotFoundException());
        $this->app->instance(Experience::class, $mock);

        $service = app(ExpService::class);
        $result  = $service->suspend(-1);

        $this->assertInstanceOf(ServiceResult::class, $result);
        $this->assertFalse($result->success);
        $this->assertEquals(__('messages.failedOperation'), $result->message);
        $this->assertNull($result->data);
    }
}
