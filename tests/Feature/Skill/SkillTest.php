<?php

namespace Tests\Feature\Skill;

use App\Models\Skill;
use Illuminate\Http\Response;
use Tests\Feature\BaseFeatureTestClass;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class SkillTest extends BaseFeatureTestClass
{
    use DatabaseTransactions;

    /**
     * Test if the "happy path" works as expected.
     *
     * @return void
     */
    public function testGet()
    {
        $skill  = Skill::factory()->create();
        $result = $this->get(
            $this->baseUrl . '/skill/' . $skill->id,
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
        $this->assertSame($skill->image, $data['image']);
    }

    /**
     * Test if the "happy path" works as expected.
     *
     * @return void
     */
    public function testCreate()
    {
        $result = $this->post(
            $this->baseUrl . '/skill/',
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
            'skills',
            ['image' => 'http://image']
        );
    }

    /**
     * Test if the "happy path" works as expected.
     *
     * @return void
     */
    public function testUpdate()
    {
        $skill  = Skill::factory()->create();
        $result = $this->patch(
            $this->baseUrl . '/skill/' . $skill->id,
            ['image' => 'http://image2'],
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
            'skills',
            ['image' => 'http://image2']
        );
    }

    /**
     * Test if the "happy path" works as expected.
     *
     * @return void
     */
    public function testSuspend()
    {
        $skill  = Skill::factory()->create();
        $result = $this->delete(
            $this->baseUrl . '/skill/' . $skill->id,
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
            'skills',
            [
                'image' => $skill->image,
            ]
        );
    }
}
