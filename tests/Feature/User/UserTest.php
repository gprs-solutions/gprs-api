<?php

namespace Tests\Feature\User;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Tests\Feature\BaseFeatureTestClass;

class UserTest extends BaseFeatureTestClass
{
    use DatabaseTransactions;

    /**
     * Test if the "happy path" works as expected.
     *
     * @return void
     */
    public function testGet()
    {
        $result = $this->get(
            $this->baseUrl . '/user/' . $this->userInstance->id,
            [
                'Authorization' => 'Bearer ' . $this->token,
            ]
        );
        $result->assertStatus(Response::HTTP_OK);
        $result->assertJsonStructure(['success', 'status', 'message', 'data']);
        $content = $result->decodeResponseJson();
        $this->assertTrue($content['success']);
        $this->assertSame(Response::HTTP_OK, $content['status']);
        $this->assertSame(__('messages.successfulOperation'), $content['message']);
        $this->assertNotEmpty($content['data'][0]);
        $data = $content['data'][0];
        $this->assertSame($this->userInstance->id, $data['id']);
        $this->assertSame($this->userInstance->name, $data['name']);
        $this->assertSame($this->userInstance->email, $data['email']);
        $this->assertIsArray($data['roles']);
    }

    /**
     * Test if the "happy path" works as expected.
     *
     * @return void
     */
    public function testCreate()
    {
        $result = $this->post(
            $this->baseUrl . '/user/',
            [
                'name'            => 'John Doe',
                'email'           => 'johndoe@gprs.com',
                'password'        => 'P4$$w0rd',
                'passwordConfirm' => 'P4$$w0rd',
                'roles'           => ['GPRS_SUPER_USR'],
            ],
            [
                'Authorization' => 'Bearer ' . $this->token,
            ]
        );
        $result->assertStatus(Response::HTTP_OK);
        $result->assertJsonStructure(['success', 'status', 'message', 'data']);
        $content = $result->decodeResponseJson();
        $this->assertTrue($content['success']);
        $this->assertSame(Response::HTTP_OK, $content['status']);
        $this->assertSame(__('messages.successfulOperation'), $content['message']);
        $this->assertEmpty($content['data']);
        $this->assertDatabaseHas(
            'users',
            [
                'name'  => 'John Doe',
                'email' => 'johndoe@gprs.com',
            ]
        );
        $user = User::where('email', 'johndoe@gprs.com')->first();
        $this->assertTrue(Hash::check('P4$$w0rd', $user->password));
        // One for the default super usr and one for the newly created one.
        $this->assertDatabaseCount('user_roles', 2);
    }

    /**
     * Test if the "happy path" works as expected.
     *
     * @return void
     */
    public function testUpdate()
    {
        $result = $this->patch(
            $this->baseUrl . '/user/' . $this->userInstance->id,
            [
                'name'            => 'John Doe',
                'email'           => 'johndoe@gprs.com',
                'password'        => 'P4$$w0rd2',
                'passwordConfirm' => 'P4$$w0rd2',
                'roles'           => [],
            ],
            [
                'Authorization' => 'Bearer ' . $this->token,
            ]
        );
        $result->assertStatus(Response::HTTP_OK);
        $result->assertJsonStructure(['success', 'status', 'message', 'data']);
        $content = $result->decodeResponseJson();
        $this->assertTrue($content['success']);
        $this->assertSame(Response::HTTP_OK, $content['status']);
        $this->assertSame(__('messages.successfulOperation'), $content['message']);
        $this->assertEmpty($content['data']);
        $this->assertDatabaseHas(
            'users',
            [
                'name'  => 'John Doe',
                'email' => 'johndoe@gprs.com',
            ]
        );
        $user = User::where('email', 'johndoe@gprs.com')->first();
        $this->assertTrue(Hash::check('P4$$w0rd2', $user->password));
        $this->assertDatabaseCount('user_roles', 1);
    }

    /**
     * Test if the "happy path" works as expected.
     *
     * @return void
     */
    public function testSuspend()
    {
        $result = $this->delete(
            $this->baseUrl . '/user/' . $this->userInstance->id,
            [],
            [
                'Authorization' => 'Bearer ' . $this->token,
            ]
        );
        $result->assertStatus(Response::HTTP_OK);
        $result->assertJsonStructure(['success', 'status', 'message', 'data']);
        $content = $result->decodeResponseJson();
        $this->assertTrue($content['success']);
        $this->assertSame(Response::HTTP_OK, $content['status']);
        $this->assertSame(__('messages.successfulOperation'), $content['message']);
        $this->assertEmpty($content['data']);
        $this->assertDatabaseHas(
            'users',
            [
                'name'  => $this->userInstance->name,
                'email' => $this->userInstance->email,
            ]
        );
        $user = User::withTrashed()->where('email', $this->userInstance->email)->first();
        $this->assertTrue(Hash::check('P4$$w0rd', $user->password));
        $this->assertNotEmpty($user->deleted_at);
        $this->assertDatabaseCount('user_roles', 1);
    }
}
