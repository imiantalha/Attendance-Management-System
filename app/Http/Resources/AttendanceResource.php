<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AttendanceResource extends JsonResource
{
    /**
     * Transform the resource into an API representation.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'attendance_date' => $this->attendance_date?->toDateString(),
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'working_minutes' => $this->when(
                $this->hasAttribute('working_minutes'),
                fn () => (int) $this->working_minutes
            ),
            'duration' => $this->when(
                $this->hasAttribute('duration'),
                fn () => $this->duration
            ),
            'employee' => new UserResource($this->whenLoaded('user')),
            'recorded_by' => new UserResource($this->whenLoaded('attendedBy')),
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}
