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
            <div class="table-responsive">
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
