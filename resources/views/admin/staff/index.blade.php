@extends('admin.layouts.app')

@section('title', 'Staff Users')
@section('page-title', 'Staff Users')

@section('content')
    @if (session('status'))
        <div class="alert alert-success rounded-4">{{ session('status') }}</div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger rounded-4">{{ $errors->first() }}</div>
    @endif

    <div class="card expense-card">
        <div class="card-header border-0">
            <div class="d-flex align-items-center justify-content-between gap-3">
                <div>
                    <h3 class="card-title fw-bold">Staff/User তালিকা</h3>
                    <p class="text-secondary mb-0">Feature-wise access সহ staff account ম্যানেজ করুন।</p>
                </div>
                <a href="{{ route('admin.staff.create') }}" class="btn btn-primary btn-sm">
                    <i class="fa-solid fa-user-plus me-1"></i> Add Staff
                </a>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive d-none d-md-block">
                <table class="table align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>নাম</th>
                            <th>ইমেইল</th>
                            <th>Role</th>
                            <th>Permissions</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($staffUsers as $staff)
                            <tr>
                                <td class="fw-bold">{{ $staff->name }}</td>
                                <td>{{ $staff->email }}</td>
                                <td><span class="badge text-bg-{{ $staff->isSuperAdmin() ? 'primary' : 'secondary' }}">{{ $staff->isSuperAdmin() ? 'Super Admin' : 'Staff' }}</span></td>
                                <td>
                                    @foreach (($staff->permissions ?? []) as $permission)
                                        <span class="badge text-bg-light border me-1">{{ \App\Models\User::permissionLabels()[$permission] ?? $permission }}</span>
                                    @endforeach
                                </td>
                                <td><span class="badge text-bg-{{ $staff->is_active ? 'success' : 'danger' }}">{{ $staff->is_active ? 'Active' : 'Inactive' }}</span></td>
                                <td>
                                    <div class="d-flex gap-1">
                                        <a href="{{ route('admin.staff.edit', $staff) }}" class="btn btn-warning btn-sm text-white">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </a>
                                        <form method="POST" action="{{ route('admin.staff.destroy', $staff) }}" onsubmit="return confirm('আপনি কি এই staff delete করতে চান?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" @disabled($staff->is(auth()->user()))>
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mobile-list d-md-none">
                @foreach ($staffUsers as $staff)
                    <div class="mobile-list-card">
                        <div class="mobile-list-top">
                            <div class="mobile-list-icon"><i class="fa-solid fa-user-shield"></i></div>
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between gap-2">
                                    <div>
                                        <h4>{{ $staff->name }}</h4>
                                        <p>{{ $staff->email }}</p>
                                    </div>
                                    <strong>{{ $staff->isSuperAdmin() ? 'Admin' : 'Staff' }}</strong>
                                </div>
                            </div>
                        </div>

                        <div class="mobile-list-meta">
                            <span>{{ $staff->is_active ? 'Active' : 'Inactive' }}</span>
                            <span>{{ collect($staff->permissions ?? [])->map(fn ($permission) => \App\Models\User::permissionLabels()[$permission] ?? $permission)->join(', ') ?: 'No permission' }}</span>
                        </div>

                        <div class="mobile-list-actions">
                            <a href="{{ route('admin.staff.edit', $staff) }}" class="btn btn-warning btn-sm text-white">
                                <i class="fa-solid fa-pen-to-square me-1"></i> Edit
                            </a>
                            <form method="POST" action="{{ route('admin.staff.destroy', $staff) }}" onsubmit="return confirm('আপনি কি এই staff delete করতে চান?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" @disabled($staff->is(auth()->user()))>
                                    <i class="fa-solid fa-trash me-1"></i> Delete
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        @if ($staffUsers->hasPages())
            <div class="card-footer bg-white">{{ $staffUsers->links() }}</div>
        @endif
    </div>
@endsection
