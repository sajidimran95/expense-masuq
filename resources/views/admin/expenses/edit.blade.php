@extends('admin.layouts.app')

@section('title', 'খরচ এডিট')
@section('page-title', 'খরচ এডিট')

@section('content')
    <form method="POST" action="{{ route('admin.expenses.update', $expense) }}" id="expense-edit-form" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        @if ($errors->any())
            <div class="alert alert-danger rounded-4">
                তথ্যগুলো ঠিকভাবে পূরণ করুন। তারিখ, খাত, বিবরণ ও টাকার পরিমাণ বাধ্যতামূলক।
            </div>
        @endif

        <div class="card expense-card">
            <div class="card-header border-0 bg-white">
                <div class="d-flex flex-column flex-md-row justify-content-between gap-3">
                    <div>
                        <h3 class="card-title fw-bold">খরচ আপডেট করুন</h3>
                        <p class="text-secondary mb-0">তারিখ পরিবর্তন করলে মাস অটোমেটিক আপডেট হবে।</p>
                    </div>
                    <div class="d-flex flex-wrap gap-2">
                        <a href="{{ route('admin.expenses.show', $expense) }}" class="btn btn-outline-secondary">
                            <i class="fa-solid fa-arrow-left me-1"></i> ফিরে যান
                        </a>
                        <button type="submit" class="btn btn-success">
                            <i class="fa-solid fa-floppy-disk me-1"></i> আপডেট করুন
                        </button>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">তারিখ <span class="text-danger">*</span></label>
                        <input type="date" name="expense_date" value="{{ old('expense_date', $expense->expense_date->toDateString()) }}" class="form-control @error('expense_date') is-invalid @enderror" required>
                        @error('expense_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">খাত <span class="text-danger">*</span></label>
                        <input type="text" name="sector" value="{{ old('sector', $expense->sector) }}" class="form-control @error('sector') is-invalid @enderror" required>
                        <small class="text-secondary">কোন কাজে ইউজ হবে</small>
                        @error('sector')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">বিবরণ <span class="text-danger">*</span></label>
                        <div class="expense-editor-wrapper">
                            <div class="expense-editor" data-placeholder="খরচের বিস্তারিত লিখুন"></div>
                            <textarea name="description" class="expense-description-input">{{ old('description', $expense->description) }}</textarea>
                        </div>
                        @error('description')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">টাকার পরিমাণ <span class="text-danger">*</span></label>
                        <input type="text" inputmode="decimal" name="amount" value="{{ old('amount', $expense->amount) }}" class="form-control @error('amount') is-invalid @enderror" placeholder="৳ ১২৩৪.৫০ / 1234.50" required>
                        @error('amount')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">ভাউচার নং</label>
                        <input type="text" name="voucher_no" value="{{ old('voucher_no', $expense->voucher_no) }}" class="form-control">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">অনুমোদন</label>
                        <input type="file" name="approval" class="form-control @error('approval') is-invalid @enderror" accept=".png,.jpg,.jpeg,image/png,image/jpeg">
                        <small class="text-secondary">নতুন PNG/JPG ছবি দিলে পুরাতন ছবি পরিবর্তন হবে</small>
                        @error('approval')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                        @if ($expense->approval)
                            <div class="mt-3">
                                <p class="text-secondary mb-1">বর্তমান অনুমোদন</p>
                                <img src="{{ asset($expense->approval) }}" alt="অনুমোদন" class="expense-signature-preview">
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const editorElement = document.querySelector('.expense-editor');
            const textarea = document.querySelector('.expense-description-input');

            if (! editorElement || ! textarea) {
                return;
            }

            const quill = new window.Quill(editorElement, {
                theme: 'snow',
                placeholder: editorElement.dataset.placeholder,
                modules: {
                    toolbar: [
                        ['bold', 'italic', 'underline'],
                        [{ list: 'ordered' }, { list: 'bullet' }],
                        ['clean'],
                    ],
                },
            });

            if (textarea.value) {
                quill.root.innerHTML = textarea.value;
            }

            const syncEditor = () => {
                textarea.value = quill.getText().trim() === '' ? '' : quill.root.innerHTML;
            };

            quill.on('text-change', syncEditor);
            document.querySelector('#expense-edit-form').addEventListener('submit', syncEditor);
        });
    </script>
@endsection
