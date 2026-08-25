<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $today = now()->toDateString();

        $todayAttendances = Attendance::query()
            ->whereDate('attendance_date', $today)
            ->get(['id', 'user_id', 'start_time', 'end_time', 'attendance_date']);

        $presentUserIds = $todayAttendances->pluck('user_id')->unique();

        return view('dashboard', [
            'employeeCount' => User::query()->count(),
            'presentCount' => $presentUserIds->count(),
            'workingCount' => $todayAttendances->whereNull('end_time')->pluck('user_id')->unique()->count(),
            'absentCount' => max(0, User::query()->count() - $presentUserIds->count()),
            'todayAttendances' => $todayAttendances,
        ]);
    }
}
