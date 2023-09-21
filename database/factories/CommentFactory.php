<?php

namespace Database\Factories;

use App\Models\Comment;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;
use JetBrains\PhpStorm\ArrayShape;

class CommentFactory extends Factory
{
    protected $model = Comment::class;


    public function definition(): array
    {
        return [
            'comment' => $this->faker->text(),
            'user_id' => \App\Models\User::factory(),
            'task_id' => \App\Models\Task::factory(),
        ];
    }
}
