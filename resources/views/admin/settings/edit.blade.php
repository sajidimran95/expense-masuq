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

                        <hr class="my-4">

                        <h4 class="fw-bold">Front Page Content</h4>
                        <p class="text-secondary">Home page banner text এখান থেকে পরিবর্তন করুন।</p>

                        <div class="mb-3">
                            <label for="front_badge_text" class="form-label fw-bold">Small Badge Text</label>
                            <input type="text" id="front_badge_text" name="front_badge_text" value="{{ old('front_badge_text', $setting->front_badge_text) }}" class="form-control @error('front_badge_text') is-invalid @enderror">
                            @error('front_badge_text')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="front_title" class="form-label fw-bold">Front Title</label>
                            <input type="text" id="front_title" name="front_title" value="{{ old('front_title', $setting->front_title) }}" class="form-control @error('front_title') is-invalid @enderror">
                            @error('front_title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div>
                            <label for="front_description" class="form-label fw-bold">Front Description</label>
                            <textarea id="front_description" name="front_description" rows="4" class="form-control @error('front_description') is-invalid @enderror">{{ old('front_description', $setting->front_description) }}</textarea>
                            @error('front_description')
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

                        <div class="setting-preview mb-4 setting-preview-wide">
                            <span>বর্তমান Front BG</span>
                            <img src="{{ $setting->frontBackgroundUrl() }}" alt="Current front background">
                        </div>

                        <div class="mb-4">
                            <label for="front_background" class="form-label fw-bold">Front Page Background</label>
                            <input type="file" id="front_background" name="front_background" class="form-control @error('front_background') is-invalid @enderror" accept=".jpg,.jpeg,.png,.webp,image/*">
                            <small class="text-secondary">JPG, PNG বা WEBP. Max 4MB.</small>
                            @error('front_background')
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
