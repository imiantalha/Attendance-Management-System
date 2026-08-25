@extends('layouts.master')

@section('content')
<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-4">
    <div>
        <h1 class="h3 mb-1">Employee Profile</h1>
        <p class="text-muted mb-0">View account details and assigned access.</p>
    </div>
    <div class="d-flex flex-wrap gap-2">
        <a class="btn btn-outline-secondary" href="{{ route('users.index') }}">Back</a>
        @can('user-edit')
            <a class="btn btn-primary" href="{{ route('users.edit', $user) }}">Edit employee</a>
        @endcan
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body text-center p-4">
                <div class="rounded-circle bg-primary-subtle text-primary d-inline-flex align-items-center justify-content-center fw-bold fs-3 mb-3" style="width:88px;height:88px;">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <h2 class="h5 mb-1">{{ $user->name }}</h2>
                <p class="text-muted mb-3">{{ $user->email }}</p>
                <div class="d-flex flex-wrap justify-content-center gap-1">
                    @forelse ($user->getRoleNames() as $role)
                        <span class="badge rounded-pill bg-secondary">{{ $role }}</span>
                    @empty
                        <span class="text-muted small">No role assigned</span>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body p-4">
                <h2 class="h5 mb-4">Account details</h2>
                <dl class="row mb-0">
                    <dt class="col-sm-4 text-muted fw-normal">Employee ID</dt>
                    <dd class="col-sm-8">#{{ $user->id }}</dd>

                    <dt class="col-sm-4 text-muted fw-normal">Full name</dt>
                    <dd class="col-sm-8">{{ $user->name }}</dd>

                    <dt class="col-sm-4 text-muted fw-normal">Email</dt>
                    <dd class="col-sm-8">{{ $user->email }}</dd>

                    <dt class="col-sm-4 text-muted fw-normal">Roles</dt>
                    <dd class="col-sm-8">
                        <div class="d-flex flex-wrap gap-1">
                            @forelse ($user->getRoleNames() as $role)
                                <span class="badge rounded-pill bg-secondary">{{ $role }}</span>
                            @empty
                                <span class="text-muted">No role assigned</span>
                            @endforelse
                        </div>
                    </dd>

                    @if ($user->created_at)
                        <dt class="col-sm-4 text-muted fw-normal">Joined</dt>
                        <dd class="col-sm-8">{{ $user->created_at->format('M d, Y') }}</dd>
                    @endif
                </dl>

                @can('attendance-list')
                    <div class="border-top mt-4 pt-4">
                        <a href="{{ route('attendances.report', $user) }}" class="btn btn-outline-primary">View attendance report</a>
                    </div>
                @endcan
            </div>
        </div>
    </div>
</div>
@endsection
