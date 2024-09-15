<?php

namespace Tests\Unit\Services\ExpService;

use App\Http\Resources\ExperienceResource;
use App\Http\Services\ServiceResult;
use App\Http\Services\ExpService;
use App\Models\Description;
use App\Models\Experience;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Mockery;
use Tests\Unit\Services\BaseService;

class ExpServiceTest extends BaseService
{
    /**
     * Test if the "happy path" works as expected.
     *
     * @return void
     */
    public function testGet()
    {
        // Model Mock.
        $mock = Mockery::mock(Experience::class);
        $mock->shouldReceive('findOrFail')->andReturn(new Experience());
        $this->app->instance(Experience::class, $mock);

        // Resource mock.
        $mock = Mockery::mock(ExperienceResource::class);
        $mock->shouldReceive('toArray')->andReturn($this->defaultSuccessReturn);
        $this->app->instance(ExperienceResource::class, $mock);

        $service = app(ExpService::class);
        $result  = $service->get(1);

        $this->assertInstanceOf(ServiceResult::class, $result);
        $this->assertTrue($result->success);
        $this->assertEquals(null, $result->message);
        $this->assertInstanceOf(ExperienceResource::class, $result->data);
        $this->assertInstanceOf(Experience::class, $result->data->resource);
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
        $mock = Mockery::mock(ExperienceResource::class);
        $mock->shouldReceive('toArray')->andReturn($this->defaultSuccessReturn);
        $this->app->instance(ExperienceResource::class, $mock);

        $service = app(ExpService::class);
        $result  = $service->create(
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
        // Resource mock.
        $mock = Mockery::mock(ExperienceResource::class);
        $mock->shouldReceive('toArray')->andReturn($this->defaultSuccessReturn);
        $this->app->instance(ExperienceResource::class, $mock);

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
        $mock = Mockery::mock(Experience::class);
        $mock->shouldReceive('findOrFail')->andReturn(new Experience());
        $mock->shouldReceive('delete')->andReturn(true);
        $this->app->instance(Experience::class, $mock);

        // Resource mock.
        $mock = Mockery::mock(ExperienceResource::class);
        $mock->shouldReceive('toArray')->andReturn($this->defaultSuccessReturn);
        $this->app->instance(ExperienceResource::class, $mock);

        $service = app(ExpService::class);
        $result  = $service->suspend($this->userInstance->id);

        $this->assertInstanceOf(ServiceResult::class, $result);
        $this->assertTrue($result->success);
        $this->assertEquals(null, $result->message);
        $this->assertEquals(null, $result->data);
    }
}
