<?php

namespace Tests\Unit\Controllers\SkillController;

use App\Http\Controllers\SkillController;
use App\Http\Services\SkillService;
use Illuminate\Http\Response;
use Mockery;
use Tests\Unit\Controllers\BaseController;

class SkillControllerExceptionTest extends BaseController
{
    /**
     * Test if the in case of failures the system behaves as expected.
     *
     * @return void
     */
    public function testGetValidationError()
    {
        $this->request->merge(
            ['id' => -1]
        );

        $controller = app(SkillController::class);
        $result     = $controller->get($this->request);

        $this->assertEquals(Response::HTTP_BAD_REQUEST, $result->getStatusCode());
        $content = json_decode($result->getContent());
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $content->status);
        $this->assertFalse($content->success);
        $this->assertCount(1, json_decode($content->message, true));
    }

    /**
     * Test if the in case of failures the system behaves as expected.
     *
     * @return void
     */
    public function testGetBadRequest()
    {
        $mock = Mockery::mock(SkillService::class);
        $mock->shouldReceive('get')->andReturn($this->defaultFailureReturn);
        $this->app->instance(SkillService::class, $mock);

        $this->request->merge(
            ['id' => 1]
        );

        $controller = app(SkillController::class);
        $result     = $controller->get($this->request);

        $this->assertEquals(Response::HTTP_BAD_REQUEST, $result->getStatusCode());
        $content = json_decode($result->getContent());
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $content->status);
        $this->assertFalse($content->success);
        $this->assertIsString($content->message);
    }

    /**
     * Test if the in case of failures the system behaves as expected.
     *
     * @return void
     */
    public function testCreateValidationError()
    {
        $this->request->merge(
            []
        );

        $controller = app(SkillController::class);
        $result     = $controller->create($this->request);

        $this->assertEquals(Response::HTTP_BAD_REQUEST, $result->getStatusCode());
        $content = json_decode($result->getContent());
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $content->status);
        $this->assertFalse($content->success);
        $this->assertCount(1, json_decode($content->message, true));
    }

    /**
     * Test if the in case of failures the system behaves as expected.
     *
     * @return void
     */
    public function testCreateBadRequest()
    {
        $mock = Mockery::mock(SkillService::class);
        $mock->shouldReceive('create')->andReturn($this->defaultFailureReturn);
        $this->app->instance(SkillService::class, $mock);

        $this->request->merge(
            []
        );

        $controller = app(SkillController::class);
        $result     = $controller->create($this->request);

        $this->assertEquals(Response::HTTP_BAD_REQUEST, $result->getStatusCode());
        $content = json_decode($result->getContent());
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $content->status);
        $this->assertFalse($content->success);
        $this->assertIsString($content->message);
    }

    /**
     * Test if the in case of failures the system behaves as expected.
     *
     * @return void
     */
    public function testUpdateValidationError()
    {
        $this->request->merge(
            []
        );

        $controller = app(SkillController::class);
        $result     = $controller->update($this->request);

        $this->assertEquals(Response::HTTP_BAD_REQUEST, $result->getStatusCode());
        $content = json_decode($result->getContent());
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $content->status);
        $this->assertFalse($content->success);
        $this->assertCount(2, json_decode($content->message, true));
    }

    /**
     * Test if the in case of failures the system behaves as expected.
     *
     * @return void
     */
    public function testUpdateBadRequest()
    {
        $mock = Mockery::mock(SkillService::class);
        $mock->shouldReceive('update')->andReturn($this->defaultFailureReturn);
        $this->app->instance(SkillService::class, $mock);

        $this->request->merge(
            []
        );

        $controller = app(SkillController::class);
        $result     = $controller->update($this->request);

        $this->assertEquals(Response::HTTP_BAD_REQUEST, $result->getStatusCode());
        $content = json_decode($result->getContent());
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $content->status);
        $this->assertFalse($content->success);
        $this->assertIsString($content->message);
    }

    /**
     * Test if the in case of failures the system behaves as expected.
     *
     * @return void
     */
    public function testSuspendValidationError()
    {
        $this->request->merge(
            [
                'id' => -1,
            ]
        );

        $controller = app(SkillController::class);
        $result     = $controller->suspend($this->request);

        $this->assertEquals(Response::HTTP_BAD_REQUEST, $result->getStatusCode());
        $content = json_decode($result->getContent());
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $content->status);
        $this->assertFalse($content->success);
        $this->assertCount(1, json_decode($content->message, true));
    }

    /**
     * Test if the in case of failures the system behaves as expected.
     *
     * @return void
     */
    public function testSuspendBadRequest()
    {
        $mock = Mockery::mock(SkillService::class);
        $mock->shouldReceive('suspend')->andReturn($this->defaultFailureReturn);
        $this->app->instance(SkillService::class, $mock);

        $this->request->merge(
            ['id' => 1]
        );

        $controller = app(SkillController::class);
        $result     = $controller->suspend($this->request);

        $this->assertEquals(Response::HTTP_BAD_REQUEST, $result->getStatusCode());
        $content = json_decode($result->getContent());
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $content->status);
        $this->assertFalse($content->success);
        $this->assertIsString($content->message);
    }
}
