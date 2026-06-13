@extends('admin.layouts.app')

@section('title', 'Website Settings')
@section('page-title', 'Website Settings')

@section('content')
    @if (session('status'))
        <div class="alert alert-success rounded-4">
            {{ session('status') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger rounded-4">
            তথ্যগুলো ঠিকভাবে পূরণ করুন।
        </div>
    @endif

    <form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card expense-card">
                    <div class="card-header border-0 bg-white">
                        <h3 class="card-title fw-bold">সাইট তথ্য</h3>
                        <p class="text-secondary mb-0">Company name, browser title এবং SEO meta তথ্য সেট করুন।</p>
                    </div>

                    <div class="card-body">
                        <div class="mb-3">
                            <label for="company_name" class="form-label fw-bold">Company Name <span class="text-danger">*</span></label>
                            <input type="text" id="company_name" name="company_name" value="{{ old('company_name', $setting->company_name) }}" class="form-control @error('company_name') is-invalid @enderror" required>
                            @error('company_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="meta_title" class="form-label fw-bold">Meta Title</label>
                            <input type="text" id="meta_title" name="meta_title" value="{{ old('meta_title', $setting->meta_title) }}" class="form-control @error('meta_title') is-invalid @enderror" placeholder="Browser title / SEO title">
                            @error('meta_title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div>
                            <label for="meta_description" class="form-label fw-bold">Meta Description</label>
                            <textarea id="meta_description" name="meta_description" rows="4" class="form-control @error('meta_description') is-invalid @enderror" placeholder="Search engine description">{{ old('meta_description', $setting->meta_description) }}</textarea>
                            @error('meta_description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card expense-card">
                    <div class="card-header border-0 bg-white">
                        <h3 class="card-title fw-bold">Brand Assets</h3>
                        <p class="text-secondary mb-0">Logo ও favicon আপলোড করুন।</p>
                    </div>

                    <div class="card-body">
                        <div class="setting-preview mb-4">
                            <span>বর্তমান Logo</span>
                            <img src="{{ $setting->logoUrl() }}" alt="Current logo">
                        </div>

                        <div class="mb-4">
                            <label for="logo" class="form-label fw-bold">Logo</label>
                            <input type="file" id="logo" name="logo" class="form-control @error('logo') is-invalid @enderror" accept=".jpg,.jpeg,.png,.webp,.svg,image/*">
                            <small class="text-secondary">JPG, PNG, WEBP বা SVG. Max 2MB.</small>
                            @error('logo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="setting-preview mb-4">
                            <span>বর্তমান Favicon</span>
                            <img src="{{ $setting->faviconUrl() }}" alt="Current favicon">
                        </div>

                        <div class="mb-4">
                            <label for="favicon" class="form-label fw-bold">Fav Icon</label>
                            <input type="file" id="favicon" name="favicon" class="form-control @error('favicon') is-invalid @enderror" accept=".ico,.jpg,.jpeg,.png,.webp,.svg,image/*">
                            <small class="text-secondary">ICO, PNG, JPG, WEBP বা SVG. Max 1MB.</small>
                            @error('favicon')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-success w-100">
                            <i class="fa-solid fa-floppy-disk me-1"></i> Settings Save করুন
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
