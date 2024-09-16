<?php

namespace Tests\Feature\Edu;

use App\Models\Education;
use Illuminate\Http\Response;
use Tests\Feature\BaseFeatureTestClass;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class EduTest extends BaseFeatureTestClass
{
    use DatabaseTransactions;

    /**
     * Test if the "happy path" works as expected.
     *
     * @return void
     */
    public function testGet()
    {
        $education = Education::factory()->create();
        $result    = $this->get(
            $this->baseUrl . '/edu/' . $education->id,
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
        $this->assertSame($education->id, $data['id']);
        $this->assertSame($education->start . ' 00:00:00', $data['start']);
        $this->assertSame($education->end . ' 00:00:00', $data['end']);
        $this->assertSame($education->image, $data['image']);
    }

    /**
     * Test if the "happy path" works as expected.
     *
     * @return void
     */
    public function testCreate()
    {
        $result = $this->post(
            $this->baseUrl . '/edu/',
            [
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
            'educations',
            [
                'image' => 'http://image',
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
        $education = Education::factory()->create();
        $result    = $this->patch(
            $this->baseUrl . '/edu/' . $education->id,
            [
                'image'        => 'http://image2',
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
            'educations',
            [
                'image' => 'http://image2',
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
        $education = Education::factory()->create();
        $result    = $this->delete(
            $this->baseUrl . '/edu/' . $education->id,
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
            'educations',
            [
                'image' => $education->image,
                'start' => $education->start,
                'end'   => $education->end,
            ]
        );
    }
}
