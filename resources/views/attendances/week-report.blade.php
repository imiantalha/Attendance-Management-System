@extends('layouts.master')

@section('content')
<div class="row">
    <div class="col-lg-12 margin-tb mb-4">
        <div class="pull-left">
            <h2>Attendance Summary</h2>
        </div>
        <div class="float-end">
            <a class="btn btn-primary" href="{{ route('attendances.report', $user) }}">Back</a>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-lg-12">
        @php
            $totalHoursOfWork = intdiv($totalWorkingMinutes, 60);
            $remainingMinutesOfWork = $totalWorkingMinutes % 60;
        @endphp
        <h3>Total Working Time:
            <strong>{{ sprintf('%02d:%02d', $totalHoursOfWork, $remainingMinutesOfWork) }}</strong>
        </h3>
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
        </tr>
    @endforeach
    </tbody>
</table>
</div>
@endsection
