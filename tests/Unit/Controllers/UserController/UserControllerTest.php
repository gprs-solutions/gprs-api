<?php

namespace Tests\Unit\Controllers\UserController;

use App\Http\Controllers\UserController;
use App\Http\Services\UserService;
use Illuminate\Http\Response;
use Mockery;
use Tests\Unit\Controllers\BaseController;

class UserControllerTest extends BaseController
{
    /**
     * Test if the "happy path" works as expected.
     *
     * @return void
     */
    public function testGet()
    {
        $mock = Mockery::mock(UserService::class);
        $mock->shouldReceive('get')->andReturn($this->defaultSuccessReturn);
        $this->app->instance(UserService::class, $mock);

        $this->request->merge(
            ['id' => $this->userInstance->id]
        );

        $controller = app(UserController::class);
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
        $mock = Mockery::mock(UserService::class);
        $mock->shouldReceive('create')->andReturn($this->defaultSuccessReturn);
        $this->app->instance(UserService::class, $mock);

        $this->request->merge(
            [
                'name'            => 'John Doe',
                'email'           => 'johndoe@gprs.com',
                'profile_img'     => 'placeholder',
                'password'        => 'P4$$uu0Rd',
                'passwordConfirm' => 'P4$$uu0Rd',
                'roles'           => ['GPRS_SUPER_USR'],
            ]
        );

        $controller = app(UserController::class);
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
        $mock = Mockery::mock(UserService::class);
        $mock->shouldReceive('update')->andReturn($this->defaultSuccessReturn);
        $this->app->instance(UserService::class, $mock);

        $this->request->merge(
            [
                'id'              => $this->userInstance->id,
                'name'            => 'John Doe',
                'email'           => 'johndoe@gprs.com',
                'profile_img'     => 'placeholder',
                'password'        => 'P4$$uu0Rd',
                'passwordConfirm' => 'P4$$uu0Rd',
                'roles'           => [],
            ]
        );

        $controller = app(UserController::class);
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
        $mock = Mockery::mock(UserService::class);
        $mock->shouldReceive('suspend')->andReturn($this->defaultSuccessReturn);
        $this->app->instance(UserService::class, $mock);

        $this->request->merge(
            ['id' => $this->userInstance->id]
        );

        $controller = app(UserController::class);
        $result     = $controller->suspend($this->request);
        $this->assertEquals(Response::HTTP_OK, $result->getStatusCode());

        $content = json_decode($result->getContent());
        $this->assertTrue($content->success);
        $this->assertEquals(Response::HTTP_OK, $content->status);
        $this->assertEquals(__('messages.successfulOperation'), $content->message);
        $this->assertEmpty($content->data);
    }
}
