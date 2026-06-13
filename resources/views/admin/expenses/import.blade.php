@extends('admin.layouts.app')

@section('title', 'Bulk Upload')
@section('page-title', 'Bulk Upload')

@section('content')
    @if ($errors->any())
        <div class="alert alert-danger rounded-4">
            {{ $errors->first() }}
        </div>
    @endif

    <div class="row g-4">
        <div class="col-lg-7">
            <div class="card expense-card">
                <div class="card-header border-0 bg-white">
                    <h3 class="card-title fw-bold">Excel Bulk Upload</h3>
                    <p class="text-secondary mb-0">Demo file download করে Excel-এ data বসিয়ে upload করুন।</p>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.expenses.import.store') }}" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-4">
                            <label for="import_file" class="form-label fw-bold">Excel/CSV File <span class="text-danger">*</span></label>
                            <input type="file" id="import_file" name="import_file" class="form-control @error('import_file') is-invalid @enderror" accept=".xls,.csv,.txt" required>
                            <small class="text-secondary">Demo `.xls` অথবা CSV upload করুন। Max 2MB.</small>
                            @error('import_file')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex flex-column flex-sm-row gap-2">
                            <button type="submit" class="btn btn-success">
                                <i class="fa-solid fa-upload me-1"></i> Upload & Import
                            </button>
                            <a href="{{ route('admin.expenses.import.demo') }}" class="btn btn-outline-primary">
                                <i class="fa-solid fa-file-excel me-1"></i> Demo Excel Download
                            </a>
                            <a href="{{ route('admin.expenses.index') }}" class="btn btn-outline-secondary">
                                তালিকায় ফিরুন
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="card expense-card">
                <div class="card-body">
                    <h4 class="fw-bold">Column Format</h4>
                    <div class="bulk-format-list">
                        <span>তারিখ: `YYYY-MM-DD` বা `DD-MM-YYYY`</span>
                        <span>খাত: যেমন অফিস, যাতায়াত</span>
                        <span>বিবরণ: খরচের বিস্তারিত</span>
                        <span>টাকার পরিমাণ: বাংলা/English number supported</span>
                        <span>ভাউচার নং: optional</span>
                    </div>
                    <p class="text-secondary mb-0 mt-3">Signature image bulk Excel থেকে import হবে না; প্রয়োজনে পরে edit করে signature upload করুন।</p>
                </div>
            </div>
        </div>
    </div>
@endsection
