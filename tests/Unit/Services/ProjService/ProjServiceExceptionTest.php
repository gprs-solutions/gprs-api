<?php

namespace Tests\Unit\Services\ProjService;

use App\Http\Services\ServiceResult;
use App\Http\Services\ProjService;
use App\Models\Project;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Mockery;
use Tests\Unit\Services\BaseService;

class ProjServiceExceptionTest extends BaseService
{
    /**
     * Test if the in case of failures the system behaves as expected.
     *
     * @return void
     */
    public function testGetInvalidProjID()
    {
        // Model Mock.
        $mock = Mockery::mock(Project::class);
        $mock->shouldReceive('findOrFail')->andThrow(new ModelNotFoundException());
        $this->app->instance(Project::class, $mock);

        $service = app(ProjService::class);
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
        $mock = Mockery::mock(Project::class);
        $mock->shouldReceive('save')->andThrow(new Exception());
        $this->app->instance(Project::class, $mock);

        $service = app(ProjService::class);
        $result  = $service->create(
            'http://image',
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
        $mock = Mockery::mock(Project::class);
        $mock->shouldReceive('roles')->andThrow(new Exception());
        $this->app->instance(Project::class, $mock);

        $service = app(ProjService::class);
        $result  = $service->update(
            1,
            'http://image',
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
    public function testSuspendInvalidProjID()
    {
        // Model Mock.
        $mock = Mockery::mock(Project::class);
        $mock->shouldReceive('findOrFail')->andThrow(new ModelNotFoundException());
        $this->app->instance(Project::class, $mock);

        $service = app(ProjService::class);
        $result  = $service->suspend(-1);

        $this->assertInstanceOf(ServiceResult::class, $result);
        $this->assertFalse($result->success);
        $this->assertEquals(__('messages.failedOperation'), $result->message);
        $this->assertNull($result->data);
    }
}
