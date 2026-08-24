<?php

namespace App\Services;

use App\Models\Attendance;
use Carbon\Carbon;

class AttendanceService
{
    public function calculateWorkingMinutes(Attendance $attendance): int
    {
        if (!$attendance->start_time || !$attendance->end_time) {
            return 0;
        }

        $date = Carbon::parse($attendance->attendance_date)->startOfDay();
        $start = $this->timeOnDate($date, $attendance->start_time);
        $end = $this->timeOnDate($date, $attendance->end_time);

        // A checkout earlier than check-in represents an overnight shift.
        if ($end->lessThan($start)) {
            $end->addDay();
        }

        return $start->diffInMinutes($end);
    }

    public function formatMinutes(int $minutes): string
    {
        $hours = intdiv($minutes, 60);
        $remainingMinutes = $minutes % 60;

        return sprintf('%dh %02dm', $hours, $remainingMinutes);
    }

    private function timeOnDate(Carbon $date, string $time): Carbon
    {
        [$hour, $minute, $second] = array_pad(explode(':', $time), 3, 0);

        return $date->copy()->setTime((int) $hour, (int) $minute, (int) $second);
    }
}
