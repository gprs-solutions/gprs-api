<?php

namespace Tests\Unit\Services\SkillService;

use App\Http\Services\ServiceResult;
use App\Http\Services\SkillService;
use App\Models\Skill;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Mockery;
use Tests\Unit\Services\BaseService;

class SkillServiceExceptionTest extends BaseService
{
    /**
     * Test if the in case of failures the system behaves as expected.
     *
     * @return void
     */
    public function testGetInvalidSkillID()
    {
        // Model Mock.
        $mock = Mockery::mock(Skill::class);
        $mock->shouldReceive('findOrFail')->andThrow(new ModelNotFoundException());
        $this->app->instance(Skill::class, $mock);

        $service = app(SkillService::class);
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
        $mock = Mockery::mock(Skill::class);
        $mock->shouldReceive('save')->andThrow(new Exception());
        $this->app->instance(Skill::class, $mock);

        $service = app(SkillService::class);
        $result  = $service->create(
            'http://image',
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
        $mock = Mockery::mock(Skill::class);
        $mock->shouldReceive('roles')->andThrow(new Exception());
        $this->app->instance(Skill::class, $mock);

        $service = app(SkillService::class);
        $result  = $service->update(
            1,
            'http://image',
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
    public function testSuspendInvalidSkillID()
    {
        // Model Mock.
        $mock = Mockery::mock(Skill::class);
        $mock->shouldReceive('findOrFail')->andThrow(new ModelNotFoundException());
        $this->app->instance(Skill::class, $mock);

        $service = app(SkillService::class);
        $result  = $service->suspend(-1);

        $this->assertInstanceOf(ServiceResult::class, $result);
        $this->assertFalse($result->success);
        $this->assertEquals(__('messages.failedOperation'), $result->message);
        $this->assertNull($result->data);
    }
}
