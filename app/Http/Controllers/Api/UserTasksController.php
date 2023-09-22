<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\TaskCollection;
use App\Mail\TaskAssignedMail;
use App\Models\Task;
use App\Models\User;
use App\Notifications\TaskAssignedNotification;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Mail;

class UserTasksController extends Controller
{
    public function index(User $user): TaskCollection
    {
        $tasks = $user
            ->tasks()
            ->latest()
            ->paginate();

        return new TaskCollection($tasks);
    }

    public function store(User $user, Task $task): Response
    {
        $user->tasks()->syncWithoutDetaching([$task->id]);
        $user->notify(new TaskAssignedNotification($task));
        return response()->noContent();
    }

    public function destroy(User $user, Task $task): Response
    {
        $user->tasks()->detach($task);

        return response()->noContent();
    }
}
