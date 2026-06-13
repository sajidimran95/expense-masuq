@extends('admin.layouts.app')

@section('title', 'ড্যাশবোর্ড')
@section('page-title', 'খরচ ড্যাশবোর্ড')

@section('content')
    <div class="dashboard-hero expense-card mb-4">
        <div>
            <span class="dashboard-kicker">{{ $currentMonthLabel }}</span>
            <h2>খরচ ম্যানেজমেন্ট ড্যাশবোর্ড</h2>
            <p>মাসিক খরচ, রিপোর্ট, অনুমোদন এবং সাম্প্রতিক এন্ট্রি এক জায়গা থেকে দেখুন।</p>
        </div>
        <div class="dashboard-hero-actions">
            @if (auth()->user()->canAccess('expenses'))
                <a href="{{ route('admin.expenses.create') }}" class="btn btn-light">
                    <i class="fa-solid fa-plus me-1"></i> নতুন খরচ
                </a>
            @endif
            @if (auth()->user()->canAccess('reports'))
                <a href="{{ route('admin.reports.index') }}" class="btn btn-outline-light">
                    <i class="fa-solid fa-chart-pie me-1"></i> রিপোর্ট দেখুন
                </a>
            @endif
        </div>
    </div>

    <div class="row g-3 mb-4">
        @foreach ($stats as $stat)
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="dashboard-stat expense-card dashboard-stat-{{ $stat['tone'] }}">
                    <div>
                        <span>{{ $stat['label'] }}</span>
                        <strong>{{ $stat['value'] }}</strong>
                    </div>
                    <i class="fa-solid {{ $stat['icon'] }}"></i>
                </div>
            </div>
        @endforeach
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card expense-card">
                <div class="card-header border-0">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h3 class="card-title fw-bold">সাম্প্রতিক খরচ</h3>
                            <p class="text-secondary mb-0">নতুন থেকে পুরনো ক্রমে সর্বশেষ খরচ এন্ট্রি।</p>
                        </div>
                        @if (auth()->user()->canAccess('expenses'))
                            <a href="{{ route('admin.expenses.index') }}" class="btn btn-primary btn-sm">
                                সব দেখুন <i class="fa-solid fa-arrow-right ms-1"></i>
                            </a>
                        @endif
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="dashboard-expense-list">
                        @forelse ($recentExpenses as $expense)
                            <a href="{{ auth()->user()->canAccess('expenses') ? route('admin.expenses.show', $expense) : '#' }}" class="dashboard-expense-item">
                                <div class="expense-icon">
                                    <i class="fa-solid fa-receipt"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2">
                                        <h4>{{ $expense->sector }}</h4>
                                        <strong>{{ $money($expense->amount) }}</strong>
                                    </div>
                                    <p>{{ \Illuminate\Support\Str::limit(strip_tags($expense->description), 72) }}</p>
                                    <div class="dashboard-expense-meta">
                                        <span><i class="fa-regular fa-calendar me-1"></i>{{ $bn($expense->expense_date->format('d-m-Y')) }}</span>
                                        <span><i class="fa-solid fa-ticket me-1"></i>{{ $expense->voucher_no ?: 'ভাউচার নেই' }}</span>
                                        <span class="{{ $expense->approval ? 'text-success' : 'text-secondary' }}">
                                            <i class="fa-solid fa-signature me-1"></i>{{ $expense->approval ? 'অনুমোদন আছে' : 'অনুমোদন নেই' }}
                                        </span>
                                    </div>
                                </div>
                            </a>
                        @empty
                            <div class="dashboard-empty">
                                <i class="fa-solid fa-receipt"></i>
                                <h4>এখনো কোনো খরচ নেই</h4>
                                <p>প্রথম খরচ এন্ট্রি যোগ করলে এখানে সাম্প্রতিক তালিকা দেখাবে।</p>
                                @if (auth()->user()->canAccess('expenses'))
                                    <a href="{{ route('admin.expenses.create') }}" class="btn btn-primary btn-sm">খরচ যোগ করুন</a>
                                @endif
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card expense-card">
                <div class="card-header border-0">
                    <h3 class="card-title fw-bold">খাত অনুযায়ী খরচ</h3>
                </div>
                <div class="card-body">
                    @forelse ($topSectors as $sector)
                        @php
                            $maxAmount = max((float) $topSectors->max('total_amount'), 1);
                            $width = min(100, max(8, ((float) $sector->total_amount / $maxAmount) * 100));
                        @endphp
                        <div class="dashboard-sector">
                            <div class="d-flex justify-content-between gap-3">
                                <span>{{ $sector->sector }}</span>
                                <strong>{{ $money($sector->total_amount) }}</strong>
                            </div>
                            <div class="progress" role="progressbar" aria-label="{{ $sector->sector }} খরচ" aria-valuenow="{{ $width }}" aria-valuemin="0" aria-valuemax="100">
                                <div class="progress-bar" style="width: {{ $width }}%"></div>
                            </div>
                            <small>{{ $bn($sector->total_entries) }}টি এন্ট্রি</small>
                        </div>
                    @empty
                        <p class="text-secondary mb-0">খরচ যোগ করলে খাত অনুযায়ী সারাংশ এখানে দেখাবে।</p>
                    @endforelse
                </div>
            </div>

            <div class="card expense-card mt-4">
                <div class="card-body">
                    <h4 class="fw-bold">দ্রুত কাজ</h4>
                    <p class="text-secondary">প্রয়োজনীয় কাজগুলো দ্রুত শুরু করুন।</p>
                    <div class="dashboard-actions">
                        @if (auth()->user()->canAccess('expenses'))
                            <a href="{{ route('admin.expenses.create') }}">
                                <i class="fa-solid fa-circle-plus"></i>
                                <span>খরচ যোগ</span>
                            </a>
                            <a href="{{ route('admin.expenses.index') }}">
                                <i class="fa-solid fa-list-check"></i>
                                <span>তালিকা</span>
                            </a>
                        @endif
                        @if (auth()->user()->canAccess('reports'))
                            <a href="{{ route('admin.reports.index') }}">
                                <i class="fa-solid fa-file-export"></i>
                                <span>রিপোর্ট</span>
                            </a>
                        @endif
                        @if (auth()->user()->canAccess('staff'))
                            <a href="{{ route('admin.staff.index') }}">
                                <i class="fa-solid fa-users-gear"></i>
                                <span>Staff</span>
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
