<?php

namespace App\Http\Resources;

use App\Services\AttendanceService;
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
        $attendanceService = app(AttendanceService::class);
        $workingMinutes = $attendanceService->calculateWorkingMinutes($this->resource);

        return [
            'id' => $this->id,
            'attendance_date' => $this->attendance_date?->toDateString(),
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'working_minutes' => $workingMinutes,
            'duration' => $attendanceService->formatMinutes($workingMinutes),
            'employee' => new UserResource($this->whenLoaded('user')),
            'recorded_by' => new UserResource($this->whenLoaded('attendedBy')),
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}
