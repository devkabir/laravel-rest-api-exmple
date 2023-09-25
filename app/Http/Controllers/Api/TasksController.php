<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\TaskRequest;
use App\Http\Resources\TaskCollection;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use App\Models\User;
use App\Notifications\TaskAssignedNotification;

class TasksController extends Controller
{
    public function index()
    {
        // Retrieve tasks created by or assigned to the authenticated user
        $tasks = Task::latest()->with('creator:id,name', 'users:id,name')->withCount(['comments', 'users'])
            ->paginate();
        return new TaskCollection($tasks);

    }

    public function store(TaskRequest $request)
    {
        $validated = $request->validated();

        $task = Task::create($validated);

        $task->users()->sync($request->input('selectedUsers', []));

        $this->sendNotification($request, $task);

        return new TaskResource($task);
    }

    public function show(Task $task)
    {
        return new TaskResource(Task::with(['comments', 'comments.user:id,name', 'creator:id,name', 'users:id,name'])->withCount(['comments', 'users'])->find($task->id));
    }

    public function users (Task $task) {
        return User::whereNotIn('id', array_merge( [$task->creator_id, auth()->user()->id],  [$task->users->pluck('id')]))->get()->toJson();
    }

    public function update(TaskRequest $request, Task $task)
    {
        $validated = $request->validated();

        $task->update($validated);
        if (count($validated['selectedUsers']) !== $task->users->count()) {
            $task->users()->sync($request->input('selectedUsers', []));

            $this->sendNotification($request, $task);
        }

        return new TaskResource($task);
    }

    public function destroy(Task $task)
    {
        $task->delete();

        return response()->noContent();
    }

    /**
     * @param  TaskRequest  $request
     * @param  Task  $task
     */
    public function sendNotification(TaskRequest $request, Task $task): void
    {
        if (gettype($request->input('selectedUsers')) === 'array') {
            foreach ($request->input('selectedUsers') as $user_id) {
                User::find($user_id)->notify(new TaskAssignedNotification($task));
            }
        }

    }
}
