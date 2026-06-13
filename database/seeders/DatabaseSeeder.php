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
        ]);
    }
}
