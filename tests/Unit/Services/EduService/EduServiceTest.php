<?php

namespace Tests\Unit\Services\EduService;

use App\Http\Resources\EducationResource;
use App\Http\Services\ServiceResult;
use App\Http\Services\EduService;
use App\Models\Education;
use Mockery;
use Tests\Unit\Services\BaseService;

class EduServiceTest extends BaseService
{
    /**
     * Test if the "happy path" works as expected.
     *
     * @return void
     */
    public function testGet()
    {
        // Model Mock.
        $mock = Mockery::mock(Education::class);
        $mock->shouldReceive('findOrFail')->andReturn(new Education());
        $this->app->instance(Education::class, $mock);

        // Resource mock.
        $mock = Mockery::mock(EducationResource::class);
        $mock->shouldReceive('toArray')->andReturn($this->defaultSuccessReturn);
        $this->app->instance(EducationResource::class, $mock);

        $service = app(EduService::class);
        $result  = $service->get(1);

        $this->assertInstanceOf(ServiceResult::class, $result);
        $this->assertTrue($result->success);
        $this->assertEquals(null, $result->message);
        $this->assertInstanceOf(EducationResource::class, $result->data);
        $this->assertInstanceOf(Education::class, $result->data->resource);
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
        $mock = Mockery::mock(EducationResource::class);
        $mock->shouldReceive('toArray')->andReturn($this->defaultSuccessReturn);
        $this->app->instance(EducationResource::class, $mock);

        $service = app(EduService::class);
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
        $mock = Mockery::mock(EducationResource::class);
        $mock->shouldReceive('toArray')->andReturn($this->defaultSuccessReturn);
        $this->app->instance(EducationResource::class, $mock);

        $service = app(EduService::class);
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
        $mock = Mockery::mock(Education::class);
        $mock->shouldReceive('findOrFail')->andReturn(new Education());
        $mock->shouldReceive('delete')->andReturn(true);
        $this->app->instance(Education::class, $mock);

        // Resource mock.
        $mock = Mockery::mock(EducationResource::class);
        $mock->shouldReceive('toArray')->andReturn($this->defaultSuccessReturn);
        $this->app->instance(EducationResource::class, $mock);

        $service = app(EduService::class);
        $result  = $service->suspend($this->userInstance->id);

        $this->assertInstanceOf(ServiceResult::class, $result);
        $this->assertTrue($result->success);
        $this->assertEquals(null, $result->message);
        $this->assertEquals(null, $result->data);
    }
}
