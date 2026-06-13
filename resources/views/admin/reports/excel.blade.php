<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="utf-8">
    <style>
        table {
            border-collapse: collapse;
            font-family: Kalpurush, SolaimanLipi, Arial, sans-serif;
            font-size: 12pt;
        }

        th,
        td {
            border: 1px solid #9ca3af;
            padding: 6px;
            vertical-align: top;
        }

        th {
            background: #e5efff;
            font-weight: bold;
            text-align: center;
        }

        .report-title {
            font-size: 16pt;
            font-weight: bold;
            text-align: center;
            background: #dbeafe;
        }

        .month-title {
            font-size: 14pt;
            font-weight: bold;
            text-align: center;
            background: #dbeafe;
        }

        .amount {
            text-align: right;
            white-space: nowrap;
        }

        .total-row td {
            background: #dcfce7;
            font-weight: bold;
        }

        .signature {
            width: 70px;
            height: 32px;
        }
    </style>
</head>
<body>
    <table>
        <colgroup>
            <col style="width: 70px;">
            <col style="width: 110px;">
            <col style="width: 170px;">
            <col style="width: 320px;">
            <col style="width: 130px;">
            <col style="width: 120px;">
            <col style="width: 130px;">
        </colgroup>
        @if (in_array($reportType, ['yearly', 'date_range'], true))
            <tr>
                <td colspan="7" class="report-title">{{ $reportTitle }}</td>
            </tr>
            <tr><td colspan="7"></td></tr>
        @endif

        @forelse ($groups as $month => $expenses)
            <tr>
                <td colspan="7" class="month-title">{{ in_array($reportType, ['yearly', 'date_range'], true) ? $monthLabel($month) : $reportTitle }}</td>
            </tr>
            <tr>
                <th>ক্রমিক</th>
                <th>তারিখ</th>
                <th>খাত</th>
                <th>বিবরণ</th>
                <th>টাকা</th>
                <th>ভাউচার</th>
                <th>অনুমোদন</th>
            </tr>

            @foreach ($expenses as $expense)
                <tr>
                    <td>{{ $bn($loop->iteration) }}</td>
                    <td>{{ $bn($expense->expense_date->format('d-m-Y')) }}</td>
                    <td>{{ $expense->sector }}</td>
                    <td>{{ $cleanDescription($expense->description) }}</td>
                    <td class="amount">{{ $bn(number_format((float) $expense->amount, 2)) }} টাকা</td>
                    <td>{{ $expense->voucher_no ?: '-' }}</td>
                    <td style="width: 90px; height: 38px; text-align: center; vertical-align: middle;">
                        @if ($signatureSource($expense->approval))
                            <img src="{{ $signatureSource($expense->approval) }}" class="signature" width="70" height="32" style="width: 70px; height: 32px;" alt="অনুমোদন">
                        @else
                            -
                        @endif
                    </td>
                </tr>
            @endforeach

            <tr class="total-row">
                <td colspan="4" class="amount">মোট</td>
                <td class="amount">{{ $bn(number_format((float) $expenses->sum('amount'), 2)) }} টাকা</td>
                <td colspan="2"></td>
            </tr>
            <tr><td colspan="7"></td></tr>
        @empty
            <tr>
                <td colspan="7">এই রিপোর্টে কোনো খরচ পাওয়া যায়নি।</td>
            </tr>
        @endforelse

        <tr class="total-row">
            <td colspan="4" class="amount">সর্বমোট</td>
            <td class="amount">{{ $bn(number_format((float) $grandTotal, 2)) }} টাকা</td>
            <td colspan="2"></td>
        </tr>
    </table>
</body>
</html>
