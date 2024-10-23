<?php

namespace Tests\Feature\Exp;

use App\Models\Experience;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Response;
use Tests\Feature\BaseFeatureTestClass;

class ExpTest extends BaseFeatureTestClass
{
    use DatabaseTransactions;

    /**
     * Test if the "happy path" works as expected.
     *
     * @return void
     */
    public function testList()
    {
        Experience::factory()->count(5)->create();
        $result = $this->get(
            $this->baseUrl . '/exp/',
        );
        $result->assertStatus(Response::HTTP_OK);
        $result->assertJsonStructure(['success', 'status', 'message', 'data']);
        $content = $result->decodeResponseJson();
        $this->assertTrue($content['success']);
        $this->assertSame(Response::HTTP_OK, $content['status']);
        $this->assertSame(__('messages.successfulOperation'), $content['message']);
        $this->assertNotEmpty($content['data']);
        $this->assertIsArray($content['data']);
    }

    /**
     * Test if the "happy path" works as expected.
     *
     * @return void
     */
    public function testGet()
    {
        $experience = Experience::factory()->create();
        $result     = $this->get(
            $this->baseUrl . '/exp/' . $experience->id,
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
        $this->assertSame($experience->id, $data['id']);
        $this->assertSame($experience->start . ' 00:00:00', $data['start']);
        $this->assertSame($experience->end . ' 00:00:00', $data['end']);
        $this->assertSame($experience->image, $data['image']);
    }

    /**
     * Test if the "happy path" works as expected.
     *
     * @return void
     */
    public function testCreate()
    {
        $result = $this->post(
            $this->baseUrl . '/exp/',
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
            'experiences',
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
        $experience = Experience::factory()->create();
        $result     = $this->patch(
            $this->baseUrl . '/exp/' . $experience->id,
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
            'experiences',
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
        $experience = Experience::factory()->create();
        $result     = $this->delete(
            $this->baseUrl . '/exp/' . $experience->id,
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
            'experiences',
            [
                'image' => $experience->image,
                'start' => $experience->start,
                'end'   => $experience->end,
            ]
        );
    }
}
