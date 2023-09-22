<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\User */
class UserResource extends JsonResource
{
    /**
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'comments_count' => $this->comments_count,
            'created_tasks_count' => $this->created_tasks_count,
            'notifications_count' => $this->notifications_count,
            'read_notifications_count' => $this->read_notifications_count,
            'tasks_count' => $this->tasks_count,
            'unread_notifications_count' => $this->unread_notifications_count,
        ];
    }
}
