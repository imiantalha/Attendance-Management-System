@extends('layouts.master')

@section('content')
<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-4">
    <div>
        <h1 class="h3 mb-1">Employees</h1>
        <p class="text-muted mb-0">Manage employee accounts, roles, and access.</p>
    </div>
    @can('user-create')
        <a class="btn btn-primary" href="{{ route('users.create') }}">
            <span aria-hidden="true">+</span> Add Employee
        </a>
    @endcan
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body border-bottom">
        <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-2">
            <div>
                <h2 class="h6 mb-1">Employee Directory</h2>
                <p class="text-muted small mb-0">{{ $data->count() }} employee{{ $data->count() === 1 ? '' : 's' }} shown</p>
            </div>
        </div>
    </div>

    @if ($data->isEmpty())
        <div class="card-body text-center py-5">
            <div class="display-6 mb-3">👥</div>
            <h3 class="h5">No employees yet</h3>
            <p class="text-muted mb-4">Create the first employee account to start managing attendance.</p>
            @can('user-create')
                <a href="{{ route('users.create') }}" class="btn btn-primary">Add Employee</a>
            @endcan
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">Employee</th>
                        <th>Email</th>
                        <th>Roles</th>
                        <th class="text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $user)
                        <tr>
                            <td class="ps-4">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="rounded-circle bg-primary-subtle text-primary d-flex align-items-center justify-content-center fw-semibold" style="width:42px;height:42px;">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <a href="{{ route('users.show', $user) }}" class="fw-semibold text-decoration-none text-dark">{{ $user->name }}</a>
                                        <div class="small text-muted">Employee #{{ $user->id }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $user->email }}</td>
                            <td>
                                <div class="d-flex flex-wrap gap-1">
                                    @forelse ($user->getRoleNames() as $role)
                                        <span class="badge rounded-pill bg-secondary">{{ $role }}</span>
                                    @empty
                                        <span class="text-muted small">No role assigned</span>
                                    @endforelse
                                </div>
                            </td>
                            <td class="text-end pe-4">
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        Actions
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li><a class="dropdown-item" href="{{ route('users.show', $user) }}">View profile</a></li>
                                        @can('user-edit')
                                            <li><a class="dropdown-item" href="{{ route('users.edit', $user) }}">Edit employee</a></li>
                                        @endcan
                                        @can('attendance-list')
                                            <li><a class="dropdown-item" href="{{ route('attendances.report', $user) }}">Attendance report</a></li>
                                        @endcan
                                        @can('user-delete')
                                            <li><hr class="dropdown-divider"></li>
                                            <li>
                                                <form action="{{ route('users.destroy', $user) }}" method="POST" onsubmit="return confirm('Delete this employee? This action cannot be undone.');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="dropdown-item text-danger">Delete employee</button>
                                                </form>
                                            </li>
                                        @endcan
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
