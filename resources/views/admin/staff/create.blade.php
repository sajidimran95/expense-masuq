@extends('admin.layouts.app')

@section('title', 'Add Staff')
@section('page-title', 'Add Staff')

@section('content')
    <form method="POST" action="{{ route('admin.staff.store') }}">
        @csrf

        <div class="card expense-card">
            <div class="card-header border-0 bg-white">
                <div class="d-flex flex-column flex-md-row justify-content-between gap-3">
                    <div>
                        <h3 class="card-title fw-bold">নতুন Staff তৈরি করুন</h3>
                        <p class="text-secondary mb-0">Role এবং feature-wise access select করুন।</p>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.staff.index') }}" class="btn btn-outline-secondary">ফিরে যান</a>
                        <button type="submit" class="btn btn-success">Save</button>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-bold">নাম <span class="text-danger">*</span></label>
                        <input type="text" name="name" value="{{ old('name') }}" class="form-control @error('name') is-invalid @enderror" required>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">ইমেইল <span class="text-danger">*</span></label>
                        <input type="email" name="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" required>
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Password <span class="text-danger">*</span></label>
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                        @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Confirm Password <span class="text-danger">*</span></label>
                        <input type="password" name="password_confirmation" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Role Type</label>
                        <select name="role" class="form-select" data-role-select>
                            <option value="staff" @selected(old('role', 'staff') === 'staff')>Staff</option>
                            <option value="super_admin" @selected(old('role') === 'super_admin')>Super Admin</option>
                        </select>
                        <small class="text-secondary">Super Admin সব feature access পাবে।</small>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Status</label>
                        <div class="form-check form-switch mt-2">
                            <input type="hidden" name="is_active" value="0">
                            <input type="checkbox" name="is_active" value="1" class="form-check-input" id="is_active" @checked(old('is_active', true))>
                            <label for="is_active" class="form-check-label">Active</label>
                        </div>
                    </div>
                </div>

                <hr class="my-4">

                <div data-permissions-box>
                    <h5 class="fw-bold">Feature Access</h5>
                    <div class="row g-3">
                        @foreach ($permissions as $key => $label)
                            <div class="col-md-6">
                                <label class="permission-card">
                                    <input type="checkbox" name="permissions[]" value="{{ $key }}" @checked(in_array($key, old('permissions', []), true))>
                                    <span>{{ $label }}</span>
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </form>

    @include('admin.staff.partials.role-script')
@endsection
