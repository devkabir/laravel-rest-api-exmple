<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TaskRequest extends FormRequest
{
    public function attributes()
    {
        return [
            'selectedUsers' => 'User selection'
        ];
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'max:255', 'string'],
            'description' => ['required', 'max:255', 'string'],
            'creator_id' => ['required', 'exists:users,id'],
            'selectedUsers' => ['required', 'array'],
        ];
    }

    public function authorize(): bool
    {
        return auth()->hasUser();
    }
}
