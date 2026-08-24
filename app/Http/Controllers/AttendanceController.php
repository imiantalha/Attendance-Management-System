<?php

namespace App\Http\Controllers;

use App\Http\Requests\Attendance\StoreAttendanceRequest;
use App\Http\Requests\Attendance\UpdateAttendanceRequest;
use App\Models\Attendance;
use App\Models\User;
use App\Services\AttendanceService;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AttendanceController extends Controller
{
    public function __construct(private readonly AttendanceService $attendanceService)
    {
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
        $attendances = Attendance::with(['user', 'attendedBy'])
            ->latest('attendance_date')
            ->latest('id')
            ->paginate(10);

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

        Attendance::create($data);

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
        $attendance->update($request->validated());

        return redirect()
            ->route('attendances.index')
            ->with('success', 'Attendance updated successfully.');
    }

    public function destroy(Attendance $attendance): RedirectResponse
    {
        $attendance->delete();

        return redirect()
            ->route('attendances.index')
            ->with('success', 'Attendance deleted successfully.');
    }

    public function report(User $user): View
    {
        $attendances = Attendance::with(['user', 'attendedBy'])
            ->where('user_id', $user->id)
            ->orderByDesc('attendance_date')
            ->get();

        $workingMinutes = $attendances->mapWithKeys(
            fn (Attendance $attendance) => [
                $attendance->id => $this->attendanceService->calculateWorkingMinutes($attendance),
            ]
        );

        return view('attendances.report', compact('attendances', 'user', 'workingMinutes'));
    }

    public function weeklyReport(User $user): View
    {
        return $this->periodReport($user, Carbon::now()->startOfWeek(), Carbon::now()->endOfDay());
    }

    public function monthlyReport(User $user): View
    {
        return $this->periodReport($user, Carbon::now()->startOfMonth(), Carbon::now()->endOfDay());
    }

    public function yearlyReport(User $user): View
    {
        return $this->periodReport($user, Carbon::now()->startOfYear(), Carbon::now()->endOfDay());
    }

    private function periodReport(User $user, Carbon $start, Carbon $end): View
    {
        $attendances = Attendance::with(['user', 'attendedBy'])
            ->where('user_id', $user->id)
            ->whereBetween('attendance_date', [$start->toDateString(), $end->toDateString()])
            ->orderByDesc('attendance_date')
            ->get();

        $workingMinutes = $attendances->mapWithKeys(
            fn (Attendance $attendance) => [
                $attendance->id => $this->attendanceService->calculateWorkingMinutes($attendance),
            ]
        );

        $totalWorkingMinutes = $workingMinutes->sum();

        return view('attendances.week-report', compact(
            'attendances',
            'totalWorkingMinutes',
            'workingMinutes',
            'user'
        ));
    }
}
