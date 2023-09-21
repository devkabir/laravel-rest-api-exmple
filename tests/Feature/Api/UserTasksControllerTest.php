<?php

namespace Tests\Feature\Api;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class UserTasksControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function setUp():void
    {
        parent::setUp();

        $user = User::factory()->create(['email' => 'admin@admin.com']);

        Sanctum::actingAs($user, [], 'web');

        $this->withoutExceptionHandling();
    }

    /**
     * @test
     */
    public function it_gets_user_tasks(): void
    {
        $user = User::factory()->create();
        $task = Task::factory()->create();

        $user->tasks()->attach($task);

        $response = $this->getJson(route('users.tasks.index', $user));

        $response->assertOk()->assertSee($task->name);
    }

    /**
     * @test
     */
    public function it_can_attach_tasks_to_user(): void
    {
        $user = User::factory()->create();
        $task = Task::factory()->create();

        $response = $this->postJson(
            route('users.tasks.store', [$user, $task])
        );

        $response->assertNoContent();

        $this->assertTrue(
            $user
                ->tasks()
                ->where('tasks.id', $task->id)
                ->exists()
        );
    }

    /**
     * @test
     */
    public function it_can_detach_tasks_from_user(): void
    {
        $user = User::factory()->create();
        $task = Task::factory()->create();

        $response = $this->deleteJson(
            route('users.tasks.store', [$user, $task])
        );

        $response->assertNoContent();

        $this->assertFalse(
            $user
                ->tasks()
                ->where('tasks.id', $task->id)
                ->exists()
        );
    }

}
