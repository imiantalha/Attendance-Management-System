<?php

namespace Tests\Unit\Services;

use App\Models\Attendance;
use App\Services\AttendanceService;
use Tests\TestCase;

class AttendanceServiceTest extends TestCase
{
    private AttendanceService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new AttendanceService();
    }

    public function test_it_calculates_a_normal_same_day_shift(): void
    {
        $attendance = new Attendance([
            'attendance_date' => '2026-08-24',
            'start_time' => '09:00',
            'end_time' => '17:30',
        ]);

        $this->assertSame(510, $this->service->calculateWorkingMinutes($attendance));
    }

    public function test_it_calculates_an_overnight_shift(): void
    {
        $attendance = new Attendance([
            'attendance_date' => '2026-08-24',
            'start_time' => '23:00',
            'end_time' => '07:00',
        ]);

        $this->assertSame(480, $this->service->calculateWorkingMinutes($attendance));
    }

    public function test_midnight_checkout_is_calculated_as_the_next_day(): void
    {
        $attendance = new Attendance([
            'attendance_date' => '2026-08-24',
            'start_time' => '23:30',
            'end_time' => '00:00',
        ]);

        $this->assertSame(30, $this->service->calculateWorkingMinutes($attendance));
    }

    public function test_open_attendance_has_zero_completed_working_minutes(): void
    {
        $attendance = new Attendance([
            'attendance_date' => '2026-08-24',
            'start_time' => '09:00',
            'end_time' => null,
        ]);

        $this->assertSame(0, $this->service->calculateWorkingMinutes($attendance));
    }

    public function test_equal_start_and_end_times_do_not_create_a_full_day_shift(): void
    {
        $attendance = new Attendance([
            'attendance_date' => '2026-08-24',
            'start_time' => '09:00',
            'end_time' => '09:00',
        ]);

        $this->assertSame(0, $this->service->calculateWorkingMinutes($attendance));
    }

    public function test_it_formats_minutes_consistently(): void
    {
        $this->assertSame('8h 05m', $this->service->formatMinutes(485));
    }
}
