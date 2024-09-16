<?php

namespace Tests\Feature\Cert;

use App\Models\Certification;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Response;
use Tests\Feature\BaseFeatureTestClass;

class CertTest extends BaseFeatureTestClass
{
    use DatabaseTransactions;

    /**
     * Test if the "happy path" works as expected.
     *
     * @return void
     */
    public function testGet()
    {
        $certification = Certification::factory()->create();
        $result        = $this->get(
            $this->baseUrl . '/cert/' . $certification->id,
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
        $this->assertSame($certification->id, $data['id']);
        $this->assertSame($certification->start . ' 00:00:00', $data['start']);
        $this->assertSame($certification->end . ' 00:00:00', $data['end']);
        $this->assertSame($certification->image, $data['image']);
        $this->assertSame($certification->link, $data['link']);
    }

    /**
     * Test if the "happy path" works as expected.
     *
     * @return void
     */
    public function testCreate()
    {
        $result = $this->post(
            $this->baseUrl . '/cert/',
            [
                'link'         => 'http://image',
                'image'        => 'http://image',
                'start'        => '2020-10-07',
                'end'          => '2022-10-07',
                'descriptions' => [
                    [
                        'name'        => 'teste',
                        'lang'        => 'PT',
                        'description' => 'teste',
                    ],
                ],
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
            'certifications',
            [
                'image' => 'http://image',
                'link'  => 'http://image',
                'start' => '2020-10-07',
                'end'   => '2022-10-07',
            ]
        );
        $this->assertDatabaseHas(
            'descriptions',
            [
                'name'        => 'teste',
                'lang'        => 'PT',
                'description' => 'teste',
            ]
        );
    }

    /**
     * Test if the "happy path" works as expected.
     *
     * @return void
     */
    public function testUpdate()
    {
        $certification = Certification::factory()->create();
        $result        = $this->patch(
            $this->baseUrl . '/cert/' . $certification->id,
            [
                'image'        => 'http://image2',
                'link'         => 'http://image2',
                'start'        => '2020-10-07',
                'end'          => '2022-10-07',
                'descriptions' => [],
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
            'certifications',
            [
                'image' => 'http://image2',
                'link'  => 'http://image2',
                'start' => '2020-10-07',
                'end'   => '2022-10-07',
            ]
        );
    }

    /**
     * Test if the "happy path" works as expected.
     *
     * @return void
     */
    public function testSuspend()
    {
        $certification = Certification::factory()->create();
        $result        = $this->delete(
            $this->baseUrl . '/cert/' . $certification->id,
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
            'certifications',
            [
                'image' => $certification->image,
                'link'  => $certification->link,
                'start' => $certification->start,
                'end'   => $certification->end,
            ]
        );
    }
}
