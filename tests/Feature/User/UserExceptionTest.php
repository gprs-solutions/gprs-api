<?php

namespace Tests\Feature\User;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Tests\Feature\BaseFeatureTestClass;

class UserExceptionTest extends BaseFeatureTestClass
{
    use DatabaseTransactions;

    /**
     * Test if the in case of failures the system behaves as expected.
     *
     * @return void
     */
    public function testGetInvalidId()
    {
        $id = 0;
        // Invalid.
        $result = $this->get(
            $this->baseUrl . '/user/' . $id,
            [
                'Authorization' => 'Bearer ' . $this->token,
            ]
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
    public function testCreateInvalidInputs()
    {
        $result = $this->post(
            $this->baseUrl . '/user/',
            [
                'name'            => 1234,
                'email'           => 'INVALID_EMAIL',
                'password'        => 'P4$$w0rd',
                'passwordConfirm' => 'P4$$w0rdNotMatch',
                'roles'           => ['UNDEFINED_ROLE'],
            ],
            [
                'Authorization' => 'Bearer ' . $this->token,
            ]
        );
        $result->assertStatus(Response::HTTP_BAD_REQUEST);
        $result->assertJsonStructure(['success', 'status', 'message', 'data']);
        $content = $result->decodeResponseJson();
        $this->assertFalse($content['success']);
        $this->assertSame(Response::HTTP_BAD_REQUEST, $content['status']);
        $this->assertNotEmpty($content['message']);
        $this->assertEmpty($content['data']);
        $this->assertDatabaseMissing(
            'users',
            [
                'name'  => 1234,
                'email' => 'INVALID_EMAIL',
            ]
        );
    }

    /**
     * Test if the in case of failures the system behaves as expected.
     *
     * @return void
     */
    public function testUpdateInvalidInputs()
    {
        $id     = 0;
        $result = $this->patch(
            $this->baseUrl . '/user/' . $id,
            [
                'name'            => 1234,
                'email'           => 'INVALID_EMAIL',
                'password'        => 'P4$$w0rd',
                'passwordConfirm' => 'P4$$w0rdNotMatch',
                'roles'           => ['UNDEFINED_ROLE'],
            ],
            [
                'Authorization' => 'Bearer ' . $this->token,
            ]
        );
        $result->assertStatus(Response::HTTP_BAD_REQUEST);
        $result->assertJsonStructure(['success', 'status', 'message', 'data']);
        $content = $result->decodeResponseJson();
        $this->assertFalse($content['success']);
        $this->assertSame(Response::HTTP_BAD_REQUEST, $content['status']);
        $this->assertNotEmpty($content['message']);
        $this->assertEmpty($content['data']);
        $this->assertDatabaseMissing(
            'users',
            [
                'name'  => 1234,
                'email' => 'INVALID_EMAIL',
            ]
        );
    }

    /**
     * Test if the in case of failures the system behaves as expected.
     *
     * @return void
     */
    public function testSuspendInvalidId()
    {
        $id     = 0;
        $result = $this->delete(
            $this->baseUrl . '/user/' . $id,
            [],
            [
                'Authorization' => 'Bearer ' . $this->token,
            ]
        );
        $result->assertStatus(Response::HTTP_BAD_REQUEST);
        $result->assertJsonStructure(['success', 'status', 'message', 'data']);
        $content = $result->decodeResponseJson();
        $this->assertFalse($content['success']);
        $this->assertSame(Response::HTTP_BAD_REQUEST, $content['status']);
        $this->assertNotEmpty($content['message']);
        $this->assertEmpty($content['data']);
        $this->assertDatabaseHas(
            'users',
            [
                'name'       => $this->userInstance->name,
                'email'      => $this->userInstance->email,
                'deleted_at' => null,
            ]
        );
    }
}
