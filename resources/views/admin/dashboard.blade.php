@extends('admin.layouts.app')

@section('title', 'ড্যাশবোর্ড')
@section('page-title', 'খরচ ড্যাশবোর্ড')

@section('content')
    <div class="row g-4 mb-4">
        @foreach ($stats as $stat)
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="small-box text-bg-{{ $stat['theme'] }} shadow-sm">
                    <div class="inner">
                        <h3>{{ $stat['value'] }}</h3>
                        <p>{{ $stat['label'] }}</p>
                    </div>
                    <i class="small-box-icon fa-solid {{ $stat['icon'] }}"></i>
                    <a href="{{ route('admin.expenses.index') }}" class="small-box-footer link-light link-underline-opacity-0 link-underline-opacity-50-hover">
                        বিস্তারিত দেখুন <i class="fa-solid fa-arrow-circle-right ms-1"></i>
                    </a>
                </div>
            </div>
        @endforeach
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card expense-card">
                <div class="card-header border-0">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h3 class="card-title fw-bold">সাম্প্রতিক খরচ</h3>
                            <p class="text-secondary mb-0">সর্বশেষ জমা দেওয়া খরচগুলো ফাইন্যান্স রিভিউয়ের অপেক্ষায় আছে।</p>
                        </div>
                        <a href="{{ route('admin.expenses.create') }}" class="btn btn-primary btn-sm">
                            <i class="fa-solid fa-plus me-1"></i> খরচ যোগ করুন
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>মার্চেন্ট</th>
                                    <th>ক্যাটাগরি</th>
                                    <th>পরিমাণ</th>
                                    <th>স্ট্যাটাস</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($recentExpenses as $expense)
                                    <tr>
                                        <td class="fw-semibold">{{ $expense['merchant'] }}</td>
                                        <td>{{ $expense['category'] }}</td>
                                        <td>{{ $expense['amount'] }}</td>
                                        <td>
                                            @php
                                                $badge = match ($expense['status']) {
                                                    'অনুমোদিত' => 'success',
                                                    'পেন্ডিং' => 'warning',
                                                    default => 'info',
                                                };
                                            @endphp
                                            <span class="badge text-bg-{{ $badge }}">{{ $expense['status'] }}</span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card expense-card">
                <div class="card-header border-0">
                    <h3 class="card-title fw-bold">বাজেট অবস্থা</h3>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="fw-semibold">অপারেশন</span>
                            <span class="text-secondary">72%</span>
                        </div>
                        <div class="progress" role="progressbar" aria-label="অপারেশন বাজেট ব্যবহার" aria-valuenow="72" aria-valuemin="0" aria-valuemax="100">
                            <div class="progress-bar bg-primary" style="width: 72%"></div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="fw-semibold">ভ্রমণ</span>
                            <span class="text-secondary">88%</span>
                        </div>
                        <div class="progress" role="progressbar" aria-label="ভ্রমণ বাজেট ব্যবহার" aria-valuenow="88" aria-valuemin="0" aria-valuemax="100">
                            <div class="progress-bar bg-warning" style="width: 88%"></div>
                        </div>
                    </div>

                    <div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="fw-semibold">সফটওয়্যার</span>
                            <span class="text-secondary">54%</span>
                        </div>
                        <div class="progress" role="progressbar" aria-label="সফটওয়্যার বাজেট ব্যবহার" aria-valuenow="54" aria-valuemin="0" aria-valuemax="100">
                            <div class="progress-bar bg-success" style="width: 54%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card expense-card mt-4">
                <div class="card-body">
                    <h4 class="fw-bold">দ্রুত সারাংশ</h4>
                    <p class="text-secondary mb-4">এই প্যানেলটি খরচ এন্ট্রি, অনুমোদন ও রিপোর্ট মডিউলের জন্য প্রস্তুত।</p>
                    <a href="{{ route('admin.logout') }}" class="btn btn-outline-secondary disabled" aria-disabled="true">লগআউট করতে প্রোফাইল মেনু ব্যবহার করুন</a>
                </div>
            </div>
        </div>
    </div>
@endsection
