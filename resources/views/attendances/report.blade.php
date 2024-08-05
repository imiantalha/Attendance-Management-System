@extends('layouts.master')

@section('content')
<div class="row">
    <div class="col-lg-12 margin-tb mb-4">
        <div class="pull-left">
            <h2>Attendance Reports
                <div class="float-end">
                    @if ($attendances->isNotEmpty())
                    @php
                    $userId = $attendances->first()->user->id;
                    @endphp
                    <a class="btn btn-success" href="{{ route('attendances.weekly.report', $userId) }}"> Weekly </a>
                    <a class="btn btn-success" href="{{ route('attendances.monthly.report', $userId) }}"> Monthly </a>
                    <a class="btn btn-success" href="{{ route('attendances.yearly.report', $userId) }}"> Yearly </a>
                    @endif
                    <a class="btn btn-primary" href="{{ route('attendances.index') }}"> Back</a>
                </div>
            </h2>
        </div>
    </div>
</div>


<table class="table table-striped table-hover">
    <tr>
        <th>User Name</th>
        <th>Date</th>
        <th>Start Time</th>
        <th>End Time</th>
        <th>Time Spent</th>
        <th>Attendance By</th>
    </tr>
    @php
    $totalMinutesOfWork = 0;
    @endphp
    @foreach ($attendances as $attendance)
    <tr>
        <td>{{ $attendance->user->name }}</td>
        <td>{{ $attendance->attendance_date }}</td>
        <td>{{ \Carbon\Carbon::parse($attendance->start_time)->format('h:i A') }}</td>
        <td>{{ \Carbon\Carbon::parse($attendance->end_time)->format('h:i A') }}</td>
        @php
        $startTime = \Carbon\Carbon::parse($attendance->start_time);
        $endTime = \Carbon\Carbon::parse($attendance->end_time);

        // Check if the end time is on the next day
        if ($endTime->lessThanOrEqualTo($startTime)) {
        $endTime->addDay();
        }

        // Calculate the total difference
        $totalMinutes = $startTime->diffInMinutes($endTime);
        $totalMinutesOfWork += $totalMinutes;

        $hours = floor($totalMinutes / 60);
        $minutes = $totalMinutes % 60;
        @endphp

        <td>{{ str_pad($hours, 2, '0', STR_PAD_LEFT) }}:{{ str_pad($minutes, 2, '0', STR_PAD_LEFT) }}</td>
        <td>{{ $attendance->attendedBy->name }}</td>
    </tr>
    @endforeach
    @php
    $totalHoursOfWork = floor($totalMinutesOfWork / 60);
    $remainingMinutesOfWork = $totalMinutesOfWork % 60;
    @endphp
    <tr>
        <td colspan="5"></td>
    </tr>
    <tr>
        <td colspan="4" class="text-right"><strong>Total Time Spent:</strong></td>
        <td><strong>{{ str_pad($totalHoursOfWork, 2, '0', STR_PAD_LEFT) }}:{{ str_pad($remainingMinutesOfWork, 2, '0', STR_PAD_LEFT) }}</strong></td>
    </tr>
</table>


@endsection