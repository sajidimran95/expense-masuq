@extends('admin.layouts.app')

@section('title', 'খরচ বিস্তারিত')
@section('page-title', 'খরচ বিস্তারিত')

@section('content')
    @if (session('status'))
        <div class="alert alert-success rounded-4">
            {{ session('status') }}
        </div>
    @endif

    <div class="card expense-card">
        <div class="card-header border-0">
            <div class="d-flex flex-column flex-md-row justify-content-between gap-3">
                <div>
                    <h3 class="card-title fw-bold">খরচ তথ্য</h3>
                    <p class="text-secondary mb-0">{{ $expense->expense_date->format('d-m-Y') }} তারিখের খরচ</p>
                </div>
                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('admin.expenses.index', ['month' => $expense->expense_month]) }}" class="btn btn-outline-secondary">
                        <i class="fa-solid fa-arrow-left me-1"></i> তালিকা
                    </a>
                    <a href="{{ route('admin.expenses.edit', $expense) }}" class="btn btn-warning text-white">
                        <i class="fa-solid fa-pen-to-square me-1"></i> এডিট
                    </a>
                    <form method="POST" action="{{ route('admin.expenses.destroy', $expense) }}" onsubmit="return confirm('আপনি কি এই খরচটি মুছে ফেলতে চান?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="fa-solid fa-trash me-1"></i> ডিলিট
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="card-body">
            <div class="row g-4">
                <div class="col-md-4">
                    <p class="text-secondary mb-1">তারিখ</p>
                    <h5 class="fw-bold">{{ $expense->expense_date->format('d-m-Y') }}</h5>
                </div>
                <div class="col-md-4">
                    <p class="text-secondary mb-1">মাস</p>
                    <h5 class="fw-bold">{{ $expense->expense_month }}</h5>
                </div>
                <div class="col-md-4">
                    <p class="text-secondary mb-1">টাকার পরিমাণ</p>
                    <h5 class="fw-bold">{{ $expense->formatted_amount }}</h5>
                </div>
                <div class="col-md-4">
                    <p class="text-secondary mb-1">খাত</p>
                    <h5 class="fw-bold">{{ $expense->sector }}</h5>
                </div>
                <div class="col-md-4">
                    <p class="text-secondary mb-1">ভাউচার নং</p>
                    <h5 class="fw-bold">{{ $expense->voucher_no ?: '-' }}</h5>
                </div>
                <div class="col-md-4">
                    <p class="text-secondary mb-1">অনুমোদন</p>
                    @if ($expense->approval)
                        <img src="{{ asset($expense->approval) }}" alt="অনুমোদন" class="expense-signature-preview">
                    @else
                        <h5 class="fw-bold">-</h5>
                    @endif
                </div>
                <div class="col-12">
                    <p class="text-secondary mb-1">বিবরণ</p>
                    <div class="rounded-4 border bg-light p-3 expense-description-preview">
                        {!! $expense->description !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
