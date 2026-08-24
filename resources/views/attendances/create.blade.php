@extends('layouts.master')

@section('content')
<div class="row">
    <div class="col-lg-12 margin-tb mb-4">
        <div class="pull-left">
            <h2>Create Attendance</h2>
        </div>
        <div class="float-end">
            <a class="btn btn-primary" href="{{ route('attendances.index') }}">Back</a>
        </div>
    </div>
</div>

@if ($errors->any())
<div class="alert alert-danger">
    <strong>Please fix the following:</strong>
    <ul class="mb-0">
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<form action="{{ route('attendances.store') }}" method="POST">
    @csrf
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-6 mb-3">
            <label for="start_time"><strong>Start Time:</strong></label>
            <input id="start_time" type="time" name="start_time" value="{{ old('start_time') }}" class="form-control" required>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-6 mb-3">
            <label for="end_time"><strong>End Time:</strong></label>
            <input id="end_time" type="time" name="end_time" value="{{ old('end_time') }}" class="form-control">
            <small class="text-muted">Leave blank for an active/open attendance record.</small>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-6 mb-3">
            <label for="attendance_date"><strong>Attendance Date:</strong></label>
            <input id="attendance_date" type="date" name="attendance_date" value="{{ old('attendance_date', now()->toDateString()) }}" class="form-control" required>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-6 mb-3">
            <label for="user_id"><strong>User:</strong></label>
            <select name="user_id" id="user_id" class="form-control @error('user_id') is-invalid @enderror" required>
                <option value="">Select employee</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}" @selected(old('user_id') == $user->id)>{{ $user->name }}</option>
                @endforeach
            </select>
            @error('user_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-12 mb-3 text-center">
            <button type="submit" class="btn btn-primary">Create Attendance</button>
        </div>
    </div>
</form>
@endsection
