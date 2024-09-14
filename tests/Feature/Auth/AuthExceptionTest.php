<?php

namespace Tests\Feature\Auth;

use Illuminate\Http\Response;
use Tests\Feature\BaseFeatureTestClass;

class AuthExceptionTest extends BaseFeatureTestClass
{
    /**
     * Test if the in case of failures the system behaves as expected.
     *
     * @return void
     */
    public function testInvalidLogin()
    {
        $result = $this->post(
            $this->baseUrl . '/login',
            ['email' => 'INVALID']
        );
        $result->assertStatus(Response::HTTP_BAD_REQUEST);
        $result->assertJsonStructure(['success', 'status', 'message', 'data']);
        $content = $result->decodeResponseJson();
        $this->assertFalse($content['success']);
        $this->assertSame(Response::HTTP_BAD_REQUEST, $content['status']);
        $this->assertNotEmpty($content['message']);
        $this->assertEmpty($content['data']);
    }

    /**
     * Test if the in case of failures the system behaves as expected.
     *
     * @return void
     */
    public function testLoginMissingToken()
    {
        $result = $this->post(
            $this->baseUrl . '/user/',
        );
        $result->assertStatus(Response::HTTP_UNAUTHORIZED);
        $result->assertJsonStructure(['success', 'status', 'message', 'data']);
        $content = $result->decodeResponseJson();
        $this->assertFalse($content['success']);
        $this->assertSame(Response::HTTP_UNAUTHORIZED, $content['status']);
        $this->assertSame(__('messages.missingJWTToken'), $content['message']);
        $this->assertEmpty($content['data']);
    }

    /**
     * Test if the in case of failures the system behaves as expected.
     *
     * @return void
     */
    public function testLoginInvalidToken()
    {
        $token  = 'invalid';
        $result = $this->post(
            $this->baseUrl . '/user/',
            [],
            [
                'Authorization' => 'Bearer ' . $token,
            ]
        );
        $result->assertStatus(Response::HTTP_UNAUTHORIZED);
        $result->assertJsonStructure(['success', 'status', 'message', 'data']);
        $content = $result->decodeResponseJson();
        $this->assertFalse($content['success']);
        $this->assertSame(Response::HTTP_UNAUTHORIZED, $content['status']);
        $this->assertSame(__('messages.unauthorized'), $content['message']);
        $this->assertEmpty($content['data']);
    }
}
