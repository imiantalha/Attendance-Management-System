@extends('layouts.master')

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb mb-4">
            <div class="pull-left">
                <h2>Create New User</h2>
            </div>
            <div class="float-end">
                <a class="btn btn-primary" href="{{ route('users.index') }}">Back</a>
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

    <form action="{{ route('users.store') }}" method="POST">
        @csrf
        <div class="row">
            <div class="col-xs-12 mb-3">
                <label for="name" class="form-label"><strong>Name:</strong></label>
                <input id="name" type="text" name="name" value="{{ old('name') }}" class="form-control" placeholder="Name" required>
            </div>
            <div class="col-xs-12 mb-3">
                <label for="email" class="form-label"><strong>Email:</strong></label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" class="form-control" placeholder="Email" required>
            </div>
            <div class="col-xs-12 mb-3">
                <label for="password" class="form-label"><strong>Password:</strong></label>
                <input id="password" type="password" name="password" class="form-control" placeholder="Password" required>
            </div>
            <div class="col-xs-12 mb-3">
                <label for="password_confirmation" class="form-label"><strong>Confirm Password:</strong></label>
                <input id="password_confirmation" type="password" name="password_confirmation" class="form-control" placeholder="Confirm Password" required>
            </div>
            <div class="col-xs-12 mb-3">
                <label for="roles" class="form-label"><strong>Role:</strong></label>
                <select id="roles" class="form-control" multiple name="roles[]" required>
                    @foreach ($roles as $role)
                        <option value="{{ $role }}" @selected(in_array($role, old('roles', []), true))>{{ $role }}</option>
                    @endforeach
                </select>
                <small class="text-muted">Hold Ctrl/Cmd to select multiple roles.</small>
            </div>
            <div class="col-xs-12 mb-3 text-center">
                <button type="submit" class="btn btn-primary">Create User</button>
            </div>
        </div>
    </form>
@endsection
