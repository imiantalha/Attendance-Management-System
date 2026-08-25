@extends('layouts.master')

@section('content')
<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-4">
    <div>
        <h1 class="h3 mb-1">Add Employee</h1>
        <p class="text-muted mb-0">Create an account and assign the appropriate access roles.</p>
    </div>
    <a class="btn btn-outline-secondary" href="{{ route('users.index') }}">Back to employees</a>
</div>

@if ($errors->any())
    <div class="alert alert-danger" role="alert">
        <strong>Please review the highlighted fields.</strong>
        <ul class="mb-0 mt-2">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="card border-0 shadow-sm">
    <div class="card-body p-4 p-lg-5">
        <form action="{{ route('users.store') }}" method="POST">
            @csrf
            <div class="row g-4">
                <div class="col-12">
                    <h2 class="h5 mb-1">Account information</h2>
                    <p class="text-muted small mb-0">Use the employee's work details.</p>
                </div>

                <div class="col-md-6">
                    <label for="name" class="form-label">Full name <span class="text-danger">*</span></label>
                    <input id="name" type="text" name="name" value="{{ old('name') }}" class="form-control @error('name') is-invalid @enderror" placeholder="e.g. Ali Khan" autocomplete="name" required>
                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6">
                    <label for="email" class="form-label">Email address <span class="text-danger">*</span></label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" placeholder="name@company.com" autocomplete="email" required>
                    @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6">
                    <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                    <input id="password" type="password" name="password" class="form-control @error('password') is-invalid @enderror" autocomplete="new-password" required>
                    @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6">
                    <label for="password_confirmation" class="form-label">Confirm password <span class="text-danger">*</span></label>
                    <input id="password_confirmation" type="password" name="password_confirmation" class="form-control" autocomplete="new-password" required>
                </div>

                <div class="col-12">
                    <label for="roles" class="form-label">Roles <span class="text-danger">*</span></label>
                    <select id="roles" class="form-select @error('roles') is-invalid @enderror" multiple name="roles[]" required>
                        @foreach ($roles as $role)
                            <option value="{{ $role }}" @selected(in_array($role, old('roles', []), true))>{{ $role }}</option>
                        @endforeach
                    </select>
                    @error('roles') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    <div class="form-text">Hold Ctrl/Cmd to select more than one role.</div>
                </div>

                <div class="col-12 d-flex flex-column flex-sm-row justify-content-end gap-2 pt-2">
                    <a href="{{ route('users.index') }}" class="btn btn-light">Cancel</a>
                    <button type="submit" class="btn btn-primary">Create employee</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
