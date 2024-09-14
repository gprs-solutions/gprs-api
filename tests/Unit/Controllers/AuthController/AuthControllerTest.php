<?php

namespace Tests\Unit\Controllers\AuthController;

use App\Http\Controllers\AuthController;
use App\Http\Services\AuthService;
use Illuminate\Http\Response;
use Mockery;
use Tests\Unit\Controllers\BaseController;

class AuthControllerTest extends BaseController
{
    /**
     * Test if the "happy path" works as expected.
     *
     * @return void
     */
    public function testLogin()
    {
        $mock = Mockery::mock(AuthService::class);
        $mock->shouldReceive('login')->andReturn($this->defaultSuccessReturn);
        $this->app->instance(AuthService::class, $mock);

        $this->request->merge(
            [
                'email'    => $this->userInstance->email,
                'password' => 'P4$$ww0Rd',
            ]
        );

        $controller = app(AuthController::class);
        $result     = $controller->login($this->request);
        $this->assertEquals(Response::HTTP_OK, $result->getStatusCode());

        $content = json_decode($result->getContent());
        $this->assertTrue($content->success);
        $this->assertEquals(Response::HTTP_OK, $content->status);
        $this->assertEquals(__('messages.successfulOperation'), $content->message);
        $this->assertSame(['success' => true], (array) reset($content->data));
    }
}
