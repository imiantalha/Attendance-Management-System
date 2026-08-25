@extends('layouts.master')

@section('content')
<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-4">
    <div>
        <div class="text-muted small text-uppercase fw-semibold mb-1">Attendance</div>
        <h1 class="h3 mb-1 fw-bold">Attendance Records</h1>
        <p class="text-muted mb-0">Monitor employee attendance, working hours, and daily activity.</p>
    </div>
    @can('attendance-create')
        <a class="btn btn-primary" href="{{ route('attendances.create') }}">
            <span aria-hidden="true">+</span> Mark Attendance
        </a>
    @endcan
</div>

@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">Employee</th>
                        <th>Date</th>
                        <th>Check In</th>
                        <th>Check Out</th>
                        <th>Duration</th>
                        <th>Status</th>
                        <th>Recorded By</th>
                        <th class="text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($attendances as $attendance)
                        <tr>
                            <td class="ps-4">
                                <div class="fw-semibold">{{ $attendance->user->name }}</div>
                                @if($attendance->user->email)
                                    <div class="small text-muted">{{ $attendance->user->email }}</div>
                                @endif
                            </td>
                            <td>{{ \Carbon\Carbon::parse($attendance->attendance_date)->format('M d, Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($attendance->start_time)->format('h:i A') }}</td>
                            <td>
                                @if($attendance->end_time)
                                    {{ \Carbon\Carbon::parse($attendance->end_time)->format('h:i A') }}
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td>{{ $attendance->working_duration ?? '—' }}</td>
                            <td>
                                @if($attendance->status === 'Working')
                                    <span class="badge rounded-pill text-bg-primary">Working</span>
                                @else
                                    <span class="badge rounded-pill text-bg-success">Completed</span>
                                @endif
                            </td>
                            <td>{{ $attendance->attendedBy->name ?? '—' }}</td>
                            <td class="text-end pe-4">
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-light border" type="button" data-bs-toggle="dropdown" aria-expanded="false" aria-label="Attendance actions">
                                        ⋮
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li><a class="dropdown-item" href="{{ route('attendances.show', $attendance) }}">View details</a></li>
                                        @can('attendance-edit')
                                            <li><a class="dropdown-item" href="{{ route('attendances.edit', $attendance) }}">Edit</a></li>
                                        @endcan
                                        @can('attendance-report')
                                            <li><a class="dropdown-item" href="{{ route('attendances.report', $attendance->user) }}">Employee report</a></li>
                                        @endcan
                                        @can('attendance-delete')
                                            <li><hr class="dropdown-divider"></li>
                                            <li>
                                                <form action="{{ route('attendances.destroy', $attendance) }}" method="POST" onsubmit="return confirm('Delete this attendance record? This action cannot be undone.');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="dropdown-item text-danger">Delete</button>
                                                </form>
                                            </li>
                                        @endcan
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-5">
                                <div class="fs-1 mb-2" aria-hidden="true">📋</div>
                                <h2 class="h5 mb-1">No attendance records</h2>
                                <p class="text-muted mb-3">Attendance records will appear here once they are created.</p>
                                @can('attendance-create')
                                    <a href="{{ route('attendances.create') }}" class="btn btn-primary btn-sm">Mark Attendance</a>
                                @endcan
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($attendances->hasPages())
        <div class="card-footer bg-white border-0 py-3">
            {{ $attendances->links('pagination::bootstrap-4') }}
        </div>
    @endif
</div>
@endsection
