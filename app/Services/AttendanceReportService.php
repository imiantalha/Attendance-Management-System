<?php

namespace App\Services;

use App\Models\Attendance;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class AttendanceReportService
{
    public function __construct(private readonly AttendanceService $attendanceService)
    {
    }

    /**
     * @return array{attendances: Collection, workingMinutes: Collection, totalWorkingMinutes: int}
     */
    public function forUser(User $user, ?Carbon $start = null, ?Carbon $end = null): array
    {
        $query = Attendance::with(['user', 'attendedBy'])
            ->where('user_id', $user->id)
            ->orderByDesc('attendance_date')
            ->orderByDesc('id');

        if ($start && $end) {
            $query->whereBetween('attendance_date', [
                $start->toDateString(),
                $end->toDateString(),
            ]);
        }

        /** @var Collection<int, Attendance> $attendances */
        $attendances = $query->get();

        $workingMinutes = $attendances->mapWithKeys(
            fn (Attendance $attendance) => [
                $attendance->id => $this->attendanceService->calculateWorkingMinutes($attendance),
            ]
        );

        return [
            'attendances' => $attendances,
            'workingMinutes' => $workingMinutes,
            'totalWorkingMinutes' => (int) $workingMinutes->sum(),
        ];
    }

    /**
     * @return array{start: Carbon, end: Carbon}
     */
    public function period(string $period): array
    {
        $now = Carbon::now();

        return match ($period) {
            'week' => [
                'start' => $now->copy()->startOfWeek(),
                'end' => $now->copy()->endOfDay(),
            ],
            'month' => [
                'start' => $now->copy()->startOfMonth(),
                'end' => $now->copy()->endOfDay(),
            ],
            'year' => [
                'start' => $now->copy()->startOfYear(),
                'end' => $now->copy()->endOfDay(),
            ],
            default => throw new \InvalidArgumentException("Unsupported attendance report period: {$period}"),
        };
    }
}
