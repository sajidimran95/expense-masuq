@extends('admin.layouts.app')

@section('title', 'রিপোর্ট')
@section('page-title', 'খরচ রিপোর্ট')

@section('content')
    <div class="card expense-card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.reports.index') }}" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label for="type" class="form-label fw-bold">রিপোর্ট টাইপ</label>
                    <select name="type" id="type" class="form-select" data-report-type>
                        <option value="monthly" @selected($reportType === 'monthly')>মাসিক রিপোর্ট</option>
                        <option value="date" @selected($reportType === 'date')>তারিখ অনুযায়ী রিপোর্ট</option>
                        <option value="yearly" @selected($reportType === 'yearly')>বার্ষিক রিপোর্ট</option>
                    </select>
                </div>

                <div class="col-md-3 report-filter report-filter-monthly">
                    <label for="month" class="form-label fw-bold">মাস নির্বাচন করুন</label>
                    <input type="month" id="month" name="month" value="{{ $selectedMonth }}" class="form-control">
                </div>

                <div class="col-md-3 report-filter report-filter-date">
                    <label for="date" class="form-label fw-bold">তারিখ নির্বাচন করুন</label>
                    <input type="date" id="date" name="date" value="{{ $selectedDate }}" class="form-control">
                </div>

                <div class="col-md-3 report-filter report-filter-yearly">
                    <label for="year" class="form-label fw-bold">বছর নির্বাচন করুন</label>
                    <input type="number" id="year" name="year" value="{{ $selectedYear }}" min="2000" max="2100" class="form-control">
                </div>

                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fa-solid fa-magnifying-glass me-1"></i> রিপোর্ট দেখুন
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="card expense-card h-100">
                <div class="card-body">
                    <p class="text-secondary mb-1">রিপোর্ট</p>
                    <h4 class="fw-bold mb-0">{{ $reportTitle }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card expense-card h-100">
                <div class="card-body">
                    <p class="text-secondary mb-1">মোট খরচ</p>
                    <h3 class="fw-black mb-0">৳ {{ number_format((float) $totalAmount, 2) }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card expense-card h-100">
                <div class="card-body">
                    <p class="text-secondary mb-1">মোট এন্ট্রি</p>
                    <h3 class="fw-black mb-0">{{ $totalEntries }}</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="card expense-card mb-4">
        <div class="card-body">
            <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
                <div>
                    <h5 class="fw-bold mb-1">Export রিপোর্ট</h5>
                    <p class="text-secondary mb-0">বর্তমান filter অনুযায়ী month-wise headline, table এবং total সহ export হবে।</p>
                </div>
                <div class="d-flex flex-column flex-sm-row gap-2">
                    <a href="{{ route('admin.reports.export.csv', request()->query()) }}" class="btn btn-outline-success">
                        <i class="fa-solid fa-file-csv me-1"></i> CSV Export
                    </a>
                    <a href="{{ route('admin.reports.export.excel', request()->query()) }}" class="btn btn-success">
                        <i class="fa-solid fa-file-excel me-1"></i> Excel Export
                    </a>
                    <a href="{{ route('admin.reports.export.pdf', request()->query()) }}" class="btn btn-outline-danger">
                        <i class="fa-solid fa-file-pdf me-1"></i> PDF Export
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="card expense-card">
        <div class="card-header border-0">
            <div class="d-flex flex-column flex-md-row justify-content-between gap-3">
                <div>
                    <h3 class="card-title fw-bold">রিপোর্টের খরচ তালিকা</h3>
                    <p class="text-secondary mb-0">নির্বাচিত রিপোর্ট অনুযায়ী খরচগুলো এখানে দেখাবে।</p>
                </div>
                <a href="{{ route('admin.expenses.create') }}" class="btn btn-success btn-sm align-self-md-start">
                    <i class="fa-solid fa-plus me-1"></i> নতুন খরচ
                </a>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive d-none d-md-block">
                <table class="table align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>ক্রমিক নং</th>
                            <th>তারিখ</th>
                            <th>খাত</th>
                            <th>বিবরণ</th>
                            <th>টাকার পরিমাণ</th>
                            <th>ভাউচার নং</th>
                            <th>অনুমোদন</th>
                            <th>অ্যাকশন</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($expenses as $expense)
                            <tr>
                                <td class="fw-bold">{{ $expenses->firstItem() + $loop->index }}</td>
                                <td>{{ $expense->expense_date->format('d-m-Y') }}</td>
                                <td class="fw-semibold">{{ $expense->sector }}</td>
                                <td class="expense-description-preview">{!! $expense->description !!}</td>
                                <td class="fw-bold">{{ $expense->formatted_amount }}</td>
                                <td>{{ $expense->voucher_no ?: '-' }}</td>
                                <td>
                                    @if ($expense->approval)
                                        <img src="{{ asset($expense->approval) }}" alt="অনুমোদন" class="expense-signature-preview">
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex flex-wrap gap-1">
                                        <a href="{{ route('admin.expenses.show', $expense) }}" class="btn btn-info btn-sm text-white">
                                            <i class="fa-solid fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.expenses.edit', $expense) }}" class="btn btn-warning btn-sm text-white">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="py-5 text-center text-secondary">
                                    এই রিপোর্টে কোনো খরচ পাওয়া যায়নি।
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mobile-list d-md-none">
                @forelse ($expenses as $expense)
                    <div class="mobile-list-card">
                        <div class="mobile-list-top">
                            <div class="mobile-list-icon">
                                <i class="fa-solid fa-file-invoice-dollar"></i>
                            </div>
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between gap-2">
                                    <div>
                                        <span class="mobile-list-serial">#{{ $expenses->firstItem() + $loop->index }}</span>
                                        <h4>{{ $expense->sector }}</h4>
                                    </div>
                                    <strong>{{ $expense->formatted_amount }}</strong>
                                </div>
                                <p class="expense-description-preview">{!! $expense->description !!}</p>
                            </div>
                        </div>

                        <div class="mobile-list-meta">
                            <span><i class="fa-regular fa-calendar me-1"></i>{{ $expense->expense_date->format('d-m-Y') }}</span>
                            <span><i class="fa-solid fa-ticket me-1"></i>{{ $expense->voucher_no ?: 'ভাউচার নেই' }}</span>
                            <span class="{{ $expense->approval ? 'text-success' : 'text-secondary' }}">
                                <i class="fa-solid fa-signature me-1"></i>{{ $expense->approval ? 'অনুমোদন আছে' : 'অনুমোদন নেই' }}
                            </span>
                        </div>

                        @if ($expense->approval)
                            <div class="mobile-signature-box">
                                <span>অনুমোদন</span>
                                <img src="{{ asset($expense->approval) }}" alt="অনুমোদন" class="expense-signature-preview">
                            </div>
                        @endif

                        <div class="mobile-list-actions">
                            <a href="{{ route('admin.expenses.show', $expense) }}" class="btn btn-info btn-sm text-white">
                                <i class="fa-solid fa-eye me-1"></i> View
                            </a>
                            <a href="{{ route('admin.expenses.edit', $expense) }}" class="btn btn-warning btn-sm text-white">
                                <i class="fa-solid fa-pen-to-square me-1"></i> Edit
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="mobile-list-empty">
                        <i class="fa-solid fa-file-invoice"></i>
                        <p>এই রিপোর্টে কোনো খরচ পাওয়া যায়নি।</p>
                    </div>
                @endforelse
            </div>
        </div>

        @if ($expenses->hasPages())
            <div class="card-footer bg-white">
                {{ $expenses->links() }}
            </div>
        @endif
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const reportType = document.querySelector('[data-report-type]');
            const filters = document.querySelectorAll('.report-filter');

            const toggleFilters = () => {
                filters.forEach((filter) => filter.classList.add('d-none'));
                document.querySelector(`.report-filter-${reportType.value}`)?.classList.remove('d-none');
            };

            reportType.addEventListener('change', toggleFilters);
            toggleFilters();
        });
    </script>
@endsection
