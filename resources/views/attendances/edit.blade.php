@extends('layouts.master')

@section('content')
<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-4">
    <div>
        <div class="text-muted small text-uppercase fw-semibold mb-1">Attendance</div>
        <h1 class="h3 mb-1 fw-bold">Edit Attendance</h1>
        <p class="text-muted mb-0">Update the employee's attendance record carefully.</p>
    </div>
    <a class="btn btn-light border" href="{{ route('attendances.index') }}">Back to attendance</a>
</div>

@if ($errors->any())
    <div class="alert alert-danger" role="alert">
        <div class="fw-semibold mb-1">Please review the form.</div>
        <ul class="mb-0 ps-3">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="card border-0 shadow-sm">
    <div class="card-body p-4 p-lg-5">
        <form action="{{ route('attendances.update', $attendance) }}" method="POST" novalidate>
            @csrf
            @method('PUT')

            <div class="row g-4">
                <div class="col-12">
                    <label for="user_id" class="form-label fw-semibold">Employee</label>
                    <select name="user_id" id="user_id" class="form-select @error('user_id') is-invalid @enderror" required>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" @selected(old('user_id', $attendance->user_id) == $user->id)>
                                {{ $user->name }}{{ $user->email ? ' — '.$user->email : '' }}
                            </option>
                        @endforeach
                    </select>
                    @error('user_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12 col-md-4">
                    <label for="attendance_date" class="form-label fw-semibold">Attendance date</label>
                    <input id="attendance_date" type="date" name="attendance_date" class="form-control @error('attendance_date') is-invalid @enderror" value="{{ old('attendance_date', $attendance->attendance_date?->format('Y-m-d')) }}" required>
                    @error('attendance_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12 col-md-4">
                    <label for="start_time" class="form-label fw-semibold">Check-in time</label>
                    <input id="start_time" type="time" name="start_time" class="form-control @error('start_time') is-invalid @enderror" value="{{ old('start_time', \Carbon\Carbon::parse($attendance->start_time)->format('H:i')) }}" required>
                    @error('start_time')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12 col-md-4">
                    <label for="end_time" class="form-label fw-semibold">Check-out time <span class="text-muted fw-normal">(optional)</span></label>
                    <input id="end_time" type="time" name="end_time" class="form-control @error('end_time') is-invalid @enderror" value="{{ old('end_time', $attendance->end_time ? \Carbon\Carbon::parse($attendance->end_time)->format('H:i') : '') }}">
                    <div class="form-text">Leave blank while the employee is still working.</div>
                    @error('end_time')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12 d-flex flex-column flex-sm-row justify-content-end gap-2 pt-2">
                    <a href="{{ route('attendances.index') }}" class="btn btn-light border">Cancel</a>
                    <button type="submit" class="btn btn-primary px-4">Save Changes</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
