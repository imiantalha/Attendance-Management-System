<?php

namespace Tests\Unit;

use App\Models\Attendance;
use App\Services\AttendanceService;
use PHPUnit\Framework\TestCase;

class AttendanceServiceTest extends TestCase
{
    private AttendanceService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = new AttendanceService();
    }

    public function test_it_calculates_a_normal_shift(): void
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
            'start_time' => '22:00',
            'end_time' => '06:00',
        ]);

        $this->assertSame(480, $this->service->calculateWorkingMinutes($attendance));
    }

    public function test_it_returns_zero_for_an_open_attendance(): void
    {
        $attendance = new Attendance([
            'attendance_date' => '2026-08-24',
            'start_time' => '09:00',
            'end_time' => null,
        ]);

        $this->assertSame(0, $this->service->calculateWorkingMinutes($attendance));
    }

    public function test_it_formats_minutes_consistently(): void
    {
        $this->assertSame('8h 05m', $this->service->formatMinutes(485));
        $this->assertSame('0h 00m', $this->service->formatMinutes(0));
    }
}
