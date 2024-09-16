<?php

namespace Tests\Unit\Services\SkillService;

use App\Http\Resources\SkillResource;
use App\Http\Services\ServiceResult;
use App\Http\Services\SkillService;
use App\Models\Skill;
use Mockery;
use Tests\Unit\Services\BaseService;

class SkillServiceTest extends BaseService
{
    /**
     * Test if the "happy path" works as expected.
     *
     * @return void
     */
    public function testGet()
    {
        // Model Mock.
        $mock = Mockery::mock(Skill::class);
        $mock->shouldReceive('findOrFail')->andReturn(new Skill());
        $this->app->instance(Skill::class, $mock);

        // Resource mock.
        $mock = Mockery::mock(SkillResource::class);
        $mock->shouldReceive('toArray')->andReturn($this->defaultSuccessReturn);
        $this->app->instance(SkillResource::class, $mock);

        $service = app(SkillService::class);
        $result  = $service->get(1);

        $this->assertInstanceOf(ServiceResult::class, $result);
        $this->assertTrue($result->success);
        $this->assertEquals(null, $result->message);
        $this->assertInstanceOf(SkillResource::class, $result->data);
        $this->assertInstanceOf(Skill::class, $result->data->resource);
        $this->assertNull($result->data->resource->id);
    }

    /**
     * Test if the "happy path" works as expected.
     *
     * @return void
     */
    public function testCreate()
    {
        // Resource mock.
        $mock = Mockery::mock(SkillResource::class);
        $mock->shouldReceive('toArray')->andReturn($this->defaultSuccessReturn);
        $this->app->instance(SkillResource::class, $mock);

        $service = app(SkillService::class);
        $result  = $service->create(
            'http://image',
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
        // Resource mock.
        $mock = Mockery::mock(SkillResource::class);
        $mock->shouldReceive('toArray')->andReturn($this->defaultSuccessReturn);
        $this->app->instance(SkillResource::class, $mock);

        $service = app(SkillService::class);
        $result  = $service->update(
            1,
            'http://image',
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
        $mock = Mockery::mock(Skill::class);
        $mock->shouldReceive('findOrFail')->andReturn(new Skill());
        $mock->shouldReceive('delete')->andReturn(true);
        $this->app->instance(Skill::class, $mock);

        // Resource mock.
        $mock = Mockery::mock(SkillResource::class);
        $mock->shouldReceive('toArray')->andReturn($this->defaultSuccessReturn);
        $this->app->instance(SkillResource::class, $mock);

        $service = app(SkillService::class);
        $result  = $service->suspend($this->userInstance->id);

        $this->assertInstanceOf(ServiceResult::class, $result);
        $this->assertTrue($result->success);
        $this->assertEquals(null, $result->message);
        $this->assertEquals(null, $result->data);
    }
}
