<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use Carbon\Carbon;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $currentMonth = now()->format('Y-m');
        $currentYear = now()->year;
        $grandTotal = (float) Expense::sum('amount');
        $monthlyTotal = (float) Expense::where('expense_month', $currentMonth)->sum('amount');
        $yearlyTotal = (float) Expense::whereYear('expense_date', $currentYear)->sum('amount');
        $totalEntries = Expense::count();
        $signatureCount = Expense::whereNotNull('approval')->count();
        $recentExpenses = Expense::latest('expense_date')->latest()->take(6)->get();
        $topSectors = Expense::selectRaw('sector, SUM(amount) as total_amount, COUNT(*) as total_entries')
            ->groupBy('sector')
            ->orderByDesc('total_amount')
            ->take(4)
            ->get();

        return view('admin.dashboard', [
            'stats' => [
                ['label' => 'মোট খরচ', 'value' => $this->money($grandTotal), 'icon' => 'fa-sack-dollar', 'tone' => 'purple'],
                ['label' => 'এই মাসের খরচ', 'value' => $this->money($monthlyTotal), 'icon' => 'fa-wallet', 'tone' => 'blue'],
                ['label' => 'এই বছরের খরচ', 'value' => $this->money($yearlyTotal), 'icon' => 'fa-chart-line', 'tone' => 'green'],
                ['label' => 'মোট এন্ট্রি', 'value' => $this->bn($totalEntries), 'icon' => 'fa-receipt', 'tone' => 'orange'],
                ['label' => 'অনুমোদন আছে', 'value' => $this->bn($signatureCount), 'icon' => 'fa-signature', 'tone' => 'blue'],
            ],
            'recentExpenses' => $recentExpenses,
            'topSectors' => $topSectors,
            'currentMonthLabel' => $this->monthLabel($currentMonth),
            'currentDateTimeLabel' => $this->dateTimeLabel(now()),
            'bn' => fn (string|int|float $value): string => $this->bn($value),
            'money' => fn (string|int|float $value): string => $this->money((float) $value),
        ]);
    }

    private function money(float $amount): string
    {
        return $this->bn(number_format($amount, 2)).' টাকা';
    }

    private function monthLabel(string $month): string
    {
        $date = Carbon::createFromFormat('Y-m', $month);
        $months = [
            'January' => 'জানুয়ারি',
            'February' => 'ফেব্রুয়ারি',
            'March' => 'মার্চ',
            'April' => 'এপ্রিল',
            'May' => 'মে',
            'June' => 'জুন',
            'July' => 'জুলাই',
            'August' => 'আগস্ট',
            'September' => 'সেপ্টেম্বর',
            'October' => 'অক্টোবর',
            'November' => 'নভেম্বর',
            'December' => 'ডিসেম্বর',
        ];

        return $months[$date->format('F')].' '.$this->bn($date->format('Y'));
    }

    private function dateTimeLabel(Carbon $date): string
    {
        $days = [
            'Saturday' => 'শনিবার',
            'Sunday' => 'রবিবার',
            'Monday' => 'সোমবার',
            'Tuesday' => 'মঙ্গলবার',
            'Wednesday' => 'বুধবার',
            'Thursday' => 'বৃহস্পতিবার',
            'Friday' => 'শুক্রবার',
        ];

        return $days[$date->format('l')].', '.$this->monthLabel($date->format('Y-m')).' '.$this->bn($date->format('d')).', '.$this->bn($date->format('h:i A'));
    }

    private function bn(string|int|float $value): string
    {
        return strtr((string) $value, [
            '0' => '০',
            '1' => '১',
            '2' => '২',
            '3' => '৩',
            '4' => '৪',
            '5' => '৫',
            '6' => '৬',
            '7' => '৭',
            '8' => '৮',
            '9' => '৯',
        ]);
    }
}
