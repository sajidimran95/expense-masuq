<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: kalpurush, sans-serif;
            font-size: 10.5px;
            color: #111827;
        }

        h1 {
            margin: 0 0 6px;
            font-size: 20px;
            text-align: center;
        }

        h2 {
            margin: 22px 0 8px;
            padding: 8px 10px;
            font-size: 16px;
            text-align: center;
            background: #dbeafe;
            border: 1px solid #93c5fd;
        }

        .meta {
            margin-bottom: 18px;
            text-align: center;
            color: #4b5563;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            page-break-inside: auto;
        }

        th,
        td {
            padding: 5px;
            border: 1px solid #cbd5e1;
            vertical-align: top;
        }

        th {
            background: #f1f5f9;
            font-weight: bold;
            text-align: center;
            white-space: nowrap;
        }

        span,
        p,
        li,
        strong,
        b,
        em,
        i,
        u {
            font-family: kalpurush, sans-serif;
        }

        .ql-ui {
            display: none;
        }

        .amount {
            text-align: right;
            white-space: nowrap;
        }

        .month-total td {
            background: #ecfdf5;
            font-weight: bold;
            font-size: 12px;
        }

        .total-label {
            text-align: right;
            font-weight: bold;
        }

        .grand-total {
            margin-top: 18px;
            padding: 10px;
            border: 1px solid #16a34a;
            background: #dcfce7;
            font-size: 14px;
            font-weight: bold;
            text-align: right;
        }

        .empty {
            padding: 20px;
            border: 1px solid #cbd5e1;
            text-align: center;
            color: #64748b;
        }

        .signature {
            width: 55px;
            max-height: 28px;
        }
    </style>
</head>
<body>
    <h1>Expense Management</h1>
    <div class="meta">{{ $reportTitle }}</div>

    @forelse ($groups as $month => $expenses)
        <h2>{{ $monthLabel($month) }}</h2>

        <table>
            <thead>
                <tr>
                    <th width="8%">ক্রমিক</th>
                    <th width="12%">তারিখ</th>
                    <th width="14%">খাত</th>
                    <th>বিবরণ</th>
                    <th width="14%">টাকা</th>
                    <th width="13%">ভাউচার</th>
                    <th width="11%">অনুমোদন</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($expenses as $expense)
                    <tr>
                        <td>{{ $bn($loop->iteration) }}</td>
                        <td>{{ $bn($expense->expense_date->format('d-m-Y')) }}</td>
                        <td>{{ $expense->sector }}</td>
                        <td>{!! $cleanDescription($expense->description) !!}</td>
                        <td class="amount">{{ $bn(number_format((float) $expense->amount, 2)) }} টাকা</td>
                        <td>{{ $expense->voucher_no ?: '-' }}</td>
                        <td>
                            @if ($signaturePath($expense->approval))
                                <img src="{{ $signaturePath($expense->approval) }}" class="signature" alt="অনুমোদন">
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                @endforeach
                <tr class="month-total">
                    <td colspan="4" class="total-label">মোট</td>
                    <td class="amount">{{ $bn(number_format((float) $expenses->sum('amount'), 2)) }} টাকা</td>
                    <td colspan="2"></td>
                </tr>
            </tbody>
        </table>
    @empty
        <div class="empty">এই রিপোর্টে কোনো খরচ পাওয়া যায়নি।</div>
    @endforelse

    <div class="grand-total">
        সর্বমোট: {{ $bn(number_format((float) $grandTotal, 2)) }} টাকা
    </div>
</body>
</html>
