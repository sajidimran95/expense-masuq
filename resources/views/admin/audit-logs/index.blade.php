@extends('admin.layouts.app')

@section('title', 'Audit Logs')
@section('page-title', 'Audit Logs')

@section('content')
    <div class="card expense-card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.audit-logs.index') }}" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label for="module" class="form-label fw-bold">Module</label>
                    <select name="module" id="module" class="form-select">
                        <option value="">সব Module</option>
                        @foreach ($modules as $module)
                            <option value="{{ $module }}" @selected(request('module') === $module)>{{ $module }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="action" class="form-label fw-bold">Action</label>
                    <select name="action" id="action" class="form-select">
                        <option value="">সব Action</option>
                        @foreach ($actions as $action)
                            <option value="{{ $action }}" @selected(request('action') === $action)>{{ ucfirst($action) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fa-solid fa-filter me-1"></i> Filter
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="card expense-card">
        <div class="card-header border-0">
            <h3 class="card-title fw-bold">কে কখন কী change করেছে</h3>
            <p class="text-secondary mb-0">Expense, settings এবং staff/user change এখানে দেখা যাবে।</p>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive d-none d-md-block">
                <table class="table align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>সময়</th>
                            <th>User</th>
                            <th>Email / ID</th>
                            <th>Module</th>
                            <th>Action</th>
                            <th>Description</th>
                            <th>Changed Values</th>
                            <th>IP</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($logs as $log)
                            <tr>
                                <td class="fw-semibold">{{ $log->created_at->format('d-m-Y h:i A') }}</td>
                                <td>{{ $log->user_name ?: 'System' }}</td>
                                <td>
                                    @if ($log->user_id)
                                        <div class="fw-bold">ID: {{ $log->user_id }}</div>
                                        <div class="text-secondary">{{ $log->user_email ?: $log->user?->email ?: '-' }}</div>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>{{ $log->module }}</td>
                                <td><span class="badge text-bg-{{ $log->action === 'deleted' ? 'danger' : ($log->action === 'updated' ? 'warning' : 'success') }}">{{ ucfirst($log->action) }}</span></td>
                                <td>{{ $log->description }}</td>
                                <td>
                                    @if ($log->action === 'updated')
                                        <details>
                                            <summary>View</summary>
                                            <div class="audit-values">
                                                <strong>Old:</strong>
                                                <pre>{{ json_encode($log->old_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                                <strong>New:</strong>
                                                <pre>{{ json_encode($log->new_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                            </div>
                                        </details>
                                    @else
                                        <details>
                                            <summary>View</summary>
                                            <pre class="audit-values">{{ json_encode($log->new_values ?? $log->old_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                        </details>
                                    @endif
                                </td>
                                <td>{{ $log->ip_address ?: '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="py-5 text-center text-secondary">কোনো audit log পাওয়া যায়নি।</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mobile-list d-md-none">
                @forelse ($logs as $log)
                    <div class="mobile-list-card">
                        <div class="mobile-list-top">
                            <div class="mobile-list-icon"><i class="fa-solid fa-clock-rotate-left"></i></div>
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between gap-2">
                                    <div>
                                        <h4>{{ $log->module }}</h4>
                                        <p>{{ $log->description }}</p>
                                    </div>
                                    <strong>{{ ucfirst($log->action) }}</strong>
                                </div>
                            </div>
                        </div>
                        <div class="mobile-list-meta">
                            <span><i class="fa-regular fa-user me-1"></i>{{ $log->user_name ?: 'System' }}</span>
                            <span><i class="fa-regular fa-id-card me-1"></i>ID: {{ $log->user_id ?: '-' }}</span>
                            <span><i class="fa-regular fa-envelope me-1"></i>{{ $log->user_email ?: $log->user?->email ?: '-' }}</span>
                            <span><i class="fa-regular fa-clock me-1"></i>{{ $log->created_at->format('d-m-Y h:i A') }}</span>
                            <span><i class="fa-solid fa-network-wired me-1"></i>{{ $log->ip_address ?: '-' }}</span>
                        </div>
                    </div>
                @empty
                    <div class="mobile-list-empty">
                        <i class="fa-solid fa-clock-rotate-left"></i>
                        <p>কোনো audit log পাওয়া যায়নি।</p>
                    </div>
                @endforelse
            </div>
        </div>

        @if ($logs->hasPages())
            <div class="card-footer bg-white">{{ $logs->links() }}</div>
        @endif
    </div>
@endsection
