<?php

namespace Tests\Unit\Middlewares\Authenticate;

use App\Http\Middleware\Authenticate;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Mockery;
use Tests\Unit\Middlewares\BaseMiddleware;

class AuthenticateExceptionTest extends BaseMiddleware
{
    /**
     * Test if the in case of failures the system behaves as expected.
     *
     * @return void
     */
    public function testHandleNoTokenSent()
    {
        // Removing the token from the request.
        $this->request->headers->set('Authorization', null);

        // Model Mock.
        $mock = Mockery::mock(User::class);
        $mock->shouldReceive('findOrFail')->andReturn(new User());
        $this->app->instance(User::class, $mock);

        $middleware = app(Authenticate::class);
        $result     = $middleware->handle($this->request, $this->next);
        $this->assertEquals(Response::HTTP_UNAUTHORIZED, $result->getStatusCode());

        $content = json_decode($result->getContent());
        $this->assertEquals(Response::HTTP_UNAUTHORIZED, $content->status);
        $this->assertFalse($content->success);
        $this->assertEquals(__('messages.missingJWTToken'), $content->message);
        $this->assertEmpty($content->data);
    }

    /**
     * Test if the in case of failures the system behaves as expected.
     *
     * @return void
     */
    public function testHandleExpiredToken()
    {
        // Setting an expired token in the request.
        $token = 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOjQsImlhdCI6MTcyNDYzMzAxMSwiZXhwIjoxNzI0NjM2NjExfQ.R9oV1ILAaot9cGBN2pAU1cickTyqnB3yOnr-IOLoi-U';
        $this->request->headers->set('Authorization', $token);

        // Model Mock.
        $mock = Mockery::mock(User::class);
        $mock->shouldReceive('findOrFail')->andReturn(new User());
        $this->app->instance(User::class, $mock);

        $middleware = app(Authenticate::class);
        $result     = $middleware->handle($this->request, $this->next);
        $this->assertEquals(Response::HTTP_UNAUTHORIZED, $result->getStatusCode());

        $content = json_decode($result->getContent());
        $this->assertEquals(Response::HTTP_UNAUTHORIZED, $content->status);
        $this->assertFalse($content->success);
        $this->assertEquals(__('messages.tokenExpired'), $content->message);
        $this->assertEmpty($content->data);
    }

    /**
     * Test if the in case of failures the system behaves as expected.
     *
     * @return void
     */
    public function testHandleGenericUnauthorizedError()
    {
        // Model Mock.
        $mock = Mockery::mock(Request::class)->makePartial();
        $mock->shouldReceive('bearerToken')->andThrow(new Exception());
        $this->app->instance(Request::class, $mock);

        $middleware = app(Authenticate::class);
        $result     = $middleware->handle($mock, $this->next);
        $this->assertEquals(Response::HTTP_UNAUTHORIZED, $result->getStatusCode());

        $content = json_decode($result->getContent());
        $this->assertEquals(Response::HTTP_UNAUTHORIZED, $content->status);
        $this->assertFalse($content->success);
        $this->assertEquals(__('messages.unauthorized'), $content->message);
        $this->assertEmpty($content->data);
    }
}
