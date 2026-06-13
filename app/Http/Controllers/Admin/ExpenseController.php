<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class ExpenseController extends Controller
{
    public function index(Request $request): View
    {
        $filterType = $request->query('type', 'monthly');
        $filterType = in_array($filterType, ['monthly', 'yearly'], true) ? $filterType : 'monthly';
        $selectedMonth = $request->query('month', now()->format('Y-m'));
        $selectedYear = $request->query('year', now()->format('Y'));
        $query = Expense::query();

        if ($filterType === 'yearly') {
            $query->whereYear('expense_date', $selectedYear);
        } else {
            $query->where('expense_month', $selectedMonth);
        }

        $expenses = (clone $query)
            ->latest('expense_date')
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $totalAmount = (clone $query)->sum('amount');

        return view('admin.expenses.index', [
            'expenses' => $expenses,
            'filterType' => $filterType,
            'selectedMonth' => $selectedMonth,
            'selectedYear' => $selectedYear,
            'totalAmount' => $totalAmount,
        ]);
    }

    public function create(Request $request): View
    {
        return view('admin.expenses.create', [
            'selectedMonth' => $request->query('month', now()->format('Y-m')),
        ]);
    }

    public function import(): View
    {
        return view('admin.expenses.import');
    }

    public function demoImportFile(): Response
    {
        $html = view('admin.expenses.import-demo')->render();

        return response($html, 200, [
            'Content-Type' => 'application/vnd.ms-excel; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="expense-bulk-upload-demo.xls"',
        ]);
    }

    public function importStore(Request $request): RedirectResponse
    {
        $request->validate([
            'import_file' => ['required', 'file', 'max:2048'],
        ], [
            'import_file.required' => 'Excel/CSV file upload করুন।',
        ]);

        $extension = strtolower($request->file('import_file')->getClientOriginalExtension());

        if (! in_array($extension, ['csv', 'txt', 'xls'], true)) {
            return back()->withErrors(['import_file' => 'File CSV বা demo Excel (.xls) হতে হবে।']);
        }

        $rows = $this->importRows($request->file('import_file')->getRealPath(), $extension);
        $created = 0;
        $errors = [];

        DB::transaction(function () use ($rows, &$created, &$errors): void {
            foreach ($rows as $index => $row) {
                $rowNumber = $index + 2;
                $data = [
                    'expense_date' => $this->normalizeDate($row[0] ?? ''),
                    'sector' => trim((string) ($row[1] ?? '')),
                    'description' => trim((string) ($row[2] ?? '')),
                    'amount' => $this->normalizeAmount($row[3] ?? ''),
                    'voucher_no' => trim((string) ($row[4] ?? '')),
                ];

                if ($data['expense_date'] === '' && $data['sector'] === '' && $data['description'] === '' && $data['amount'] === '') {
                    continue;
                }

                $validator = Validator::make($data, [
                    'expense_date' => ['required', 'date'],
                    'sector' => ['required', 'string', 'max:255'],
                    'description' => ['required', 'string'],
                    'amount' => ['required', 'numeric', 'min:0'],
                    'voucher_no' => ['nullable', 'string', 'max:255'],
                ]);

                if ($validator->fails()) {
                    $errors[] = 'Row '.$rowNumber.': '.$validator->errors()->first();

                    continue;
                }

                Expense::query()->create([
                    'expense_month' => substr($data['expense_date'], 0, 7),
                    'expense_date' => $data['expense_date'],
                    'sector' => $data['sector'],
                    'description' => e($data['description']),
                    'amount' => $data['amount'],
                    'voucher_no' => $data['voucher_no'] ?: null,
                    'approval' => null,
                ]);

                $created++;
            }
        });

        if ($created === 0) {
            return back()->withErrors(['import_file' => $errors[0] ?? 'কোনো valid row পাওয়া যায়নি।']);
        }

        return redirect()
            ->route('admin.expenses.index', ['month' => now()->format('Y-m')])
            ->with('status', $created.'টি খরচ bulk upload হয়েছে।'.($errors ? ' কিছু row skip হয়েছে।' : ''));
    }

    public function show(Expense $expense): View
    {
        return view('admin.expenses.show', [
            'expense' => $expense,
        ]);
    }

    public function edit(Expense $expense): View
    {
        return view('admin.expenses.edit', [
            'expense' => $expense,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $this->normalizeAmountInputs($request);

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

    public function update(Request $request, Expense $expense): RedirectResponse
    {
        $this->normalizeAmountInputs($request);

        $validated = $request->validate([
            'expense_date' => ['required', 'date'],
            'sector' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'amount' => ['required', 'numeric', 'min:0'],
            'voucher_no' => ['nullable', 'string', 'max:255'],
            'approval' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ], [
            'expense_date.required' => 'তারিখ দিন।',
            'sector.required' => 'খাত দিন।',
            'description.required' => 'বিবরণ দিন।',
            'amount.required' => 'টাকার পরিমাণ দিন।',
            'amount.numeric' => 'টাকার পরিমাণ সংখ্যা হতে হবে।',
            'approval.image' => 'অনুমোদন হিসেবে PNG বা JPG ছবি দিন।',
            'approval.mimes' => 'অনুমোদন শুধু PNG, JPG বা JPEG হতে পারবে।',
        ]);

        $approvalPath = $expense->approval;

        if ($request->hasFile('approval')) {
            $this->deleteApprovalSignature($expense->approval);
            $approvalPath = $this->storeApprovalSignature($validated['approval']);
        }

        $expense->update([
            'expense_month' => substr($validated['expense_date'], 0, 7),
            'expense_date' => $validated['expense_date'],
            'sector' => $validated['sector'],
            'description' => $this->sanitizeDescription($validated['description']),
            'amount' => $validated['amount'],
            'voucher_no' => $validated['voucher_no'] ?? null,
            'approval' => $approvalPath,
        ]);

        return redirect()
            ->route('admin.expenses.show', $expense)
            ->with('status', 'খরচ সফলভাবে আপডেট হয়েছে।');
    }

    public function destroy(Expense $expense): RedirectResponse
    {
        $month = $expense->expense_month;

        $this->deleteApprovalSignature($expense->approval);
        $expense->delete();

        return redirect()
            ->route('admin.expenses.index', ['month' => $month])
            ->with('status', 'খরচ সফলভাবে মুছে ফেলা হয়েছে।');
    }

    private function sanitizeDescription(string $description): string
    {
        return strip_tags($description, '<p><br><strong><b><em><i><u><ol><ul><li>');
    }

    private function normalizeAmountInputs(Request $request): void
    {
        if ($request->has('expenses')) {
            $expenses = collect($request->input('expenses', []))
                ->map(function (array $expense): array {
                    if (array_key_exists('amount', $expense)) {
                        $expense['amount'] = $this->normalizeAmount($expense['amount']);
                    }

                    return $expense;
                })
                ->all();

            $request->merge(['expenses' => $expenses]);

            return;
        }

        if ($request->has('amount')) {
            $request->merge([
                'amount' => $this->normalizeAmount($request->input('amount')),
            ]);
        }
    }

    private function normalizeAmount(mixed $amount): string
    {
        $amount = strtr((string) $amount, [
            '০' => '0',
            '১' => '1',
            '২' => '2',
            '৩' => '3',
            '৪' => '4',
            '৫' => '5',
            '৬' => '6',
            '৭' => '7',
            '৮' => '8',
            '৯' => '9',
            '٠' => '0',
            '١' => '1',
            '٢' => '2',
            '٣' => '3',
            '٤' => '4',
            '٥' => '5',
            '٦' => '6',
            '٧' => '7',
            '٨' => '8',
            '٩' => '9',
            '٫' => '.',
        ]);

        return preg_replace('/[^0-9.]/', '', $amount) ?: '';
    }

    private function normalizeDate(mixed $date): string
    {
        $date = strtr((string) $date, [
            '০' => '0',
            '۱' => '1',
            '۲' => '2',
            '۳' => '3',
            '۴' => '4',
            '۵' => '5',
            '۶' => '6',
            '۷' => '7',
            '۸' => '8',
            '۹' => '9',
            '٠' => '0',
            '١' => '1',
            '٢' => '2',
            '٣' => '3',
            '٤' => '4',
            '٥' => '5',
            '٦' => '6',
            '٧' => '7',
            '٨' => '8',
            '٩' => '9',
        ]);
        $date = preg_replace_callback('/[\x{09E6}-\x{09EF}\x{0660}-\x{0669}\x{06F0}-\x{06F9}]/u', function (array $match): string {
            $code = mb_ord($match[0]);

            return (string) match (true) {
                $code >= 0x09E6 && $code <= 0x09EF => $code - 0x09E6,
                $code >= 0x0660 && $code <= 0x0669 => $code - 0x0660,
                $code >= 0x06F0 && $code <= 0x06F9 => $code - 0x06F0,
                default => $match[0],
            };
        }, $date);
        $date = str_replace(['/', '.'], '-', trim((string) $date));

        foreach (['Y-m-d', 'd-m-Y', 'd-m-y'] as $format) {
            try {
                return Carbon::createFromFormat($format, $date)->format('Y-m-d');
            } catch (\Throwable) {
                //
            }
        }

        try {
            return Carbon::parse($date)->format('Y-m-d');
        } catch (\Throwable) {
            return '';
        }
    }

    private function importRows(string $path, string $extension): array
    {
        $content = (string) file_get_contents($path);

        if (strtolower($extension) === 'xls' && str_contains(strtolower($content), '<table')) {
            return $this->htmlTableRows($content);
        }

        $rows = [];
        $handle = fopen($path, 'r');

        if (! $handle) {
            return [];
        }

        while (($row = fgetcsv($handle)) !== false) {
            $rows[] = array_map(fn ($value): string => trim((string) $value), $row);
        }

        fclose($handle);

        return array_slice($rows, 1);
    }

    private function htmlTableRows(string $html): array
    {
        preg_match_all('/<tr[^>]*>(.*?)<\/tr>/is', $html, $rowMatches);
        $rows = [];

        foreach ($rowMatches[1] as $rowHtml) {
            preg_match_all('/<t[dh][^>]*>(.*?)<\/t[dh]>/is', $rowHtml, $cellMatches);
            $rows[] = array_map(
                fn (string $cell): string => trim(html_entity_decode(strip_tags($cell), ENT_QUOTES, 'UTF-8')),
                $cellMatches[1]
            );
        }

        return array_slice($rows, 1);
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

    private function deleteApprovalSignature(?string $path): void
    {
        if (! $path) {
            return;
        }

        $fullPath = public_path($path);

        if (is_file($fullPath)) {
            unlink($fullPath);
        }
    }
}
