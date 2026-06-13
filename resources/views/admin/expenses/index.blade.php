@extends('admin.layouts.app')

@section('title', 'খরচ তালিকা')
@section('page-title', 'খরচ তালিকা')

@section('content')
    <div class="card expense-card mb-4">
        <div class="card-body">
            <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
                <form method="GET" action="{{ route('admin.expenses.index') }}" class="row g-2 align-items-end flex-grow-1" data-expense-filter-form>
                    <div class="col-12 col-sm-4 col-lg-3">
                        <label for="type" class="form-label fw-semibold">ফিল্টার টাইপ</label>
                        <select name="type" id="type" class="form-select" data-expense-filter-type>
                            <option value="monthly" @selected($filterType === 'monthly')>মাস অনুযায়ী</option>
                            <option value="yearly" @selected($filterType === 'yearly')>বছর অনুযায়ী</option>
                        </select>
                    </div>
                    <div class="col-12 col-sm-4 col-lg-3 expense-filter expense-filter-monthly">
                        <label for="month" class="form-label fw-semibold">মাস নির্বাচন করুন</label>
                        <input type="month" id="month" name="month" value="{{ $selectedMonth }}" class="form-control">
                    </div>
                    <div class="col-12 col-sm-4 col-lg-3 expense-filter expense-filter-yearly">
                        <label for="year" class="form-label fw-semibold">বছর নির্বাচন করুন</label>
                        <input type="number" id="year" name="year" value="{{ $selectedYear }}" min="2000" max="2100" class="form-control">
                    </div>
                    <div class="col-12 col-sm-4 col-lg-3">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fa-solid fa-filter me-1"></i> ফিল্টার
                        </button>
                    </div>
                </form>

                <div class="text-md-end">
                    <p class="mb-2 text-secondary">{{ $filterType === 'yearly' ? 'নির্বাচিত বছরের মোট খরচ' : 'নির্বাচিত মাসের মোট খরচ' }}</p>
                    <h3 class="fw-black mb-3">৳ {{ number_format((float) $totalAmount, 2) }}</h3>
                    <a href="{{ route('admin.expenses.create', ['month' => $selectedMonth]) }}" class="btn btn-success">
                        <i class="fa-solid fa-plus me-1"></i> নতুন খরচ যোগ করুন
                    </a>
                </div>
            </div>
        </div>
    </div>

    @if (session('status'))
        <div class="alert alert-success rounded-4">
            {{ session('status') }}
        </div>
    @endif

    <div class="card expense-card">
        <div class="card-header border-0">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h3 class="card-title fw-bold">খরচের বিস্তারিত</h3>
                    <p class="text-secondary mb-0">{{ $filterType === 'yearly' ? $selectedYear.' সালের সকল খরচ এখানে দেখাবে।' : 'মাসভিত্তিক সকল খরচ এখানে দেখাবে।' }}</p>
                </div>
                <a href="{{ route('admin.expenses.create', ['month' => $selectedMonth]) }}" class="btn btn-primary btn-sm">
                    <i class="fa-solid fa-plus me-1"></i> Add
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
                                        <img src="{{ asset($expense->approval) }}" alt="অনুমোদন signature" class="expense-signature-preview">
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
                                        <form method="POST" action="{{ route('admin.expenses.destroy', $expense) }}" onsubmit="return confirm('আপনি কি এই খরচটি মুছে ফেলতে চান?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="py-5 text-center text-secondary">
                                    {{ $filterType === 'yearly' ? 'এই বছরে কোনো খরচ যোগ করা হয়নি।' : 'এই মাসে কোনো খরচ যোগ করা হয়নি।' }}
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
                                <i class="fa-solid fa-receipt"></i>
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
                                <img src="{{ asset($expense->approval) }}" alt="অনুমোদন signature" class="expense-signature-preview">
                            </div>
                        @endif

                        <div class="mobile-list-actions">
                            <a href="{{ route('admin.expenses.show', $expense) }}" class="btn btn-info btn-sm text-white">
                                <i class="fa-solid fa-eye me-1"></i> View
                            </a>
                            <a href="{{ route('admin.expenses.edit', $expense) }}" class="btn btn-warning btn-sm text-white">
                                <i class="fa-solid fa-pen-to-square me-1"></i> Edit
                            </a>
                            <form method="POST" action="{{ route('admin.expenses.destroy', $expense) }}" onsubmit="return confirm('আপনি কি এই খরচটি মুছে ফেলতে চান?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">
                                    <i class="fa-solid fa-trash me-1"></i> Delete
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="mobile-list-empty">
                        <i class="fa-solid fa-receipt"></i>
                        <p>{{ $filterType === 'yearly' ? 'এই বছরে কোনো খরচ যোগ করা হয়নি।' : 'এই মাসে কোনো খরচ যোগ করা হয়নি।' }}</p>
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
            const filterType = document.querySelector('[data-expense-filter-type]');
            const filters = document.querySelectorAll('.expense-filter');

            const toggleFilters = () => {
                filters.forEach((filter) => filter.classList.add('d-none'));
                document.querySelector(`.expense-filter-${filterType.value}`)?.classList.remove('d-none');
            };

            filterType.addEventListener('change', toggleFilters);
            toggleFilters();
        });
    </script>
@endsection
