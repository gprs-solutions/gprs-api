<?php

namespace Tests\Unit\Controllers\SkillController;

use App\Http\Controllers\SkillController;
use App\Http\Services\SkillService;
use Illuminate\Http\Response;
use Mockery;
use Tests\Unit\Controllers\BaseController;

class SkillControllerTest extends BaseController
{
    /**
     * Test if the "happy path" works as expected.
     *
     * @return void
     */
    public function testGet()
    {
        $mock = Mockery::mock(SkillService::class);
        $mock->shouldReceive('get')->andReturn($this->defaultSuccessReturn);
        $this->app->instance(SkillService::class, $mock);

        $this->request->merge(
            ['id' => 1]
        );

        $controller = app(SkillController::class);
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
        $mock = Mockery::mock(SkillService::class);
        $mock->shouldReceive('create')->andReturn($this->defaultSuccessReturn);
        $this->app->instance(SkillService::class, $mock);

        $this->request->merge(
            ['image' => 'http://image']
        );

        $controller = app(SkillController::class);
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
        $mock = Mockery::mock(SkillService::class);
        $mock->shouldReceive('update')->andReturn($this->defaultSuccessReturn);
        $this->app->instance(SkillService::class, $mock);

        $this->request->merge(
            [
                'id'    => 1,
                'image' => 'http://image',
            ]
        );

        $controller = app(SkillController::class);
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
        $mock = Mockery::mock(SkillService::class);
        $mock->shouldReceive('suspend')->andReturn($this->defaultSuccessReturn);
        $this->app->instance(SkillService::class, $mock);

        $this->request->merge(
            ['id' => 1]
        );

        $controller = app(SkillController::class);
        $result     = $controller->suspend($this->request);
        $this->assertEquals(Response::HTTP_OK, $result->getStatusCode());

        $content = json_decode($result->getContent());
        $this->assertTrue($content->success);
        $this->assertEquals(Response::HTTP_OK, $content->status);
        $this->assertEquals(__('messages.successfulOperation'), $content->message);
        $this->assertEmpty($content->data);
    }
}
