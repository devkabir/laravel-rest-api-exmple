<?php

namespace Api;

use App\Models\Comment;
use App\Models\Task;
use App\Models\User;
use App\Notifications\NewCommentAddedNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Notification;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CommentsControllerTest extends TestCase
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
    public function it_gets_comments_list(): void
    {
        $comments = Comment::factory()
            ->count(5)
            ->create([
                'user_id' => auth()->user()->id,
            ]);

        $response = $this->getJson(route('comments.index'));
        $response->assertOk()->assertSee($comments[0]->comment);
    }

    /**
     * @test
     */
    public function it_stores_the_comment(): void
    {
        Notification::fake();

        $task = Task::factory()->create();
        User::factory()->count(2)->create()
            ->each(function (User $user) use ($task) {
                $user->tasks()->syncWithoutDetaching($task->id);
            });

        $comment = Comment::factory()->make(['task_id' => $task->id])->toArray();


        $response = $this->postJson(route('comments.store'), $comment);

        Notification::assertSentTo(
            [$task->creator, $task->users],
            NewCommentAddedNotification::class,
            function ($notification, $channels){
                return $notification->comment->id === 1;
            }
        );

        $this->assertDatabaseHas('comments', $comment);

        $response->assertStatus(201)->assertJsonFragment($comment);
    }

    /**
     * @test
     */
    public function it_updates_the_comment(): void
    {
        $comment = Comment::factory()->create();

        $user = User::factory()->create();
        $task = Task::factory()->create();

        $data = [
            'comment' => $this->faker->text(),
            'user_id' => $user->id,
            'task_id' => $task->id,
        ];

        $response = $this->putJson(
            route('comments.update', $comment),
            $data
        );

        $data['id'] = $comment->id;

        $this->assertDatabaseHas('comments', $data);

        $response->assertOk()->assertJsonFragment($data);
    }

    /**
     * @test
     */
    public function it_deletes_the_comment(): void
    {
        $comment = Comment::factory()->create();

        $response = $this->deleteJson(route('comments.destroy', $comment));

        $this->assertSoftDeleted($comment);

        $response->assertNoContent();
    }
}
