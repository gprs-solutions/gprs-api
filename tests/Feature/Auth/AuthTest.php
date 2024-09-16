<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Response;
use Tests\Feature\BaseFeatureTestClass;

class AuthTest extends BaseFeatureTestClass
{
    use DatabaseTransactions;

    /**
     * Test if the "happy path" works as expected.
     *
     * @return void
     */
    public function testLogin()
    {
        $result = $this->post(
            $this->baseUrl . '/login',
            [
                'email'    => $this->userInstance->email,
                'password' => 'P4$$w0rd',
            ]
        );
        $result->assertStatus(Response::HTTP_OK);
        $result->assertJsonStructure(['success', 'status', 'message', 'data']);
        $content = $result->decodeResponseJson();
        $this->assertTrue($content['success']);
        $this->assertSame(Response::HTTP_OK, $content['status']);
        $this->assertSame(__('messages.successfulOperation'), $content['message']);
        $this->assertNotEmpty($content['data'][0]['token']);
        $this->assertIsString($content['data'][0]['token']);
    }
}
