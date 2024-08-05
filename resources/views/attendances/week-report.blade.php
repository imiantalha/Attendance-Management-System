@extends('layouts.master')

@section('content')
<div class="row">
    <div class="col-lg-12 margin-tb mb-4">
        <div class="pull-left">
            <h2>User's Total Time
                <div class="float-end">
                    @if ($attendances->isNotEmpty())
                    @php
                    $userId = $attendances->first()->user->id;
                    @endphp
                    <a class="btn btn-primary" href="{{ route('attendances.report', $userId) }}"> Back</a>
                    @else
                    <a class="btn btn-primary" href="{{ route('attendances.index') }}"> Back</a>
                    @endif
                </div>
            </h2>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12 margin-tb mb-4">
        <h3>Total Working Time: 
            @php
            $totalHoursOfWork = floor($totalWorkingMinutes / 60);
            $remainingMinutesOfWork = $totalWorkingMinutes % 60;
            @endphp
            <strong>{{ str_pad($totalHoursOfWork, 2, '0', STR_PAD_LEFT) }}:{{ str_pad($remainingMinutesOfWork, 2, '0', STR_PAD_LEFT) }}</strong>
        </h3>
    </div>
</div>

<table class="table table-striped table-hover">
    <tr>
        <th>User Name</th>
        <th>Date</th>
        <th>Start Time</th>
        <th>End Time</th>
        <th>Time Spent</th>
    </tr>
    @foreach ($attendances as $attendance)
    <tr>
        <td>{{ $attendance->user->name }}</td>
        <td>{{ $attendance->attendance_date }}</td>
        <td>{{ $attendance->start_time }}</td>
        <td>{{ $attendance->end_time }}</td>
        @php
        $startTime = \Carbon\Carbon::parse($attendance->start_time);
        $endTime = \Carbon\Carbon::parse($attendance->end_time);
        if ($endTime->format('H:i') == '00:00') {
            $endTime->addDay();
        }
        $oneDayMinutes = $startTime->diffInMinutes($endTime); 
        $hours = floor($oneDayMinutes / 60);
        $minutes = $oneDayMinutes % 60;
        @endphp
        <td>{{ str_pad($hours, 2, '0', STR_PAD_LEFT) }}:{{ str_pad($minutes, 2, '0', STR_PAD_LEFT) }}</td>
    </tr>
    @endforeach
</table>

@endsection
