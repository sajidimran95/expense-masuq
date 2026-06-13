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
        $filename = 'expense-report-'.$data['reportType'].'-'.now()->format('YmdHis').'.csv';

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
            'Content-Disposition' => 'attachment; filename="expense-report-'.$data['reportType'].'-'.now()->format('YmdHis').'.pdf"',
        ]);
    }

    /**
     * @return array{reportType: string, selectedMonth: string, selectedDate: string, selectedYear: string, reportTitle: string}
     */
    private function reportData(Request $request): array
    {
        $reportType = $request->query('type', 'monthly');
        $reportType = in_array($reportType, ['monthly', 'date', 'yearly'], true) ? $reportType : 'monthly';
        $selectedMonth = $request->query('month', now()->format('Y-m'));
        $selectedDate = $request->query('date', now()->toDateString());
        $selectedYear = $request->query('year', now()->format('Y'));

        if ($reportType === 'date') {
            $reportTitle = date('d-m-Y', strtotime($selectedDate)).' তারিখের রিপোর্ট';
        } elseif ($reportType === 'yearly') {
            $reportTitle = $selectedYear.' সালের রিপোর্ট';
        } else {
            $reportTitle = $this->monthLabel($selectedMonth);
        }

        return compact('reportType', 'selectedMonth', 'selectedDate', 'selectedYear', 'reportTitle');
    }

    /**
     * @param  array{reportType: string, selectedMonth: string, selectedDate: string, selectedYear: string, reportTitle: string}  $data
     */
    private function filteredQuery(array $data): \Illuminate\Database\Eloquent\Builder
    {
        $query = Expense::query();

        if ($data['reportType'] === 'date') {
            return $query->whereDate('expense_date', $data['selectedDate']);
        }

        if ($data['reportType'] === 'yearly') {
            return $query->whereYear('expense_date', $data['selectedYear']);
        }

        return $query->where('expense_month', $data['selectedMonth']);
    }

    /**
     * @param  array{reportType: string, selectedMonth: string, selectedDate: string, selectedYear: string, reportTitle: string}  $data
     */
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
        return Carbon::createFromFormat('Y-m', $month)->format('F-Y');
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
}
