<?php

namespace App\Http\Controllers;

use App\Http\Requests\Attendance\StoreAttendanceRequest;
use App\Http\Requests\Attendance\UpdateAttendanceRequest;
use App\Models\Attendance;
use App\Models\User;
use App\Services\AttendanceReportService;
use App\Services\AttendanceService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AttendanceController extends Controller
{
    public function __construct(
        private readonly AttendanceService $attendanceService,
        private readonly AttendanceReportService $attendanceReportService
    ) {
        $this->middleware('permission:attendance-list')->only(['index', 'show']);
        $this->middleware('permission:attendance-create')->only(['create', 'store']);
        $this->middleware('permission:attendance-edit')->only(['edit', 'update']);
        $this->middleware('permission:attendance-delete')->only(['destroy']);
        $this->middleware('permission:attendance-report')->only([
            'report',
            'weeklyReport',
            'monthlyReport',
            'yearlyReport',
        ]);
    }

    public function index(): View
    {
        $attendances = Attendance::with(['user:id,name,email', 'attendedBy:id,name'])
            ->latest('attendance_date')
            ->latest('id')
            ->paginate(10);

        $attendances->getCollection()->transform(function (Attendance $attendance): Attendance {
            $attendance->working_minutes = $this->attendanceService->calculateWorkingMinutes($attendance);
            $attendance->working_duration = $attendance->working_minutes > 0
                ? $this->attendanceService->formatMinutes($attendance->working_minutes)
                : null;
            $attendance->status = $attendance->end_time ? 'Completed' : 'Working';

            return $attendance;
        });

        return view('attendances.index', compact('attendances'));
    }

    public function create(): View
    {
        $users = User::orderBy('name')->get(['id', 'name', 'email']);

        return view('attendances.create', compact('users'));
    }

    public function store(StoreAttendanceRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['attendance_by'] = $request->user()->id;

        $this->attendanceService->create($data);

        return redirect()
            ->route('attendances.index')
            ->with('success', 'Attendance created successfully.');
    }

    public function show(Attendance $attendance): View
    {
        $attendance->load(['user', 'attendedBy']);

        return view('attendances.show', compact('attendance'));
    }

    public function edit(Attendance $attendance): View
    {
        $users = User::orderBy('name')->get(['id', 'name', 'email']);

        return view('attendances.edit', compact('attendance', 'users'));
    }

    public function update(UpdateAttendanceRequest $request, Attendance $attendance): RedirectResponse
    {
        $this->attendanceService->update($attendance, $request->validated());

        return redirect()
            ->route('attendances.index')
            ->with('success', 'Attendance updated successfully.');
    }

    public function destroy(Attendance $attendance): RedirectResponse
    {
        $this->attendanceService->delete($attendance);

        return redirect()
            ->route('attendances.index')
            ->with('success', 'Attendance deleted successfully.');
    }

    public function report(User $user): View
    {
        $report = $this->attendanceReportService->forUser($user);

        return view('attendances.report', [
            'attendances' => $report['attendances'],
            'user' => $user,
            'workingMinutes' => $report['workingMinutes'],
        ]);
    }

    public function weeklyReport(User $user): View
    {
        return $this->periodReport($user, 'week');
    }

    public function monthlyReport(User $user): View
    {
        return $this->periodReport($user, 'month');
    }

    public function yearlyReport(User $user): View
    {
        return $this->periodReport($user, 'year');
    }

    private function periodReport(User $user, string $period): View
    {
        $dates = $this->attendanceReportService->period($period);
        $report = $this->attendanceReportService->forUser($user, $dates['start'], $dates['end']);

        return view('attendances.week-report', [
            'attendances' => $report['attendances'],
            'totalWorkingMinutes' => $report['totalWorkingMinutes'],
            'workingMinutes' => $report['workingMinutes'],
            'user' => $user,
        ]);
    }
}
