<?php

namespace Tests\Feature;

use App\Models\Attendance;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_verified_users_can_view_dashboard_metrics(): void
    {
        $user = User::factory()->create();
        $present = User::factory()->create();
        $working = User::factory()->create();

        Attendance::create([
            'user_id' => $present->id,
            'attendance_by' => $user->id,
            'attendance_date' => now()->toDateString(),
            'start_time' => '09:00',
            'end_time' => '17:00',
        ]);

        Attendance::create([
            'user_id' => $working->id,
            'attendance_by' => $user->id,
            'attendance_date' => now()->toDateString(),
            'start_time' => '09:00',
            'end_time' => null,
        ]);

        $response = $this->actingAs($user)
            ->get('/dashboard');

        $response
            ->assertOk()
            ->assertViewHas('employeeCount', 3)
            ->assertViewHas('presentCount', 2)
            ->assertViewHas('workingCount', 1)
            ->assertViewHas('absentCount', 1);
    }

    public function test_guests_cannot_view_dashboard(): void
    {
        $this->get('/dashboard')->assertRedirect('/login');
    }
}
