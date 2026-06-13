@extends('admin.layouts.app')

@section('title', 'নতুন খরচ যোগ')
@section('page-title', 'নতুন খরচ যোগ')

@section('content')
    @php
        $defaultDate = old('expenses.0.expense_date', $selectedMonth.'-01');
        $oldExpenses = old('expenses', [[
            'expense_date' => $defaultDate,
            'sector' => '',
            'description' => '',
            'amount' => '',
            'voucher_no' => '',
            'approval' => '',
        ]]);
    @endphp

    <form method="POST" action="{{ route('admin.expenses.store') }}" id="expense-form" enctype="multipart/form-data">
        @csrf

        <div class="card expense-card mb-4">
            <div class="card-body">
                <div class="row g-3 align-items-end">
                    <div class="col-md-6">
                        <p class="mb-1 fw-bold">মাস অটো সেট হবে</p>
                        <p class="mb-0 text-secondary">প্রতিটি খরচের তারিখ থেকে মাস অটোমেটিক যোগ হবে।</p>
                    </div>
                </div>
            </div>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger rounded-4">
                তথ্যগুলো ঠিকভাবে পূরণ করুন। তারিখ, খাত, বিবরণ ও টাকার পরিমাণ বাধ্যতামূলক।
            </div>
        @endif

        <div id="expense-rows" class="d-grid gap-3">
            @foreach ($oldExpenses as $index => $expense)
                <div class="card expense-card expense-entry">
                    <div class="card-header border-0 bg-white">
                        <div class="d-flex align-items-center justify-content-between">
                            <h5 class="mb-0 fw-bold">ক্রমিক নং <span class="serial-number">{{ $index + 1 }}</span></h5>
                            <button type="button" class="btn btn-outline-danger btn-sm remove-expense-row {{ $loop->first && count($oldExpenses) === 1 ? 'd-none' : '' }}">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">তারিখ <span class="text-danger">*</span></label>
                                <input type="date" name="expenses[{{ $index }}][expense_date]" value="{{ $expense['expense_date'] ?? '' }}" class="form-control" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">খাত <span class="text-danger">*</span></label>
                                <input type="text" name="expenses[{{ $index }}][sector]" value="{{ $expense['sector'] ?? '' }}" class="form-control" placeholder="যেমন: অফিস, যাতায়াত" required>
                                <small class="text-secondary">কোন কাজে ইউজ হবে</small>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">বিবরণ <span class="text-danger">*</span></label>
                                <div class="expense-editor-wrapper">
                                    <div class="expense-editor" data-placeholder="খরচের বিস্তারিত লিখুন"></div>
                                    <textarea name="expenses[{{ $index }}][description]" class="expense-description-input">{{ $expense['description'] ?? '' }}</textarea>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">টাকার পরিমাণ <span class="text-danger">*</span></label>
                                <input type="text" inputmode="decimal" name="expenses[{{ $index }}][amount]" value="{{ $expense['amount'] ?? '' }}" class="form-control" placeholder="৳ ১২৩৪.৫০ / 1234.50" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">ভাউচার নং</label>
                                <input type="text" name="expenses[{{ $index }}][voucher_no]" value="{{ $expense['voucher_no'] ?? '' }}" class="form-control" placeholder="ভাউচার নং">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">অনুমোদন</label>
                                <input type="file" name="expenses[{{ $index }}][approval]" class="form-control" accept=".png,.jpg,.jpeg,image/png,image/jpeg">
                                <small class="text-secondary">PNG/JPG ছবি দিন</small>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="sticky-bottom mt-4 rounded-4 border bg-white p-3 shadow-sm">
            <div class="d-flex flex-column flex-sm-row justify-content-between gap-2">
                <a href="{{ route('admin.expenses.index', ['month' => $selectedMonth]) }}" class="btn btn-outline-secondary">
                    <i class="fa-solid fa-arrow-left me-1"></i> তালিকায় ফিরুন
                </a>
                <div class="d-flex flex-column flex-sm-row gap-2">
                    <button type="button" class="btn btn-outline-primary" id="add-expense-row">
                        <i class="fa-solid fa-plus me-1"></i> আরেকটি খরচ যোগ করুন
                    </button>
                    <button type="submit" class="btn btn-success">
                        <i class="fa-solid fa-floppy-disk me-1"></i> সব খরচ সেভ করুন
                    </button>
                </div>
            </div>
        </div>
    </form>

    <template id="expense-row-template">
        <div class="card expense-card expense-entry">
            <div class="card-header border-0 bg-white">
                <div class="d-flex align-items-center justify-content-between">
                    <h5 class="mb-0 fw-bold">ক্রমিক নং <span class="serial-number"></span></h5>
                    <button type="button" class="btn btn-outline-danger btn-sm remove-expense-row">
                        <i class="fa-solid fa-trash"></i>
                    </button>
                </div>
            </div>

            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">তারিখ <span class="text-danger">*</span></label>
                        <input type="date" data-name="expense_date" class="form-control" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">খাত <span class="text-danger">*</span></label>
                        <input type="text" data-name="sector" class="form-control" placeholder="যেমন: অফিস, যাতায়াত" required>
                        <small class="text-secondary">কোন কাজে ইউজ হবে</small>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">বিবরণ <span class="text-danger">*</span></label>
                        <div class="expense-editor-wrapper">
                            <div class="expense-editor" data-placeholder="খরচের বিস্তারিত লিখুন"></div>
                            <textarea data-name="description" class="expense-description-input"></textarea>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">টাকার পরিমাণ <span class="text-danger">*</span></label>
                        <input type="text" inputmode="decimal" data-name="amount" class="form-control" placeholder="৳ ১২৩৪.৫০ / 1234.50" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">ভাউচার নং</label>
                        <input type="text" data-name="voucher_no" class="form-control" placeholder="ভাউচার নং">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">অনুমোদন</label>
                        <input type="file" data-name="approval" class="form-control" accept=".png,.jpg,.jpeg,image/png,image/jpeg">
                        <small class="text-secondary">PNG/JPG ছবি দিন</small>
                    </div>
                </div>
            </div>
        </div>
    </template>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const rows = document.querySelector('#expense-rows');
            const template = document.querySelector('#expense-row-template');
            const addButton = document.querySelector('#add-expense-row');
            const editors = new WeakMap();

            const initializeEditors = () => {
                rows.querySelectorAll('.expense-editor-wrapper').forEach((wrapper) => {
                    const editorElement = wrapper.querySelector('.expense-editor');
                    const textarea = wrapper.querySelector('.expense-description-input');

                    if (! editorElement || ! textarea || editors.has(editorElement)) {
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

                    quill.on('text-change', () => {
                        textarea.value = quill.getText().trim() === '' ? '' : quill.root.innerHTML;
                    });

                    editors.set(editorElement, quill);
                });
            };

            const syncEditors = () => {
                rows.querySelectorAll('.expense-editor-wrapper').forEach((wrapper) => {
                    const editorElement = wrapper.querySelector('.expense-editor');
                    const textarea = wrapper.querySelector('.expense-description-input');
                    const quill = editors.get(editorElement);

                    if (textarea && quill) {
                        textarea.value = quill.getText().trim() === '' ? '' : quill.root.innerHTML;
                    }
                });
            };

            const renumberRows = () => {
                rows.querySelectorAll('.expense-entry').forEach((row, index) => {
                    row.querySelector('.serial-number').textContent = index + 1;
                    row.querySelectorAll('[name], [data-name]').forEach((input) => {
                        const field = input.dataset.name ?? input.name.match(/\]\[([^\]]+)\]/)?.[1];

                        if (field) {
                            input.name = `expenses[${index}][${field}]`;
                            input.removeAttribute('data-name');
                        }
                    });

                    const removeButton = row.querySelector('.remove-expense-row');
                    removeButton.classList.toggle('d-none', rows.children.length === 1);
                });

                initializeEditors();
            };

            addButton.addEventListener('click', () => {
                const clone = template.content.cloneNode(true);
                const dateInput = clone.querySelector('[data-name="expense_date"]');
                dateInput.value = new Date().toISOString().slice(0, 10);
                rows.appendChild(clone);
                renumberRows();
            });

            rows.addEventListener('click', (event) => {
                const removeButton = event.target.closest('.remove-expense-row');

                if (! removeButton) {
                    return;
                }

                removeButton.closest('.expense-entry').remove();
                renumberRows();
            });

            renumberRows();

            document.querySelector('#expense-form').addEventListener('submit', syncEditors);
        });
    </script>
@endsection
