<?php

namespace Tests\Feature\Proj;

use App\Models\Project;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Response;
use Tests\Feature\BaseFeatureTestClass;

class ProjTest extends BaseFeatureTestClass
{
    use DatabaseTransactions;

    /**
     * Test if the "happy path" works as expected.
     *
     * @return void
     */
    public function testList()
    {
        Project::factory()->count(5)->create();
        $result = $this->get(
            $this->baseUrl . '/proj/',
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
        $project = Project::factory()->create();
        $result  = $this->get(
            $this->baseUrl . '/proj/' . $project->id,
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
        $this->assertSame($project->id, $data['id']);
        $this->assertSame($project->start . ' 00:00:00', $data['start']);
        $this->assertSame($project->end . ' 00:00:00', $data['end']);
        $this->assertSame($project->image, $data['image']);
        $this->assertSame($project->link, $data['link']);
    }

    /**
     * Test if the "happy path" works as expected.
     *
     * @return void
     */
    public function testCreate()
    {
        $result = $this->post(
            $this->baseUrl . '/proj/',
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
            'projects',
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
        $project = Project::factory()->create();
        $result  = $this->patch(
            $this->baseUrl . '/proj/' . $project->id,
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
            'projects',
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
        $project = Project::factory()->create();
        $result  = $this->delete(
            $this->baseUrl . '/proj/' . $project->id,
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
            'projects',
            [
                'image' => $project->image,
                'link'  => $project->link,
                'start' => $project->start,
                'end'   => $project->end,
            ]
        );
    }
}
