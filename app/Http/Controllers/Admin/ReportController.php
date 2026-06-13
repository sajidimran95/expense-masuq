<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;
use Mpdf\Config\ConfigVariables;
use Mpdf\Config\FontVariables;
use Mpdf\Mpdf;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportController extends Controller
{
    public function index(Request $request): View
    {
        $data = $this->reportData($request);
        $query = $this->filteredQuery($data);

        $expenses = (clone $query)
            ->latest('expense_date')
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.reports.index', array_merge($data, [
            'expenses' => $expenses,
            'totalAmount' => (clone $query)->sum('amount'),
            'totalEntries' => (clone $query)->count(),
        ]));
    }

    public function exportCsv(Request $request): StreamedResponse
    {
        $data = $this->reportData($request);
        $groups = $this->groupedExpenses($data);
        $filename = $this->exportFileName($data, 'csv');

        return response()->streamDownload(function () use ($groups, $data): void {
            $handle = fopen('php://output', 'w');

            fwrite($handle, "\xEF\xBB\xBF");
            fputcsv($handle, [$data['reportTitle']]);
            fputcsv($handle, []);

            foreach ($groups as $month => $expenses) {
                fputcsv($handle, [$this->monthLabel($month)]);
                fputcsv($handle, ['ক্রমিক নং', 'তারিখ', 'খাত', 'বিবরণ', 'টাকার পরিমাণ', 'ভাউচার নং', 'অনুমোদন']);

                $monthTotal = 0;

                foreach ($expenses as $index => $expense) {
                    $monthTotal += (float) $expense->amount;

                    fputcsv($handle, [
                        $index + 1,
                        $expense->expense_date->format('d-m-Y'),
                        $expense->sector,
                        strip_tags($expense->description),
                        number_format((float) $expense->amount, 2),
                        $expense->voucher_no ?: '-',
                        $expense->approval ? 'আছে' : '-',
                    ]);
                }

                fputcsv($handle, ['', '', '', 'TOTAL', number_format($monthTotal, 2), '', '']);
                fputcsv($handle, []);
            }

            fputcsv($handle, ['', '', '', 'GRAND TOTAL', number_format($this->grandTotal($groups), 2), '', '']);
            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    public function exportPdf(Request $request): Response
    {
        $data = $this->reportData($request);
        $groups = $this->groupedExpenses($data);
        $html = view('admin.reports.pdf', array_merge($data, [
            'groups' => $groups,
            'grandTotal' => $this->grandTotal($groups),
            'monthLabel' => fn (string $month): string => $this->monthLabel($month),
            'cleanDescription' => fn (string $description): string => $this->cleanPdfDescription($description),
            'bn' => fn (string|int|float $value): string => $this->banglaNumber($value),
            'signaturePath' => fn (?string $path): ?string => $this->pdfImagePath($path),
        ]))->render();
        $config = (new ConfigVariables())->getDefaults();
        $fontConfig = (new FontVariables())->getDefaults();

        $pdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'fontDir' => array_merge($config['fontDir'], [
                storage_path('fonts'),
            ]),
            'fontdata' => $fontConfig['fontdata'] + [
                'kalpurush' => [
                    'R' => 'kalpurush.ttf',
                    'B' => 'kalpurush.ttf',
                    'useOTL' => 0xFF,
                ],
            ],
            'default_font' => 'kalpurush',
            'autoScriptToLang' => false,
            'autoLangToFont' => false,
            'margin_top' => 12,
            'margin_right' => 10,
            'margin_bottom' => 12,
            'margin_left' => 10,
        ]);

        $pdf->WriteHTML($html);

        return response($pdf->Output('', 'S'), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => $this->downloadHeader($this->exportFileName($data, 'pdf'), 'expense-report.pdf'),
        ]);
    }

    public function exportExcel(Request $request): Response
    {
        $data = $this->reportData($request);
        $groups = $this->groupedExpenses($data);
        $html = view('admin.reports.excel', array_merge($data, [
            'groups' => $groups,
            'grandTotal' => $this->grandTotal($groups),
            'monthLabel' => fn (string $month): string => $this->monthLabel($month),
            'cleanDescription' => fn (string $description): string => strip_tags($description),
            'bn' => fn (string|int|float $value): string => $this->banglaNumber($value),
            'signatureSource' => fn (?string $path): ?string => $this->excelImageSource($path),
        ]))->render();

        return response($html, 200, [
            'Content-Type' => 'application/vnd.ms-excel; charset=UTF-8',
            'Content-Disposition' => $this->downloadHeader($this->exportFileName($data, 'xls'), 'expense-report.xls'),
        ]);
    }

    private function reportData(Request $request): array
    {
        $reportType = $request->query('type', 'all');
        $reportType = in_array($reportType, ['all', 'monthly', 'date', 'date_range', 'yearly'], true) ? $reportType : 'monthly';
        $selectedMonth = $request->query('month', now()->format('Y-m'));
        $selectedDate = $request->query('date', now()->toDateString());
        $selectedStartDate = $request->query('start_date', now()->startOfMonth()->toDateString());
        $selectedEndDate = $request->query('end_date', now()->toDateString());
        $selectedYear = $request->query('year', now()->format('Y'));

        if ($reportType === 'all') {
            $reportTitle = 'সকল খরচের রিপোর্ট';
        } elseif ($reportType === 'date') {
            $reportTitle = $this->banglaNumber(date('d-m-Y', strtotime($selectedDate))).' তারিখের রিপোর্ট';
        } elseif ($reportType === 'date_range') {
            $startDate = Carbon::parse($selectedStartDate);
            $endDate = Carbon::parse($selectedEndDate);

            if ($startDate->gt($endDate)) {
                [$startDate, $endDate] = [$endDate, $startDate];
                $selectedStartDate = $startDate->toDateString();
                $selectedEndDate = $endDate->toDateString();
            }

            $reportTitle = $this->banglaNumber($startDate->format('d-m-Y')).' থেকে '.$this->banglaNumber($endDate->format('d-m-Y')).' রিপোর্ট';
        } elseif ($reportType === 'yearly') {
            $reportTitle = $this->banglaNumber($selectedYear).' সালের রিপোর্ট';
        } else {
            $reportTitle = $this->monthLabel($selectedMonth).' মাসের রিপোর্ট';
        }

        return compact('reportType', 'selectedMonth', 'selectedDate', 'selectedStartDate', 'selectedEndDate', 'selectedYear', 'reportTitle');
    }

    private function filteredQuery(array $data): \Illuminate\Database\Eloquent\Builder
    {
        $query = Expense::query();

        if ($data['reportType'] === 'all') {
            return $query;
        }

        if ($data['reportType'] === 'date') {
            return $query->whereDate('expense_date', $data['selectedDate']);
        }

        if ($data['reportType'] === 'date_range') {
            return $query->whereBetween('expense_date', [$data['selectedStartDate'], $data['selectedEndDate']]);
        }

        if ($data['reportType'] === 'yearly') {
            return $query->whereYear('expense_date', $data['selectedYear']);
        }

        return $query->where('expense_month', $data['selectedMonth']);
    }

    private function groupedExpenses(array $data): \Illuminate\Support\Collection
    {
        return $this->filteredQuery($data)
            ->orderBy('expense_month')
            ->orderBy('expense_date')
            ->orderBy('id')
            ->get()
            ->groupBy('expense_month');
    }

    private function grandTotal(\Illuminate\Support\Collection $groups): float
    {
        return (float) $groups->sum(fn ($expenses): float => (float) $expenses->sum('amount'));
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

        return $months[$date->format('F')].'-'.$this->banglaNumber($date->format('Y'));
    }

    private function exportFileName(array $data, string $extension): string
    {
        if ($data['reportType'] === 'all') {
            return 'সকল-খরচের-রিপোর্ট.'.$extension;
        }

        if ($data['reportType'] === 'yearly') {
            return $this->banglaNumber($data['selectedYear']).'-সালের-রিপোর্ট.'.$extension;
        }

        if ($data['reportType'] === 'date') {
            return $this->banglaNumber(Carbon::parse($data['selectedDate'])->format('d-m-Y')).'-তারিখের-রিপোর্ট.'.$extension;
        }

        if ($data['reportType'] === 'date_range') {
            return $this->banglaNumber(Carbon::parse($data['selectedStartDate'])->format('d-m-Y')).'-থেকে-'.$this->banglaNumber(Carbon::parse($data['selectedEndDate'])->format('d-m-Y')).'-রিপোর্ট.'.$extension;
        }

        return $this->monthLabel($data['selectedMonth']).'-মাসের-রিপোর্ট.'.$extension;
    }

    private function downloadHeader(string $filename, string $fallback): string
    {
        return "attachment; filename=\"{$fallback}\"; filename*=UTF-8''".rawurlencode($filename);
    }

    private function cleanPdfDescription(string $description): string
    {
        return strip_tags($description, '<p><br><strong><b><em><i><u><ol><ul><li>');
    }

    private function banglaNumber(string|int|float $value): string
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

    private function pdfImagePath(?string $path): ?string
    {
        if (! $path) {
            return null;
        }

        $fullPath = public_path($path);

        return is_file($fullPath) ? $fullPath : null;
    }

    private function excelImageSource(?string $path): ?string
    {
        if (! $path) {
            return null;
        }

        $fullPath = public_path($path);

        if (! is_file($fullPath)) {
            return null;
        }

        $mime = mime_content_type($fullPath) ?: 'image/png';

        return 'data:'.$mime.';base64,'.base64_encode((string) file_get_contents($fullPath));
    }
}
