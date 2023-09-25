<?php

use App\Http\Controllers\Api\CommentsController;
use App\Http\Controllers\Api\TaskCommentsController;
use App\Http\Controllers\Api\TasksController;
use App\Http\Controllers\Api\UserTasksController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware(['auth:sanctum', 'verified'])
    ->group(function () {
        Route::get('user', fn(Request $request) => $request->user())->name('user');
        Route::get('users', fn(Request $request) => User::whereNotIn('id', [auth()->id()])->get()->pluck('name', 'id'))->name('users');
        // User's  Created Tasks
        Route::apiResource('tasks', TasksController::class);
        Route::get('/tasks/{task}/users', [TasksController::class, 'users',])->name('tasks.users');
        // User's Comments
        Route::apiResource('comments', CommentsController::class);

        // User's Assigned Tasks
        Route::get('/users/{user}/tasks', [UserTasksController::class, 'index',])->name('users.tasks.index');
        Route::post('/users/{user}/tasks/{task}', [UserTasksController::class, 'store',])->name('users.tasks.store');
        Route::delete('/users/{user}/tasks/{task}', [UserTasksController::class, 'destroy',])->name('users.tasks.destroy');

        // Task's Comments
        Route::get('/tasks/{task}/comments', [TaskCommentsController::class, 'index',])->name('tasks.comments.index');
        Route::post('/tasks/{task}/comments', [TaskCommentsController::class, 'store',])->name('tasks.comments.store');

    });
