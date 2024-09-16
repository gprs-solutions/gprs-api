<?php

namespace Tests\Unit\Controllers\ProjController;

use App\Http\Controllers\ProjController;
use App\Http\Services\ProjService;
use App\Models\Project;
use Illuminate\Http\Response;
use Mockery;
use Tests\Unit\Controllers\BaseController;

class ProjControllerTest extends BaseController
{
    /**
     * Test if the "happy path" works as expected.
     *
     * @return void
     */
    public function testGet()
    {
        Project::factory()->create();
        $mock = Mockery::mock(ProjService::class);
        $mock->shouldReceive('get')->andReturn($this->defaultSuccessReturn);
        $this->app->instance(ProjService::class, $mock);

        $this->request->merge(
            ['id' => 1]
        );

        $controller = app(ProjController::class);
        $result     = $controller->get($this->request);

        $this->assertEquals(Response::HTTP_OK, $result->getStatusCode());

        $content = json_decode($result->getContent());
        $this->assertEquals(Response::HTTP_OK, $content->status);
        $this->assertTrue($content->success);
        $this->assertEquals(__('messages.successfulOperation'), $content->message);
        $this->assertSame(['success' => true], (array) reset($content->data));
    }

    /**
     * Test if the "happy path" works as expected.
     *
     * @return void
     */
    public function testCreate()
    {
        $mock = Mockery::mock(ProjService::class);
        $mock->shouldReceive('create')->andReturn($this->defaultSuccessReturn);
        $this->app->instance(ProjService::class, $mock);

        $this->request->merge(
            [
                'image'        => 'http://image',
                'link'         => 'http://image',
                'start'        => '2020-10-07',
                'end'          => '2022-10-07',
                'descriptions' => [
                    [
                        'id'          => 1,
                        'name'        => 'teste',
                        'lang'        => 'PT',
                        'description' => 'teste',
                    ],
                ],
            ]
        );

        $controller = app(ProjController::class);
        $result     = $controller->create($this->request);
        $this->assertEquals(Response::HTTP_OK, $result->getStatusCode());

        $content = json_decode($result->getContent());
        $this->assertTrue($content->success);
        $this->assertEquals(Response::HTTP_OK, $content->status);
        $this->assertEquals(__('messages.successfulOperation'), $content->message);
        $this->assertEmpty($content->data);
    }

    /**
     * Test if the "happy path" works as expected.
     *
     * @return void
     */
    public function testUpdate()
    {
        $project = Project::factory()->create();
        $mock    = Mockery::mock(ProjService::class);
        $mock->shouldReceive('update')->andReturn($this->defaultSuccessReturn);
        $this->app->instance(ProjService::class, $mock);

        $this->request->merge(
            [
                'id'           => $project->id,
                'image'        => 'http://image',
                'link'         => 'http://image',
                'start'        => '2020-10-07',
                'end'          => '2022-10-07',
                'descriptions' => [
                    [
                        'id'          => 1,
                        'name'        => 'teste',
                        'lang'        => 'PT',
                        'description' => 'teste',
                    ],
                ],
            ]
        );

        $controller = app(ProjController::class);
        $result     = $controller->update($this->request);
        $this->assertEquals(Response::HTTP_OK, $result->getStatusCode());

        $content = json_decode($result->getContent());
        $this->assertTrue($content->success);
        $this->assertEquals(Response::HTTP_OK, $content->status);
        $this->assertEquals(__('messages.successfulOperation'), $content->message);
        $this->assertEmpty($content->data);
    }

    /**
     * Test if the "happy path" works as expected.
     *
     * @return void
     */
    public function testSuspend()
    {
        $project = Project::factory()->create();
        $mock    = Mockery::mock(ProjService::class);
        $mock->shouldReceive('suspend')->andReturn($this->defaultSuccessReturn);
        $this->app->instance(ProjService::class, $mock);

        $this->request->merge(
            ['id' => $project->id]
        );

        $controller = app(ProjController::class);
        $result     = $controller->suspend($this->request);
        $this->assertEquals(Response::HTTP_OK, $result->getStatusCode());

        $content = json_decode($result->getContent());
        $this->assertTrue($content->success);
        $this->assertEquals(Response::HTTP_OK, $content->status);
        $this->assertEquals(__('messages.successfulOperation'), $content->message);
        $this->assertEmpty($content->data);
    }
}
