<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CommentCollection;
use App\Http\Resources\CommentResource;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskCommentsController extends Controller
{
    public function index(Task $task)
    {
        $comments = $task
            ->comments()
            ->latest()
            ->paginate();

        return new CommentCollection($comments);
    }

    public function store(Request $request, Task $task)
    {
        $validated = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'comment' => ['required', 'max:255', 'string'],
        ]);

        $comment = $task->comments()->create($validated);

        return new CommentResource($comment);
    }

}
