@extends('admin.layouts.app')

@section('title', 'খরচ তালিকা')
@section('page-title', 'খরচ তালিকা')

@section('content')
    <div class="card expense-card mb-4">
        <div class="card-body">
            <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
                <form method="GET" action="{{ route('admin.expenses.index') }}" class="d-flex flex-column flex-sm-row gap-2">
                    <div>
                        <label for="month" class="form-label fw-semibold">মাস নির্বাচন করুন</label>
                        <input type="month" id="month" name="month" value="{{ $selectedMonth }}" class="form-control">
                    </div>
                    <div class="d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fa-solid fa-filter me-1"></i> ফিল্টার
                        </button>
                    </div>
                </form>

                <div class="text-md-end">
                    <p class="mb-2 text-secondary">নির্বাচিত মাসের মোট খরচ</p>
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
                    <p class="text-secondary mb-0">মাসভিত্তিক সকল খরচ এখানে দেখাবে।</p>
                </div>
                <a href="{{ route('admin.expenses.create', ['month' => $selectedMonth]) }}" class="btn btn-primary btn-sm">
                    <i class="fa-solid fa-plus me-1"></i> Add
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
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-5 text-center text-secondary">
                                    এই মাসে কোনো খরচ যোগ করা হয়নি।
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
@endsection
