<?php

namespace Tests\Unit\Controllers\ExpController;

use App\Http\Controllers\ExpController;
use App\Http\Services\ExpService;
use Illuminate\Http\Response;
use Mockery;
use Tests\Unit\Controllers\BaseController;

class ExpControllerTest extends BaseController
{
    /**
     * Test if the "happy path" works as expected.
     *
     * @return void
     */
    public function testList()
    {
        $mock = Mockery::mock(ExpService::class);
        $mock->shouldReceive('list')->andReturn($this->defaultSuccessReturn);
        $this->app->instance(ExpService::class, $mock);

        $this->request->merge(
            ['id' => 1]
        );

        $controller = app(ExpController::class);
        $result     = $controller->list($this->request);
        $this->assertEquals(Response::HTTP_OK, $result->getStatusCode());

        $content = json_decode($result->getContent());
        $this->assertEquals(Response::HTTP_OK, $content->status);
        $this->assertTrue($content->success);
        $this->assertEquals(__('messages.successfulOperation'), $content->message);
    }

    /**
     * Test if the "happy path" works as expected.
     *
     * @return void
     */
    public function testGet()
    {
        $mock = Mockery::mock(ExpService::class);
        $mock->shouldReceive('get')->andReturn($this->defaultSuccessReturn);
        $this->app->instance(ExpService::class, $mock);

        $this->request->merge(
            ['id' => 1]
        );

        $controller = app(ExpController::class);
        $result     = $controller->get($this->request);
        $this->assertEquals(Response::HTTP_OK, $result->getStatusCode());

        $content = json_decode($result->getContent());
        $this->assertEquals(Response::HTTP_OK, $content->status);
        $this->assertTrue($content->success);
        $this->assertEquals(__('messages.successfulOperation'), $content->message);
        $this->assertSame(['success' => true], (array) reset($content->data));
    }

    /**
     * Test if the "happy path" works as expected.
     *
     * @return void
     */
    public function testCreate()
    {
        $mock = Mockery::mock(ExpService::class);
        $mock->shouldReceive('create')->andReturn($this->defaultSuccessReturn);
        $this->app->instance(ExpService::class, $mock);

        $this->request->merge(
            [
                'image'        => 'http://image',
                'start'        => '2020-10-07',
                'end'          => '2022-10-07',
                'descriptions' => [
                    [
                        'id'          => 1,
                        'name'        => 'teste',
                        'lang'        => 'PT',
                        'description' => 'teste',
                    ],
                ],
            ]
        );

        $controller = app(ExpController::class);
        $result     = $controller->create($this->request);
        $this->assertEquals(Response::HTTP_OK, $result->getStatusCode());

        $content = json_decode($result->getContent());
        $this->assertTrue($content->success);
        $this->assertEquals(Response::HTTP_OK, $content->status);
        $this->assertEquals(__('messages.successfulOperation'), $content->message);
        $this->assertEmpty($content->data);
    }

    /**
     * Test if the "happy path" works as expected.
     *
     * @return void
     */
    public function testUpdate()
    {
        $mock = Mockery::mock(ExpService::class);
        $mock->shouldReceive('update')->andReturn($this->defaultSuccessReturn);
        $this->app->instance(ExpService::class, $mock);

        $this->request->merge(
            [
                'id'           => 1,
                'image'        => 'http://image',
                'start'        => '2020-10-07',
                'end'          => '2022-10-07',
                'descriptions' => [
                    [
                        'id'          => 1,
                        'name'        => 'teste',
                        'lang'        => 'PT',
                        'description' => 'teste',
                    ],
                ],
            ]
        );

        $controller = app(ExpController::class);
        $result     = $controller->update($this->request);
        $this->assertEquals(Response::HTTP_OK, $result->getStatusCode());

        $content = json_decode($result->getContent());
        $this->assertTrue($content->success);
        $this->assertEquals(Response::HTTP_OK, $content->status);
        $this->assertEquals(__('messages.successfulOperation'), $content->message);
        $this->assertEmpty($content->data);
    }

    /**
     * Test if the "happy path" works as expected.
     *
     * @return void
     */
    public function testSuspend()
    {
        $mock = Mockery::mock(ExpService::class);
        $mock->shouldReceive('suspend')->andReturn($this->defaultSuccessReturn);
        $this->app->instance(ExpService::class, $mock);

        $this->request->merge(
            ['id' => 1]
        );

        $controller = app(ExpController::class);
        $result     = $controller->suspend($this->request);
        $this->assertEquals(Response::HTTP_OK, $result->getStatusCode());

        $content = json_decode($result->getContent());
        $this->assertTrue($content->success);
        $this->assertEquals(Response::HTTP_OK, $content->status);
        $this->assertEquals(__('messages.successfulOperation'), $content->message);
        $this->assertEmpty($content->data);
    }
}
