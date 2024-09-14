<?php

namespace Tests\Unit\Middlewares\Authorize;

use App\Http\Middleware\Authorize;
use App\Models\Permission;
use Illuminate\Http\Response;
use Tests\Unit\Middlewares\BaseMiddleware;

class AuthorizeTest extends BaseMiddleware
{
    /**
     * Test if the "happy path" works as expected.
     *
     * @return void
     */
    public function testHandle()
    {
        $middleware  = app(Authorize::class);
        $permissions = Permission::all()->pluck('code')->toArray();
        $result      = $middleware->handle($this->request, $this->next, ...$permissions);
        $this->assertEquals(Response::HTTP_OK, $result->getStatusCode());

        $content = json_decode($result->getContent());
        $this->assertTrue($content->success);
        $this->assertEquals(Response::HTTP_OK, $content->status);
        $this->assertEquals(__('messages.successfulOperation'), $content->message);
        $this->assertEmpty($content->data);
    }
}
