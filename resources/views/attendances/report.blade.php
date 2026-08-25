@extends('layouts.master')

@section('content')
<div class="row">
    <div class="col-lg-12 margin-tb mb-4">
        <div class="pull-left">
            <h2>Attendance Reports</h2>
        </div>
        <div class="float-end">
            <a class="btn btn-success" href="{{ route('attendances.weekly.report', $user) }}">Weekly</a>
            <a class="btn btn-success" href="{{ route('attendances.monthly.report', $user) }}">Monthly</a>
            <a class="btn btn-success" href="{{ route('attendances.yearly.report', $user) }}">Yearly</a>
            <a class="btn btn-primary" href="{{ route('attendances.index') }}">Back</a>
        </div>
    </div>
</div>

<div class="table-responsive">
<table class="table table-striped table-hover align-middle">
    <thead>
        <tr>
            <th>User Name</th>
            <th>Date</th>
            <th>Start Time</th>
            <th>End Time</th>
            <th>Time Spent</th>
            <th>Attendance By</th>
        </tr>
    </thead>
    <tbody>
    @foreach ($attendances as $attendance)
        @php
            $minutes = $workingMinutes[$attendance->id] ?? 0;
            $hours = intdiv($minutes, 60);
            $remainingMinutes = $minutes % 60;
        @endphp
        <tr>
            <td>{{ $attendance->user->name }}</td>
            <td>{{ $attendance->attendance_date->format('Y-m-d') }}</td>
            <td>{{ \Carbon\Carbon::parse($attendance->start_time)->format('h:i A') }}</td>
            <td>{{ $attendance->end_time ? \Carbon\Carbon::parse($attendance->end_time)->format('h:i A') : '—' }}</td>
            <td>{{ sprintf('%02d:%02d', $hours, $remainingMinutes) }}</td>
            <td>{{ $attendance->attendedBy?->name ?? '—' }}</td>
        </tr>
    @endforeach
    </tbody>
    <tfoot>
        @php
            $totalHours = intdiv($totalWorkingMinutes, 60);
            $totalRemainingMinutes = $totalWorkingMinutes % 60;
        @endphp
        <tr>
            <td colspan="4" class="text-end"><strong>Total Time Spent:</strong></td>
            <td><strong>{{ sprintf('%02d:%02d', $totalHours, $totalRemainingMinutes) }}</strong></td>
            <td></td>
        </tr>
    </tfoot>
</table>
</div>
@endsection
