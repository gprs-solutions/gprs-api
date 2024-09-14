<?php

namespace Tests\Unit\Controllers\AuthController;

use App\Http\Controllers\AuthController;
use App\Http\Services\AuthService;
use Illuminate\Http\Response;
use Mockery;
use Tests\Unit\Controllers\BaseController;

class AuthControllerExceptionTest extends BaseController
{
    /**
     * Test if the in case of failures the system behaves as expected.
     *
     * @return void
     */
    public function testLoginBadRequest()
    {
        $this->request->merge(
            [
                'email'    => 'invalid-email',
                'password' => ['invalid data type for password'],
            ]
        );

        $controller = app(AuthController::class);
        $result     = $controller->login($this->request);
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
    public function testLoginUnauthorized()
    {
        $mock = Mockery::mock(AuthService::class);
        $mock->shouldReceive('login')->andReturn($this->defaultFailureReturn);
        $this->app->instance(AuthService::class, $mock);

        $this->request->merge(
            [
                'email'    => $this->userInstance->email,
                'password' => 'invalid-password',
            ]
        );

        $controller = app(AuthController::class);
        $result     = $controller->login($this->request);

        $this->assertEquals(Response::HTTP_UNAUTHORIZED, $result->getStatusCode());
        $content = json_decode($result->getContent());
        $this->assertEquals(Response::HTTP_UNAUTHORIZED, $content->status);
        $this->assertFalse($content->success);
        $this->assertEquals(__('messages.failedOperation'), $content->message);
    }
}
