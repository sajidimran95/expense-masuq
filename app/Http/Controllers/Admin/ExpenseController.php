<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ExpenseController extends Controller
{
    public function index(Request $request): View
    {
        $selectedMonth = $request->query('month', now()->format('Y-m'));

        $expenses = Expense::query()
            ->where('expense_month', $selectedMonth)
            ->latest('expense_date')
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $totalAmount = Expense::query()
            ->where('expense_month', $selectedMonth)
            ->sum('amount');

        return view('admin.expenses.index', [
            'expenses' => $expenses,
            'selectedMonth' => $selectedMonth,
            'totalAmount' => $totalAmount,
        ]);
    }

    public function create(Request $request): View
    {
        return view('admin.expenses.create', [
            'selectedMonth' => $request->query('month', now()->format('Y-m')),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'expenses' => ['required', 'array', 'min:1'],
            'expenses.*.expense_date' => ['required', 'date'],
            'expenses.*.sector' => ['required', 'string', 'max:255'],
            'expenses.*.description' => ['required', 'string'],
            'expenses.*.amount' => ['required', 'numeric', 'min:0'],
            'expenses.*.voucher_no' => ['nullable', 'string', 'max:255'],
            'expenses.*.approval' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ], [
            'expenses.required' => 'কমপক্ষে একটি খরচ যোগ করুন।',
            'expenses.*.expense_date.required' => 'তারিখ দিন।',
            'expenses.*.sector.required' => 'খাত দিন।',
            'expenses.*.description.required' => 'বিবরণ দিন।',
            'expenses.*.amount.required' => 'টাকার পরিমাণ দিন।',
            'expenses.*.amount.numeric' => 'টাকার পরিমাণ সংখ্যা হতে হবে।',
            'expenses.*.approval.image' => 'অনুমোদন হিসেবে PNG বা JPG signature image দিন।',
            'expenses.*.approval.mimes' => 'অনুমোদন signature শুধু PNG, JPG বা JPEG হতে পারবে।',
        ]);

        DB::transaction(function () use ($validated): void {
            foreach ($validated['expenses'] as $expense) {
                Expense::query()->create([
                    'expense_month' => substr($expense['expense_date'], 0, 7),
                    'expense_date' => $expense['expense_date'],
                    'sector' => $expense['sector'],
                    'description' => $this->sanitizeDescription($expense['description']),
                    'amount' => $expense['amount'],
                    'voucher_no' => $expense['voucher_no'] ?? null,
                    'approval' => $this->storeApprovalSignature($expense['approval'] ?? null),
                ]);
            }
        });

        $redirectMonth = substr($validated['expenses'][0]['expense_date'], 0, 7);

        return redirect()
            ->route('admin.expenses.index', ['month' => $redirectMonth])
            ->with('status', 'খরচ সফলভাবে যোগ করা হয়েছে।');
    }

    private function sanitizeDescription(string $description): string
    {
        return strip_tags($description, '<p><br><strong><b><em><i><u><ol><ul><li>');
    }

    private function storeApprovalSignature(mixed $file): ?string
    {
        if (! $file) {
            return null;
        }

        $directory = public_path('uploads/signatures');

        if (! is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        $filename = uniqid('signature_', true).'.'.$file->extension();
        $file->move($directory, $filename);

        return 'uploads/signatures/'.$filename;
    }
}
