<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Comment;
use App\Models\Task;
use App\Models\User;
use Database\Factories\CommentFactory;
use Database\Factories\TaskFactory;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory(10)->create()
            ->each(function (User $user) {
                Task::factory(2)->create()->each(function (Task $task) use ($user) {
                    $user->tasks()->sync([$task->id]);
                    $task->comments()->createMany(Comment::factory(10)->make()->toArray());
                });

            });


}
}
