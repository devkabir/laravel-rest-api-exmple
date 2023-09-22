<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Comment;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $user = User::factory()->create();
        $tasks = Task::factory(10)->make(['creator_id' => $user->id])->toArray();
        $user->created_tasks()->createMany($tasks);
        $user->created_tasks->each(function (Task $task){
            $users = User::factory(10)->create();
            $task->users()->syncWithoutDetaching($users->pluck('id'));
            $users->each(function (User $user) use ($task) {
                $comments = Comment::factory(10)->make(['user_id' => $user->id]);
                $task->comments()->saveMany($comments);
            });
        });
    }
}
