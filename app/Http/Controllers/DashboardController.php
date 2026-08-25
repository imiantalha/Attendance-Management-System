<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\User;
use App\Services\AttendanceService;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(
        private readonly AttendanceService $attendanceService,
    ) {
    }

    public function __invoke(): View
    {
        $today = now()->toDateString();
        $employeeCount = User::query()->count();

        $todayAttendances = Attendance::query()
            ->with('user:id,name,email')
            ->whereDate('attendance_date', $today)
            ->latest('start_time')
            ->get(['id', 'user_id', 'start_time', 'end_time', 'attendance_date']);

        $presentUserIds = $todayAttendances->pluck('user_id')->unique();

        $todayAttendances->each(function (Attendance $attendance): void {
            $minutes = $this->attendanceService->calculateWorkingMinutes($attendance);
            $attendance->setAttribute('working_duration', $minutes > 0
                ? $this->attendanceService->formatMinutes($minutes)
                : '—');
        });

        return view('dashboard', [
            'employeeCount' => $employeeCount,
            'presentCount' => $presentUserIds->count(),
            'workingCount' => $todayAttendances->whereNull('end_time')->pluck('user_id')->unique()->count(),
            'absentCount' => max(0, $employeeCount - $presentUserIds->count()),
            'todayAttendances' => $todayAttendances,
        ]);
    }
}
