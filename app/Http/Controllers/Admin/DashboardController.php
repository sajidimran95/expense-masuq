<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        return view('admin.dashboard', [
            'stats' => [
                ['label' => 'মাসিক খরচ', 'value' => '৳ ১২,৮৪০', 'icon' => 'fa-wallet', 'theme' => 'primary'],
                ['label' => 'পেন্ডিং ক্লেইম', 'value' => '১৮', 'icon' => 'fa-receipt', 'theme' => 'warning'],
                ['label' => 'অনুমোদিত রিপোর্ট', 'value' => '১২৪', 'icon' => 'fa-circle-check', 'theme' => 'success'],
                ['label' => 'বাজেট ছাড়িয়েছে', 'value' => '৭', 'icon' => 'fa-triangle-exclamation', 'theme' => 'danger'],
            ],
            'recentExpenses' => [
                ['merchant' => 'অফিস সরঞ্জাম', 'category' => 'অপারেশন', 'amount' => '৳ ৩২০', 'status' => 'অনুমোদিত'],
                ['merchant' => 'ক্লায়েন্ট ডিনার', 'category' => 'সেলস', 'amount' => '৳ ১৮৫.৫০', 'status' => 'পেন্ডিং'],
                ['merchant' => 'ক্লাউড হোস্টিং', 'category' => 'সফটওয়্যার', 'amount' => '৳ ৭৪০', 'status' => 'অনুমোদিত'],
                ['merchant' => 'ভ্রমণ রিইম্বার্সমেন্ট', 'category' => 'ভ্রমণ', 'amount' => '৳ ১,১২০', 'status' => 'রিভিউ'],
            ],
        ]);
    }
}
