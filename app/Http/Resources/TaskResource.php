<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Task */
class TaskResource extends JsonResource
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
            'description' => $this->description,
            'created_at' => $this->created_at->diffForHumans(),
            'updated_at' => $this->updated_at->diffForHumans(),
            'deleted_at' => $this->deleted_at?->diffForHumans(),
            'comments_count' => $this->comments_count,
            'users_count' => $this->users_count,
            'creator_id' => $this->creator_id,

            'creator' => $this->whenLoaded('creator'),
            'users' => $this->whenLoaded('users'),
            'comments' => new CommentCollection($this->whenLoaded('comments')),
        ];
    }


}
