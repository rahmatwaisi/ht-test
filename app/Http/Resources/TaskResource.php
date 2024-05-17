<?php

namespace App\Http\Resources;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /** @var Task|JsonResource $this */

        $data = [
            'id' => $this->id,
            'slug' => $this->slug,
            'title' => $this->title,
            'description' => $this->description ?? null,
            'status' => $this->status,
            'completed_at' => $this->completed_at ?? null,
            'created_at' => $this->created_at ?? null,
            'updated_at' => $this->updated_at ?? null,
        ];

        if ($this->relationLoaded('owner')) {
            $data['owner'] = new UserResource($this->whenLoaded('owner'));
        }

        return $data;
    }
}
