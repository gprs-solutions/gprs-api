<?php

namespace Tests\Unit\Middlewares\Authenticate;

use App\Http\Middleware\Authenticate;
use App\Models\User;
use Illuminate\Http\Response;
use Mockery;
use Tests\Unit\Middlewares\BaseMiddleware;

class AuthenticateTest extends BaseMiddleware
{
    /**
     * Test if the "happy path" works as expected.
     *
     * @return void
     */
    public function testHandle()
    {
        // Model Mock.
        $mock = Mockery::mock(User::class);
        $mock->shouldReceive('findOrFail')->andReturn(new User());
        $this->app->instance(User::class, $mock);

        $middleware = app(Authenticate::class);
        $result     = $middleware->handle($this->request, $this->next);
        $this->assertEquals(Response::HTTP_OK, $result->getStatusCode());

        $content = json_decode($result->getContent());
        $this->assertEquals(Response::HTTP_OK, $content->status);
        $this->assertTrue($content->success);
        $this->assertEquals(__('messages.successfulOperation'), $content->message);
        $this->assertEmpty($content->data);
    }
}
