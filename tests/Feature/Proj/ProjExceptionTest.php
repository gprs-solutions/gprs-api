<?php

namespace Tests\Feature\Proj;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Response;
use Tests\Feature\BaseFeatureTestClass;

class ProjExceptionTest extends BaseFeatureTestClass
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
            $this->baseUrl . '/proj/' . $id,
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
            $this->baseUrl . '/proj/',
            [
                'image'        => 1234,
                'start'        => 'invalid',
                'end'          => 'invalid',
                'descriptions' => [
                    [
                        'name'        => 1234,
                        'lang'        => 'PT_INVALID_NUMBER',
                        'description' => 1234,
                    ],
                ],
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
            'projects',
            ['image' => 1234]
        );
        $this->assertDatabaseMissing(
            'descriptions',
            ['name' => 1234]
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
            $this->baseUrl . '/proj/' . $id,
            [
                'id'           => $id,
                'image'        => 1234,
                'start'        => 'invalid',
                'end'          => 'invalid',
                'descriptions' => [
                    [
                        'id'          => $id,
                        'name'        => 1234,
                        'lang'        => 'PT_INVALID_NUMBER',
                        'description' => 1234,
                    ],
                ],
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
            'projects',
            ['image' => 1234]
        );
        $this->assertDatabaseMissing(
            'descriptions',
            ['name' => 1234]
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
            $this->baseUrl . '/proj/' . $id,
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
    }
}
