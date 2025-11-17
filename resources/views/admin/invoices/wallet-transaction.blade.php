<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Invoice #{{ $transaction->id }}</title>
    <style>
        /* mPDF compatible CSS */
        body {
            font-family: 'XB Riyaz', 'aealarabiya', 'Traditional Arabic', sans-serif;
            font-size: 14px;
            line-height: 1.6;
            direction: rtl;
            text-align: right;
            margin: 0;
            padding: 20px;
            color: #333;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
        }

        .header h1 {
            font-size: 24px;
            margin: 0 0 10px 0;
            color: #2c3e50;
        }

        .info-section {
            margin-bottom: 25px;
        }

        .info-card {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 5px;
            padding: 15px;
            margin-bottom: 15px;
        }

        .info-card h3 {
            margin: 0 0 15px 0;
            color: #2c3e50;
            border-bottom: 1px solid #002fff;
            padding-bottom: 8px;
        }

        .info-row {
            margin-bottom: 8px;
            display: table;
            width: 100%;
        }

        .info-label {
            display: table-cell;
            width: 30%;
            font-weight: bold;
            color: #555;
        }

        .info-value {
            display: table-cell;
            width: 70%;
        }

        .service-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        .service-table th {
            background-color: #002fff;
            color: white;
            padding: 12px;
            text-align: right;
            font-weight: bold;
        }

        .service-table td {
            padding: 10px;
            border: 1px solid #dee2e6;
            text-align: right;
        }

        .amount-summary {
            width: 300px;
            margin: 20px 0 20px auto;
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            padding: 15px;
            border-radius: 5px;
        }

        .summary-row {
            display: table;
            width: 100%;
            margin-bottom: 8px;
        }

        .summary-label {
            display: table-cell;
        }

        .summary-value {
            display: table-cell;
            text-align: left;
            font-weight: bold;
        }

        .total-row {
            border-top: 2px solid #002eff;
            padding-top: 10px;
            margin-top: 10px;
            font-size: 16px;
            font-weight: bold;
        }

        .footer {
            text-align: center;
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #dee2e6;
            color: #666;
            font-size: 12px;
        }

        .arabic-text {
            font-family: 'XB Riyaz', 'aealarabiya', 'Traditional Arabic', sans-serif;
            direction: rtl;
        }

        .english-text {
            font-family: Arial, sans-serif;
            direction: ltr;
            text-align: left;
        }
    </style>
</head>
<body>
<div class="header">
    <h1 class="arabic-text">فاتورة</h1>
    <h1 class="english-text">INVOICE</h1>
    <p>#{{ str_pad($transaction->id, 6, '0', STR_PAD_LEFT) }}</p>
</div>

<div class="info-section">
    <div class="info-card">
        <h3 class="arabic-text">معلومات الفاتورة / Invoice Details</h3>

        @if($transaction->employeeStage && $transaction->employeeStage->employee && $transaction->employeeStage->employee->company)
            <div class="info-row">
                <span class="info-label arabic-text">الشركة / Company:</span>
                <span class="info-value arabic-text">{{ $transaction->employeeStage->employee->company->name ?? 'N/A' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label arabic-text">الموظف / Employee:</span>
                <span class="info-value arabic-text">{{ $transaction->employeeStage->employee->name ?? 'N/A' }}</span>
            </div>
        @else
            <div class="info-row">
                <span class="info-value arabic-text">تعبئة رصيد المحفظة / Wallet Top-up</span>
            </div>
        @endif

        <div class="info-row">
            <span class="info-label">Invoice Date:</span>
            <span class="info-value">{{ $transaction->completed_at ? $transaction->completed_at->format('d M Y') : $transaction->created_at->format('d M Y') }}</span>
        </div>
    </div>
</div>

<table class="service-table">
    <thead>
    <tr>
        <th class="arabic-text">الوصف / Description</th>
        <th class="arabic-text">المبلغ / Amount</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td class="arabic-text">
            @if($transaction->employeeStage && $transaction->employeeStage->stage)
                {{ $transaction->employeeStage->stage->name ?? 'N/A' }}
            @else
                تعبئة رصيد المحفظة / Wallet Top-up
            @endif
        </td>
        <td class="english-text">{{ number_format($transaction->amount, 2) }} SAR</td>
    </tr>
    </tbody>
</table>

<div class="amount-summary">
    <div class="summary-row total-row">
        <span class="summary-label arabic-text">الإجمالي / Total:</span>
        <span class="summary-value english-text">{{ number_format($transaction->amount, 2) }} SAR</span>
    </div>
</div>

<div class="footer">
    <p class="arabic-text">شكراً لتعاملكم معنا</p>
    <p class="english-text">Thank you for your business!</p>
    <p>Generated on: {{ now()->format('d M Y, h:i A') }}</p>
</div>
</body>
</html>
