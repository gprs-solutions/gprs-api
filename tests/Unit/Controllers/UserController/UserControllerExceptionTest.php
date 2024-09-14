<?php

namespace Tests\Unit\Controllers\UserController;

use App\Http\Controllers\UserController;
use App\Http\Services\UserService;
use Illuminate\Http\Response;
use Mockery;
use Tests\Unit\Controllers\BaseController;

class UserControllerExceptionTest extends BaseController
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

        $controller = app(UserController::class);
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
        $mock = Mockery::mock(UserService::class);
        $mock->shouldReceive('get')->andReturn($this->defaultFailureReturn);
        $this->app->instance(UserService::class, $mock);

        $this->request->merge(
            [
                'id' => $this->userInstance->id,
            ]
        );

        $controller = app(UserController::class);
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
            [
                'name'            => 1234,
                'email'           => 'invalid',
                'password'        => 'small',
                'passwordConfirm' => 'P4$$uu0Rd',
                'roles'           => ['invalid-role'],
            ]
        );

        $controller = app(UserController::class);
        $result     = $controller->create($this->request);

        $this->assertEquals(Response::HTTP_BAD_REQUEST, $result->getStatusCode());
        $content = json_decode($result->getContent());
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $content->status);
        $this->assertFalse($content->success);
        $this->assertCount(4, json_decode($content->message, true));
    }

    /**
     * Test if the in case of failures the system behaves as expected.
     *
     * @return void
     */
    public function testCreateBadRequest()
    {
        $mock = Mockery::mock(UserService::class);
        $mock->shouldReceive('create')->andReturn($this->defaultFailureReturn);
        $this->app->instance(UserService::class, $mock);

        $this->request->merge(
            [
                'name'            => 'John Doe',
                'email'           => 'newemail@gprs.com',
                'profile_img'     => 'placeholder',
                'password'        => 'P4$$uu0Rd',
                'passwordConfirm' => 'P4$$uu0Rd',
                'roles'           => ['GPRS_SUPER_USR'],
            ]
        );

        $controller = app(UserController::class);
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
            [
                'id'              => -1,
                'name'            => 1234,
                'email'           => 'invalid',
                'password'        => 'small',
                'passwordConfirm' => 'P4$$uu0Rd',
                'roles'           => ['invalid-role'],
            ]
        );

        $controller = app(UserController::class);
        $result     = $controller->update($this->request);

        $this->assertEquals(Response::HTTP_BAD_REQUEST, $result->getStatusCode());
        $content = json_decode($result->getContent());
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $content->status);
        $this->assertFalse($content->success);
        $this->assertCount(5, json_decode($content->message, true));
    }

    /**
     * Test if the in case of failures the system behaves as expected.
     *
     * @return void
     */
    public function testUpdateBadRequest()
    {
        $mock = Mockery::mock(UserService::class);
        $mock->shouldReceive('update')->andReturn($this->defaultFailureReturn);
        $this->app->instance(UserService::class, $mock);

        $this->request->merge(
            [
                'id'              => $this->userInstance->id,
                'name'            => 'John Doe',
                'email'           => 'newemail@gprs.com',
                'profile_img'     => 'placeholder',
                'password'        => 'P4$$uu0Rd',
                'passwordConfirm' => 'P4$$uu0Rd',
                'roles'           => ['GPRS_SUPER_USR'],
            ]
        );

        $controller = app(UserController::class);
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

        $controller = app(UserController::class);
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
        $mock = Mockery::mock(UserService::class);
        $mock->shouldReceive('suspend')->andReturn($this->defaultFailureReturn);
        $this->app->instance(UserService::class, $mock);

        $this->request->merge(
            [
                'id' => $this->userInstance->id,
            ]
        );

        $controller = app(UserController::class);
        $result     = $controller->suspend($this->request);

        $this->assertEquals(Response::HTTP_BAD_REQUEST, $result->getStatusCode());
        $content = json_decode($result->getContent());
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $content->status);
        $this->assertFalse($content->success);
        $this->assertIsString($content->message);
    }
}
