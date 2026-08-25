@extends('layouts.master')

@section('content')
<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-4">
    <div>
        <h1 class="h3 mb-1">Edit Employee</h1>
        <p class="text-muted mb-0">Update account details and access roles for {{ $user->name }}.</p>
    </div>
    <a class="btn btn-outline-secondary" href="{{ route('users.show', $user) }}">View employee</a>
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
        <form action="{{ route('users.update', $user) }}" method="POST">
            @csrf
            @method('PATCH')
            <div class="row g-4">
                <div class="col-12">
                    <h2 class="h5 mb-1">Account information</h2>
                    <p class="text-muted small mb-0">Leave the password fields empty to keep the current password.</p>
                </div>

                <div class="col-md-6">
                    <label for="name" class="form-label">Full name <span class="text-danger">*</span></label>
                    <input id="name" type="text" name="name" value="{{ old('name', $user->name) }}" class="form-control @error('name') is-invalid @enderror" autocomplete="name" required>
                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6">
                    <label for="email" class="form-label">Email address <span class="text-danger">*</span></label>
                    <input id="email" type="email" name="email" value="{{ old('email', $user->email) }}" class="form-control @error('email') is-invalid @enderror" autocomplete="email" required>
                    @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6">
                    <label for="password" class="form-label">New password</label>
                    <input id="password" type="password" name="password" class="form-control @error('password') is-invalid @enderror" autocomplete="new-password">
                    @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6">
                    <label for="password_confirmation" class="form-label">Confirm new password</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" class="form-control" autocomplete="new-password">
                </div>

                <div class="col-12">
                    <label for="roles" class="form-label">Roles <span class="text-danger">*</span></label>
                    @php($selectedRoles = old('roles', $user->getRoleNames()->toArray()))
                    <select id="roles" class="form-select @error('roles') is-invalid @enderror" multiple name="roles[]" required>
                        @foreach ($roles as $role)
                            <option value="{{ $role }}" @selected(in_array($role, $selectedRoles, true))>{{ $role }}</option>
                        @endforeach
                    </select>
                    @error('roles') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    <div class="form-text">Hold Ctrl/Cmd to select more than one role.</div>
                </div>

                <div class="col-12 d-flex flex-column flex-sm-row justify-content-end gap-2 pt-2">
                    <a href="{{ route('users.show', $user) }}" class="btn btn-light">Cancel</a>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
