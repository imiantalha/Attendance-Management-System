<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Attendance\StoreAttendanceRequest;
use App\Http\Requests\Attendance\UpdateAttendanceRequest;
use App\Http\Resources\AttendanceResource;
use App\Models\Attendance;
use App\Services\AttendanceService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Symfony\Component\HttpFoundation\Response;

class AttendanceController extends Controller
{
    public function __construct(private readonly AttendanceService $attendanceService)
    {
        $this->middleware('permission:attendance-list')->only(['index', 'show']);
        $this->middleware('permission:attendance-create')->only(['store']);
        $this->middleware('permission:attendance-edit')->only(['update']);
        $this->middleware('permission:attendance-delete')->only(['destroy']);
    }

    public function index(): AnonymousResourceCollection
    {
        $attendances = Attendance::with(['user', 'attendedBy'])
            ->latest('attendance_date')
            ->latest('id')
            ->paginate(15);

        $attendances->getCollection()->transform(
            fn (Attendance $attendance) => $this->withDuration($attendance)
        );

        return AttendanceResource::collection($attendances);
    }

    public function show(Attendance $attendance): AttendanceResource
    {
        $attendance->load(['user', 'attendedBy']);

        return new AttendanceResource($this->withDuration($attendance));
    }

    public function store(StoreAttendanceRequest $request): AttendanceResource
    {
        $data = $request->validated();
        $data['attendance_by'] = $request->user()->id;

        $attendance = Attendance::create($data);
        $attendance->load(['user', 'attendedBy']);

        return new AttendanceResource($this->withDuration($attendance));
    }

    public function update(UpdateAttendanceRequest $request, Attendance $attendance): AttendanceResource
    {
        $attendance->update($request->validated());
        $attendance->load(['user', 'attendedBy']);

        return new AttendanceResource($this->withDuration($attendance));
    }

    public function destroy(Attendance $attendance): JsonResponse
    {
        $attendance->delete();

        return response()->json([
            'message' => 'Attendance deleted successfully.',
        ], Response::HTTP_OK);
    }

    private function withDuration(Attendance $attendance): Attendance
    {
        $minutes = $this->attendanceService->calculateWorkingMinutes($attendance);

        return $attendance->setAttribute('working_minutes', $minutes)
            ->setAttribute('duration', $this->attendanceService->formatMinutes($minutes));
    }
}
