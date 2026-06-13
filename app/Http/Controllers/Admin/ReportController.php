<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function index(Request $request): View
    {
        $reportType = $request->query('type', 'monthly');
        $reportType = in_array($reportType, ['monthly', 'date', 'yearly'], true) ? $reportType : 'monthly';

        $selectedMonth = $request->query('month', now()->format('Y-m'));
        $selectedDate = $request->query('date', now()->toDateString());
        $selectedYear = $request->query('year', now()->format('Y'));

        $query = Expense::query();

        if ($reportType === 'date') {
            $query->whereDate('expense_date', $selectedDate);
            $reportTitle = date('d-m-Y', strtotime($selectedDate)).' তারিখের রিপোর্ট';
        } elseif ($reportType === 'yearly') {
            $query->whereYear('expense_date', $selectedYear);
            $reportTitle = $selectedYear.' সালের রিপোর্ট';
        } else {
            $query->where('expense_month', $selectedMonth);
            $reportTitle = $selectedMonth.' মাসের রিপোর্ট';
        }

        $expenses = (clone $query)
            ->latest('expense_date')
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.reports.index', [
            'expenses' => $expenses,
            'reportType' => $reportType,
            'reportTitle' => $reportTitle,
            'selectedMonth' => $selectedMonth,
            'selectedDate' => $selectedDate,
            'selectedYear' => $selectedYear,
            'totalAmount' => (clone $query)->sum('amount'),
            'totalEntries' => (clone $query)->count(),
        ]);
    }
}
