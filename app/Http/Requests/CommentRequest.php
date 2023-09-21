<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CommentRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'user_id' => ['required', 'exists:users,id'],
            'task_id' => ['required', 'exists:tasks,id'],
            'comment' => ['required', 'max:255', 'string'],
        ];
    }

    public function authorize(): bool
    {
        return auth()->hasUser();
    }
}
