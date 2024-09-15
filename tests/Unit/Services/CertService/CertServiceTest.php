<?php

namespace Tests\Unit\Services\CertService;

use App\Http\Resources\CertificationResource;
use App\Http\Services\ServiceResult;
use App\Http\Services\CertService;
use App\Models\Certification;
use Mockery;
use Tests\Unit\Services\BaseService;

class CertServiceTest extends BaseService
{
    /**
     * Test if the "happy path" works as expected.
     *
     * @return void
     */
    public function testGet()
    {
        // Model Mock.
        $mock = Mockery::mock(Certification::class);
        $mock->shouldReceive('findOrFail')->andReturn(new Certification());
        $this->app->instance(Certification::class, $mock);

        // Resource mock.
        $mock = Mockery::mock(CertificationResource::class);
        $mock->shouldReceive('toArray')->andReturn($this->defaultSuccessReturn);
        $this->app->instance(CertificationResource::class, $mock);

        $service = app(CertService::class);
        $result  = $service->get(1);

        $this->assertInstanceOf(ServiceResult::class, $result);
        $this->assertTrue($result->success);
        $this->assertEquals(null, $result->message);
        $this->assertInstanceOf(CertificationResource::class, $result->data);
        $this->assertInstanceOf(Certification::class, $result->data->resource);
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
        $mock = Mockery::mock(CertificationResource::class);
        $mock->shouldReceive('toArray')->andReturn($this->defaultSuccessReturn);
        $this->app->instance(CertificationResource::class, $mock);

        $service = app(CertService::class);
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
        // Resource mock.
        $mock = Mockery::mock(CertificationResource::class);
        $mock->shouldReceive('toArray')->andReturn($this->defaultSuccessReturn);
        $this->app->instance(CertificationResource::class, $mock);

        $service = app(CertService::class);
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
        $mock = Mockery::mock(Certification::class);
        $mock->shouldReceive('findOrFail')->andReturn(new Certification());
        $mock->shouldReceive('delete')->andReturn(true);
        $this->app->instance(Certification::class, $mock);

        // Resource mock.
        $mock = Mockery::mock(CertificationResource::class);
        $mock->shouldReceive('toArray')->andReturn($this->defaultSuccessReturn);
        $this->app->instance(CertificationResource::class, $mock);

        $service = app(CertService::class);
        $result  = $service->suspend($this->userInstance->id);

        $this->assertInstanceOf(ServiceResult::class, $result);
        $this->assertTrue($result->success);
        $this->assertEquals(null, $result->message);
        $this->assertEquals(null, $result->data);
    }
}
