<?php

namespace Api;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class TasksControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * @test
     */
    public function it_gets_tasks_list(): void
    {
        Task::factory(20)->create();
        $response = $this->getJson(route('tasks.index'));


        $response->assertOk();
        // Assert that the response structure includes pagination data
        $response->assertJsonStructure([
            'data',
            'links',
            'meta',
        ]);
        $response->assertJsonFragment([
            'current_page' => 1,
            'per_page' => 15,
            'last_page' => 2
        ]);
        $response->assertJsonFragment([
            'next' => 'http://localhost:8000/api/tasks?page=2'
        ]);
    }

    /**
     * @test
     */
    public function it_stores_the_task(): void
    {
        $data = Task::factory()
            ->make([
                'creator_id' => auth()->user()->id,
            ])
            ->toArray();
        $response = $this->postJson(route('tasks.store'), $data);

        $this->assertDatabaseHas('tasks', $data);

        $response->assertStatus(201)->assertJsonFragment($data);
    }

    /**
     * @test
     */
    public function it_updates_the_task(): void
    {
        $task = Task::factory()->create();

        $user = User::factory()->create();

        $data = [
            'name' => $this->faker->text(255),
            'description' => $this->faker->text(),
            'creator_id' => $user->id,
        ];

        $response = $this->putJson(route('tasks.update', $task), $data);

        $data['id'] = $task->id;

        $this->assertDatabaseHas('tasks', $data);

        $response->assertOk()->assertJsonFragment($data);
    }

    /**
     * @test
     */
    public function it_deletes_the_task(): void
    {
        $task = Task::factory()->create();

        $response = $this->deleteJson(route('tasks.destroy', $task));

        $this->assertSoftDeleted($task);

        $response->assertNoContent();
    }

    protected function setUp(): void
    {
        parent::setUp();

        $user = User::factory()->create(['email' => 'admin@admin.com']);

        Sanctum::actingAs($user, [], 'web');

        $this->withoutExceptionHandling();
    }

}
