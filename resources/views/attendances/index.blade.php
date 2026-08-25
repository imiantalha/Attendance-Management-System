@extends('layouts.master')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-4">
        <div>
            <h1 class="h3 mb-1 fw-bold">Attendance</h1>
            <p class="text-muted mb-0">Review, manage and track employee attendance.</p>
        </div>
        @can('attendance-create')
            <a class="btn btn-dark" href="{{ route('attendances.create') }}">Mark Attendance</a>
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
            @if ($attendances->isEmpty())
                <div class="text-center p-5">
                    <h2 class="h5">No attendance records</h2>
                    <p class="text-muted mb-0">Attendance records will appear here once they are created.</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="px-4 py-3">Employee</th>
                                <th class="py-3">Date</th>
                                <th class="py-3">Check in</th>
                                <th class="py-3">Check out</th>
                                <th class="py-3">Recorded by</th>
                                <th class="py-3 text-end px-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($attendances as $attendance)
                                <tr>
                                    <td class="px-4">
                                        <div class="fw-semibold">{{ $attendance->user?->name ?? 'Unknown employee' }}</div>
                                        <div class="small text-muted">{{ $attendance->user?->email }}</div>
                                    </td>
                                    <td>{{ optional($attendance->attendance_date)->format('d M Y') }}</td>
                                    <td>{{ $attendance->start_time ? \Carbon\Carbon::parse($attendance->start_time)->format('h:i A') : '—' }}</td>
                                    <td>{{ $attendance->end_time ? \Carbon\Carbon::parse($attendance->end_time)->format('h:i A') : '—' }}</td>
                                    <td>{{ $attendance->attendedBy?->name ?? 'System' }}</td>
                                    <td class="text-end px-4">
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                Actions
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                @can('view', $attendance)
                                                    <li><a class="dropdown-item" href="{{ route('attendances.show', $attendance) }}">View</a></li>
                                                @endcan
                                                @can('update', $attendance)
                                                    <li><a class="dropdown-item" href="{{ route('attendances.edit', $attendance) }}">Edit</a></li>
                                                @endcan
                                                @can('report', $attendance)
                                                    <li><a class="dropdown-item" href="{{ route('attendances.report', $attendance->user) }}">Report</a></li>
                                                @endcan
                                                @can('delete', $attendance)
                                                    <li><hr class="dropdown-divider"></li>
                                                    <li>
                                                        <form action="{{ route('attendances.destroy', $attendance) }}" method="POST" onsubmit="return confirm('Delete this attendance record? This action cannot be undone.')">
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
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="border-top p-3">
                    {{ $attendances->links('pagination::bootstrap-4') }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
