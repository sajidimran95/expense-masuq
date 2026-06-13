<?php

namespace Database\Seeders;

use App\Models\SiteSetting;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::query()->updateOrCreate([
            'email' => 'admin@example.com',
        ], [
            'name' => 'খরচ অ্যাডমিন',
            'password' => Hash::make('password'),
            'is_admin' => true,
            'role' => 'super_admin',
            'permissions' => ['expenses', 'reports', 'settings', 'staff', 'audit_logs'],
            'is_active' => true,
        ]);

        SiteSetting::query()->firstOrCreate([], [
            'company_name' => 'Expense Management',
            'logo' => 'images/expense-logo.svg',
            'favicon' => 'images/expense-logo.svg',
            'meta_title' => 'Expense Management',
            'meta_description' => 'দৈনিক ও মাসিক খরচ, রিপোর্ট এবং অনুমোদন সহজে ম্যানেজ করার সফটওয়্যার।',
            'front_badge_text' => 'দৈনিক ও মাসিক খরচ হিসাব সফটওয়্যার',
            'front_title' => 'প্রতিদিনের খরচ ও মাসিক বাজেট সহজে হিসাব করুন।',
            'front_description' => 'দৈনিক খরচ, মাসিক বাজেট, রসিদ, অনুমোদন এবং ক্যাটাগরি রিপোর্ট এক জায়গা থেকে সুন্দরভাবে ম্যানেজ করুন।',
        ]);
    }
}
