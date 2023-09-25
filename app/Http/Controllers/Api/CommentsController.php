<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CommentRequest;
use App\Http\Resources\CommentCollection;
use App\Http\Resources\CommentResource;
use App\Models\Comment;
use App\Notifications\NewCommentAddedNotification;
use Illuminate\Http\Request;

class CommentsController extends Controller
{
    public function index()
    {
        $comments = Comment::whereUserId(auth()->user()->id)
                    ->latest()
                    ->cursorPaginate();
        return new CommentCollection($comments);
    }

    public function store(CommentRequest $request)
    {
        $validated = $request->validated();

        $comment = Comment::create($validated);

        $users = $comment->task->users()->get();
        $comment->task->creator->notify(new NewCommentAddedNotification($comment));
        foreach ($users as $user) {
            $user->notify(new NewCommentAddedNotification($comment));
        }
        return new CommentResource($comment);
    }

    public function show(Comment $comment)
    {
        return new CommentResource($comment);
    }

    public function update(CommentRequest $request, Comment $comment)
    {
        $validated = $request->validated();

        $comment->update($validated);

        return new CommentResource($comment);
    }

    public function destroy(Comment $comment)
    {
        $comment->delete();

        return response()->noContent();
    }
}
