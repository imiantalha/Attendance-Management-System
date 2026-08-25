<?php

namespace Tests\Unit;

use App\Models\Attendance;
use App\Models\User;
use App\Policies\AttendancePolicy;
use PHPUnit\Framework\TestCase;

class AttendancePolicyTest extends TestCase
{
    public function test_attendance_policy_delegates_create_permission(): void
    {
        $policy = new AttendancePolicy();
        $user = new User();

        $this->assertFalse($policy->create($user));
    }

    public function test_attendance_policy_denies_view_without_permission(): void
    {
        $policy = new AttendancePolicy();
        $user = new User();
        $attendance = new Attendance();

        $this->assertFalse($policy->view($user, $attendance));
    }
}
