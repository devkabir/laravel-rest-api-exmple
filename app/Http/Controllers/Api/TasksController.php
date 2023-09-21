<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\TaskRequest;
use App\Http\Resources\TaskCollection;
use App\Http\Resources\TaskResource;
use App\Models\Task;

class TasksController extends Controller
{
    public function index()
    {
        // Retrieve tasks created by or assigned to the authenticated user
        $tasks = Task::whereCreatorId(auth()->user()->id)
                        ->latest()
                        ->paginate();
        return new TaskCollection($tasks);

    }

    public function store(TaskRequest $request)
    {
        $validated = $request->validated();

        $task = Task::create($validated);

        return new TaskResource($task);
    }

    public function show(Task $task)
    {
        return new TaskResource($task);
    }

    public function update(TaskRequest $request, Task $task)
    {
        $validated = $request->validated();

        $task->update($validated);

        return new TaskResource($task);
    }

    public function destroy(Task $task)
    {
        $task->delete();

        return response()->noContent();
    }
}
