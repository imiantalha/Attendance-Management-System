@extends('layouts.master')

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb mb-4">
            <div class="pull-left">
                <h2> Show Attendance
                    <div class="float-end">
                        <a class="btn btn-primary" href="{{ route('attendances.index') }}"> Back</a>
                    </div>
                </h2>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12 mb-3">
            <div class="form-group">
                <strong>Start Time:</strong>
                {{ ($attendance->start_time) }}
            </div>
        </div>
        <div class="col-xs-12 mb-3">
            <div class="form-group">
                <strong>End Time:</strong>
                {{ ($attendance->end_time) }}
            </div>
        </div>
        <div class="col-xs-12 mb-3">
            <div class="form-group">
                <strong>Attendance Date:</strong>
                {{ ($attendance->attendance_date) }}
            </div>
        </div>
        <div class="col-xs-12 mb-3">
            <div class="form-group">
                <strong>User:</strong>
                {{ $attendance->user->name }}
            </div>
        </div>
        <div class="col-xs-12 mb-3">
            <div class="form-group">
                <strong>Attendance By:</strong>
                {{ $attendance->attendedBy->name }}
            </div>
        </div>
    </div>
@endsection
