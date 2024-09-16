<?php

namespace Tests\Unit\Services\ProjService;

use App\Http\Resources\ProjectResource;
use App\Http\Services\ServiceResult;
use App\Http\Services\ProjService;
use App\Models\Project;
use Mockery;
use Tests\Unit\Services\BaseService;

class ProjServiceTest extends BaseService
{
    /**
     * Test if the "happy path" works as expected.
     *
     * @return void
     */
    public function testGet()
    {
        // Model Mock.
        $mock = Mockery::mock(Project::class);
        $mock->shouldReceive('findOrFail')->andReturn(new Project());
        $this->app->instance(Project::class, $mock);

        // Resource mock.
        $mock = Mockery::mock(ProjectResource::class);
        $mock->shouldReceive('toArray')->andReturn($this->defaultSuccessReturn);
        $this->app->instance(ProjectResource::class, $mock);

        $service = app(ProjService::class);
        $result  = $service->get(1);

        $this->assertInstanceOf(ServiceResult::class, $result);
        $this->assertTrue($result->success);
        $this->assertEquals(null, $result->message);
        $this->assertInstanceOf(ProjectResource::class, $result->data);
        $this->assertInstanceOf(Project::class, $result->data->resource);
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
        $mock = Mockery::mock(ProjectResource::class);
        $mock->shouldReceive('toArray')->andReturn($this->defaultSuccessReturn);
        $this->app->instance(ProjectResource::class, $mock);

        $service = app(ProjService::class);
        $result  = $service->create(
            'http://image',
            'http://image',
            '2020-10-07',
            '2022-10-07',
            [
                [
                    'name'        => 'teste',
                    'lang'        => 'PT',
                    'description' => 'teste',
                ],
            ]
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
        $project = Project::factory()->create();
        // Resource mock.
        $mock = Mockery::mock(ProjectResource::class);
        $mock->shouldReceive('toArray')->andReturn($this->defaultSuccessReturn);
        $this->app->instance(ProjectResource::class, $mock);

        $service = app(ProjService::class);
        $result  = $service->update(
            $project->id,
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
        $mock = Mockery::mock(Project::class);
        $mock->shouldReceive('findOrFail')->andReturn(new Project());
        $mock->shouldReceive('delete')->andReturn(true);
        $this->app->instance(Project::class, $mock);

        // Resource mock.
        $mock = Mockery::mock(ProjectResource::class);
        $mock->shouldReceive('toArray')->andReturn($this->defaultSuccessReturn);
        $this->app->instance(ProjectResource::class, $mock);

        $service = app(ProjService::class);
        $result  = $service->suspend($this->userInstance->id);

        $this->assertInstanceOf(ServiceResult::class, $result);
        $this->assertTrue($result->success);
        $this->assertEquals(null, $result->message);
        $this->assertEquals(null, $result->data);
    }
}
