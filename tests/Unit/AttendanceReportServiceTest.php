<?php

namespace Tests\Unit;

use App\Models\Attendance;
use App\Models\User;
use App\Services\AttendanceReportService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AttendanceReportServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_returns_only_the_requested_user_attendance(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        Attendance::create([
            'user_id' => $user->id,
            'attendance_by' => $user->id,
            'attendance_date' => '2026-08-24',
            'start_time' => '09:00',
            'end_time' => '17:00',
        ]);

        Attendance::create([
            'user_id' => $otherUser->id,
            'attendance_by' => $otherUser->id,
            'attendance_date' => '2026-08-24',
            'start_time' => '09:00',
            'end_time' => '17:00',
        ]);

        $report = app(AttendanceReportService::class)->forUser($user);

        $this->assertCount(1, $report['attendances']);
        $this->assertSame(480, $report['totalWorkingMinutes']);
    }

    public function test_it_filters_reports_by_date_range(): void
    {
        $user = User::factory()->create();

        foreach (['2026-08-01', '2026-08-15', '2026-08-30'] as $date) {
            Attendance::create([
                'user_id' => $user->id,
                'attendance_by' => $user->id,
                'attendance_date' => $date,
                'start_time' => '09:00',
                'end_time' => '17:00',
            ]);
        }

        $report = app(AttendanceReportService::class)->forUser(
            $user,
            Carbon::parse('2026-08-10'),
            Carbon::parse('2026-08-20')
        );

        $this->assertCount(1, $report['attendances']);
        $this->assertSame(480, $report['totalWorkingMinutes']);
    }

    public function test_it_rejects_unknown_report_periods(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        app(AttendanceReportService::class)->period('quarter');
    }
}
