@extends('layouts.master')

@section('content')
<div class="row">
    <div class="col-lg-12 margin-tb mb-4">
        <div class="pull-left">
            <h2>Create Attendance
                <div class="float-end">
                    <a class="btn btn-primary" href="{{ route('attendances.index') }}"> Back</a>
                </div>
            </h2>
        </div>
    </div>
</div>

@if (count($errors) > 0)
<div class="alert alert-danger">
    <strong>Whoops!</strong> There were some problems with your input.<br><br>
    <ul>
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<form action="{{ route('attendances.store') }}" method="POST">
    @csrf
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 mb-3">
            <div class="form-group">
                <strong>Start Time:</strong>
                <input type="time" name="start_time" class="form-control" placeholder="{{ \Carbon\Carbon::now('Asia/Karachi')->format('H:i') }}">
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12 mb-3">
            <div class="form-group">
                <strong>End Time:</strong>
                <input type="time" name="end_time" class="form-control" placeholder="HH:MM">
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12 mb-3">
            <div class="form-group">
                <strong>Attendance Date:</strong>
                <input type="date" name="attendance_date" class="form-control" placeholder="{{ \Carbon\Carbon::now('Asia/Karachi')->format('d/m/Y') }}">
            </div>
        </div>
        <div class="form-group">
            <label for="user_id"><strong>User:</strong></label>
            <select name="user_id" id="user_id" class="form-control @error('user_id') is-invalid @enderror">
                @foreach($users as $user)
                    <option value="{{ $user->id }}"> {{ $user->name }} </option>
                @endforeach
            </select>
            @error('user_id')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-xs-12 col-sm-12 col-md-12 mb-3">
            <div class="form-group">
                <strong>Attendance By:</strong>
                <input type="hidden" name="attendance_by" value="{{ Auth::user()->id }}">
                <strong>{{ Auth::user()->name }}</strong>
            </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-12 text-center">
            <button type="submit" class="btn btn-primary">Submit</button>
        </div>
    </div>
</form>

@endsection