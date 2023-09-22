<?php

namespace Api;

use App\Models\Comment;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class TaskCommentsControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();

        $user = User::factory()->create(['email' => 'admin@admin.com']);

        Sanctum::actingAs($user, [], 'web');

        $this->withoutExceptionHandling();
    }

    /**
     * @test
     */
    public function it_gets_task_comments(): void
    {
        $task = Task::factory()->create();
        $comments = Comment::factory()
            ->count(2)
            ->create([
                'task_id' => $task->id,
            ]);

        $response = $this->getJson(route('tasks.comments.index', $task));

        $response->assertOk()->assertSee($comments[0]->comment);
    }

    /**
     * @test
     */
    public function it_stores_the_task_comments(): void
    {
        $task = Task::factory()->create();
        $data = Comment::factory()
            ->make([
                'task_id' => $task->id,
            ])
            ->toArray();

        $response = $this->postJson(
            route('tasks.comments.store', $task),
            $data
        );

        $this->assertDatabaseHas('comments', $data);

        $response->assertStatus(201)->assertJsonFragment($data);

        $comment = Comment::latest('id')->first();

        $this->assertEquals($task->id, $comment->task_id);
    }
}
