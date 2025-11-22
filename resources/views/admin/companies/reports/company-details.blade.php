<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="UTF-8">
    <title>{{ __('Company Report') }} - {{ $company->name }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 9px;
            line-height: 1.4;
            color: #333;
        }

        .header {
            background: #667eea;
            color: white;
            padding: 15px 20px;
            margin-bottom: 15px;
            border-radius: 0;
            border-bottom: 3px solid #5568d3;
        }

        .header h1 {
            font-size: 22px;
            margin-bottom: 3px;
            font-weight: bold;
            letter-spacing: 0.5px;
        }

        .header p {
            font-size: 9px;
            opacity: 0.95;
        }

        .company-info {
            background: #ffffff;
            padding: 0;
            margin-bottom: 15px;
            border: 3px solid #667eea;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(102, 126, 234, 0.15);
        }

        .company-info-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 14px 18px;
            border-bottom: 3px solid #5568d3;
        }

        .company-info-header h2 {
            font-size: 17px;
            margin: 0;
            font-weight: bold;
            letter-spacing: 0.3px;
        }

        .company-info-body {
            padding: 16px;
            background: linear-gradient(to bottom, #f8f9ff 0%, #ffffff 100%);
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
            background: white;
            border-radius: 6px;
            overflow: hidden;
        }

        .info-table tr {
            border-bottom: 1px solid #e8eaf6;
        }

        .info-table tr:last-child {
            border-bottom: none;
        }

        .info-table td {
            padding: 10px 12px;
            font-size: 9px;
        }

        .info-table td:first-child {
            font-weight: bold;
            color: #667eea;
            width: 30%;
            background: #f5f7ff;
        }

        .info-table td:last-child {
            color: #333;
        }

        .wallet-highlight {
            background: linear-gradient(135deg, #e8f5e9 0%, #f1f8f4 100%);
            border: 3px solid #4CAF50;
            border-radius: 8px;
            padding: 14px;
            text-align: center;
            box-shadow: 0 2px 6px rgba(76, 175, 80, 0.2);
        }

        .wallet-highlight .label {
            font-size: 9px;
            color: #2e7d32;
            font-weight: bold;
            display: block;
            margin-bottom: 6px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .wallet-highlight .amount {
            font-size: 22px;
            color: #1b5e20;
            font-weight: bold;
            text-shadow: 0 1px 2px rgba(27, 94, 32, 0.1);
        }

        .summary-table {
            width: 100%;
            margin-bottom: 15px;
            border-collapse: separate;
            border-spacing: 8px;
        }

        .summary-card {
            background: #ffffff;
            border: 3px solid #e0e0e0;
            border-radius: 8px;
            padding: 12px 10px;
            text-align: center;
            vertical-align: top;
            width: 25%;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
        }

        .summary-card.primary {
            border-color: #4CAF50;
            background: linear-gradient(to bottom, #ffffff 0%, #f1f8f4 100%);
        }

        .summary-card.warning {
            border-color: #FF9800;
            background: linear-gradient(to bottom, #ffffff 0%, #fff8f0 100%);
        }

        .summary-card.info {
            border-color: #2196F3;
            background: linear-gradient(to bottom, #ffffff 0%, #f0f7ff 100%);
        }

        .summary-card.success {
            border-color: #8BC34A;
            background: linear-gradient(to bottom, #ffffff 0%, #f5f9f0 100%);
        }

        .summary-card h3 {
            font-size: 8px;
            color: #666;
            text-transform: uppercase;
            margin: 0 0 8px 0;
            line-height: 1.3;
            font-weight: bold;
            letter-spacing: 0.5px;
        }

        .summary-card .value {
            font-size: 20px;
            font-weight: bold;
            color: #333;
            margin: 0 0 5px 0;
            line-height: 1.2;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
        }

        .summary-card .subtitle {
            font-size: 7px;
            color: #888;
            line-height: 1.3;
            margin: 0;
            font-weight: 500;
        }

        .section {
            margin-bottom: 25px;
            page-break-inside: avoid;
        }

        .section-title {
            background: #667eea;
            color: white;
            padding: 10px 15px;
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 10px;
            border-radius: 3px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
            font-size: 7px;
        }

        table thead {
            background: #f1f3f5;
        }

        table th {
            padding: 6px 3px;
            text-align: left;
            font-weight: bold;
            border-bottom: 2px solid #dee2e6;
            font-size: 7px;
        }

        table td {
            padding: 5px 3px;
            border-bottom: 1px solid #e9ecef;
        }

        table tbody tr:hover {
            background: #f8f9fa;
        }

        table tfoot {
            background: #e9ecef;
            font-weight: bold;
        }

        table tfoot td {
            padding: 8px 3px;
            border-top: 2px solid #dee2e6;
        }

        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 7px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .badge-success {
            background: #d4edda;
            color: #155724;
        }

        .badge-warning {
            background: #fff3cd;
            color: #856404;
        }

        .badge-danger {
            background: #f8d7da;
            color: #721c24;
        }

        .badge-info {
            background: #d1ecf1;
            color: #0c5460;
        }

        .badge-primary {
            background: #cce5ff;
            color: #004085;
        }

        .text-success {
            color: #28a745;
        }

        .text-danger {
            color: #dc3545;
        }

        .text-warning {
            color: #ffc107;
        }

        .text-primary {
            color: #007bff;
        }

        .text-muted {
            color: #6c757d;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .font-weight-bold {
            font-weight: bold;
        }

        .footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 2px solid #dee2e6;
            text-align: center;
            font-size: 8px;
            color: #6c757d;
        }

        .page-break {
            page-break-before: always;
        }

        .no-data {
            text-align: center;
            padding: 30px;
            color: #999;
            font-style: italic;
        }
    </style>
</head>

<body>

    {{-- Header --}}
    <div class="header">
        <h1>{{ __('Company Detailed Report') }}</h1>
        <p>{{ __('Generated on') }}: {{ $generatedDate }}</p>
    </div>

    {{-- Company Information --}}
    <div class="company-info">
        <div class="company-info-header">
            <h2>{{ $company->getTranslation('name', app()->getLocale()) }}</h2>
        </div>
        <div class="company-info-body">
            <table class="info-table">
                <tr>
                    <td>{{ __('Description') }}</td>
                    <td>{{ $company->getTranslation('description', app()->getLocale()) }}</td>
                </tr>
                <tr>
                    <td>{{ __('Status') }}</td>
                    <td>
                        <span class="badge badge-{{ $company->status == 'active' ? 'success' : 'danger' }}">
                            {{ ucfirst($company->status) }}
                        </span>
                    </td>
                </tr>
            </table>

            <div class="wallet-highlight">
                <span class="label">{{ __('Current Wallet Balance') }}</span>
                <span class="amount">{{ number_format($summary['current_wallet_balance'], 2) }}
                    {{ __('SAR') }}</span>
            </div>
        </div>
    </div>

    {{-- Summary Statistics - 2 Rows for Portrait Layout --}}
    <table class="summary-table" cellpadding="0" cellspacing="8">
        {{-- First Row: Main Financial Metrics --}}
        <tr>
            <td class="summary-card info">
                <h3>{{ __('Total Employees') }}</h3>
                <div class="value">{{ $summary['total_employees'] }}</div>
                <div class="subtitle">{{ __('Active Employees') }}</div>
            </td>
            <td class="summary-card warning">
                <h3>{{ __('Total Cost') }}</h3>
                <div class="value text-danger">{{ number_format($summary['total_cost'], 2) }}</div>
                <div class="subtitle">{{ __('SAR') }}</div>
            </td>
            <td class="summary-card primary">
                <h3>{{ __('Total Revenue') }}</h3>
                <div class="value text-primary">{{ number_format($summary['total_price'], 2) }}</div>
                <div class="subtitle">{{ __('SAR') }}</div>
            </td>
            <td class="summary-card success">
                <h3>{{ __('Total Profit') }}</h3>
                <div class="value text-success">{{ number_format($summary['total_profit'], 2) }}</div>
                <div class="subtitle">{{ number_format($summary['profit_margin'], 1) }}% {{ __('Margin') }}</div>
            </td>
        </tr>

        {{-- Second Row: Transaction Metrics --}}
        <tr>
            <td class="summary-card">
                <h3>{{ __('Completed Stages') }}</h3>
                <div class="value">{{ $summary['total_stages_completed'] }}</div>
            </td>
            <td class="summary-card">
                <h3>{{ __('Total Wallet Charges') }}</h3>
                <div class="value">{{ number_format($summary['total_wallet_charges'], 2) }}</div>
                <div class="subtitle">{{ __('SAR') }}</div>
            </td>
            <td class="summary-card">
                <h3>{{ __('Wallet Transactions') }}</h3>
                <div class="value">{{ $walletTransactions->count() }}</div>
            </td>
            <td class="summary-card">
                <h3>{{ __('Payment Transactions') }}</h3>
                <div class="value">{{ $paymentTransactions->count() }}</div>
            </td>
        </tr>
    </table>


    {{-- Wallet Transactions Section --}}
    <div class="section">
        <div class="section-title">{{ __('Wallet Transactions (Company Payments)') }}</div>

        @if ($walletTransactions->count() > 0)
            <table>
                <thead>
                    <tr>
                        <th style="width: 5%">{{ __('ID') }}</th>
                        <th style="width: 10%">{{ __('Date') }}</th>
                        <th style="width: 15%">{{ __('Employee') }}</th>
                        <th style="width: 15%">{{ __('Stage') }}</th>
                        <th style="width: 10%">{{ __('Price') }}</th>
                        <th style="width: 10%">{{ __('Cost') }}</th>
                        <th style="width: 10%">{{ __('Profit') }}</th>
                        <th style="width: 10%">{{ __('Status') }}</th>
                        <th style="width: 15%">{{ __('Processed By') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($walletTransactions as $transaction)
                        @php
                            $cost = $transaction->employeeStage ? $transaction->employeeStage->amount_cost ?? 0 : 0;
                            $profit = $transaction->amount - $cost;
                        @endphp
                        <tr>
                            <td><strong>#{{ $transaction->id }}</strong></td>
                            <td>{{ $transaction->completed_at ? $transaction->completed_at->format('Y-m-d H:i') : $transaction->created_at->format('Y-m-d H:i') }}
                            </td>
                            <td>
                                @if ($transaction->employeeStage && $transaction->employeeStage->employee)
                                    {{ $transaction->employeeStage->employee->name }}
                                @else
                                    <span class="text-muted">{{ __('Wallet Charge') }}</span>
                                @endif
                            </td>
                            <td>
                                @if ($transaction->employeeStage && $transaction->employeeStage->stage)
                                    <span
                                        class="badge badge-info">{{ $transaction->employeeStage->stage->name }}</span>
                                @else
                                    —
                                @endif
                            </td>
                            <td class="text-primary font-weight-bold">{{ number_format($transaction->amount, 2) }}</td>
                            <td class="text-danger">
                                @if ($cost > 0)
                                    {{ number_format($cost, 2) }}
                                @else
                                    —
                                @endif
                            </td>
                            <td class="text-success font-weight-bold">
                                @if ($profit > 0)
                                    {{ number_format($profit, 2) }}
                                @else
                                    —
                                @endif
                            </td>
                            <td>
                                <span
                                    class="badge badge-{{ $transaction->status == 'completed' ? 'success' : 'warning' }}">
                                    {{ ucfirst($transaction->status) }}
                                </span>
                            </td>
                            <td>{{ $transaction->user ? $transaction->user->name : '—' }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="4" class="text-right">{{ __('TOTALS') }}:</td>
                        <td class="text-primary">{{ number_format($walletTransactions->sum('amount'), 2) }}</td>
                        <td class="text-danger">
                            {{ number_format(
                                $walletTransactions->sum(function ($t) {
                                    return $t->employeeStage ? $t->employeeStage->amount_cost ?? 0 : 0;
                                }),
                                2,
                            ) }}
                        </td>
                        <td class="text-success">
                            {{ number_format(
                                $walletTransactions->sum(function ($t) {
                                    $cost = $t->employeeStage ? $t->employeeStage->amount_cost ?? 0 : 0;
                                    return $t->amount - $cost;
                                }),
                                2,
                            ) }}
                        </td>
                        <td colspan="2"></td>
                    </tr>
                </tfoot>
            </table>
        @else
            <div class="no-data">{{ __('No wallet transactions found') }}</div>
        @endif
    </div>

    {{-- Page Break --}}
    <div class="page-break"></div>

    {{-- Payment Account Transactions Section --}}
    <div class="section">
        <div class="section-title">{{ __('Payment Account Transactions (Processing Costs)') }}</div>

        @if ($paymentTransactions->count() > 0)
            <table>
                <thead>
                    <tr>
                        <th style="width: 5%">{{ __('ID') }}</th>
                        <th style="width: 12%">{{ __('Date') }}</th>
                        <th style="width: 15%">{{ __('Employee') }}</th>
                        <th style="width: 15%">{{ __('Stage') }}</th>
                        <th style="width: 15%">{{ __('Payment Account') }}</th>
                        <th style="width: 10%">{{ __('Amount') }}</th>
                        <th style="width: 10%">{{ __('Balance Before') }}</th>
                        <th style="width: 10%">{{ __('Balance After') }}</th>
                        <th style="width: 8%">{{ __('Status') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($paymentTransactions as $transaction)
                        <tr>
                            <td><strong>#{{ $transaction->id }}</strong></td>
                            <td>{{ $transaction->processed_at ? $transaction->processed_at->format('Y-m-d H:i') : $transaction->created_at->format('Y-m-d H:i') }}
                            </td>
                            <td>
                                @if ($transaction->employeeStage && $transaction->employeeStage->employee)
                                    {{ $transaction->employeeStage->employee->name }}
                                @else
                                    —
                                @endif
                            </td>
                            <td>
                                @if ($transaction->employeeStage && $transaction->employeeStage->stage)
                                    <span
                                        class="badge badge-info">{{ $transaction->employeeStage->stage->name }}</span>
                                @else
                                    —
                                @endif
                            </td>
                            <td>{{ $transaction->paymentAccount ? $transaction->paymentAccount->name : '—' }}</td>
                            <td class="text-danger font-weight-bold">{{ number_format($transaction->amount, 2) }}</td>
                            <td>{{ number_format($transaction->from_balance_before ?? 0, 2) }}</td>
                            <td>{{ number_format($transaction->from_balance_after ?? 0, 2) }}</td>
                            <td>
                                <span
                                    class="badge badge-{{ $transaction->status == 'completed' ? 'success' : 'warning' }}">
                                    {{ ucfirst($transaction->status) }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="5" class="text-right">{{ __('TOTAL COST') }}:</td>
                        <td class="text-danger font-weight-bold">
                            {{ number_format($paymentTransactions->sum('amount'), 2) }}</td>
                        <td colspan="3"></td>
                    </tr>
                </tfoot>
            </table>
        @else
            <div class="no-data">{{ __('No payment transactions found') }}</div>
        @endif
    </div>

    {{-- Page Break --}}
    <div class="page-break"></div>

    {{-- Employee Profits Section --}}
    <div class="section">
        <div class="section-title">{{ __('Employee Profit Analysis') }}</div>

        @if ($employeeProfits->count() > 0)
            <table>
                <thead>
                    <tr>
                        <th style="width: 25%">{{ __('Employee Name') }}</th>
                        <th style="width: 12%">{{ __('Completed Stages') }}</th>
                        <th style="width: 15%">{{ __('Total Cost') }}</th>
                        <th style="width: 15%">{{ __('Total Revenue') }}</th>
                        <th style="width: 15%">{{ __('Total Profit') }}</th>
                        <th style="width: 18%">{{ __('Profit Margin') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($employeeProfits as $item)
                        @php
                            $margin =
                                $item['total_price'] > 0 ? ($item['total_profit'] / $item['total_price']) * 100 : 0;
                        @endphp
                        <tr>
                            <td class="font-weight-bold">{{ $item['employee']->name }}</td>
                            <td class="text-center">
                                <span class="badge badge-info">{{ $item['completed_stages'] }}</span>
                            </td>
                            <td class="text-danger">{{ number_format($item['total_cost'], 2) . __('SAR') }} </td>
                            <td class="text-primary">{{ number_format($item['total_price'], 2) . __('SAR') }} </td>
                            <td class="text-success font-weight-bold">
                                {{ number_format($item['total_profit'], 2) . __('SAR') }}
                            </td>
                            <td class="text-center">
                                <span
                                    class="badge badge-{{ $margin >= 20 ? 'success' : ($margin >= 10 ? 'warning' : 'danger') }}">
                                    {{ number_format($margin, 1) }}%
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td class="font-weight-bold">{{ __('TOTALS') }}</td>
                        <td class="text-center">{{ $employeeProfits->sum('completed_stages') }}</td>
                        <td class="text-danger">
                            {{ number_format($employeeProfits->sum('total_cost'), 2) . __('SAR') }} </td>
                        <td class="text-primary">
                            {{ number_format($employeeProfits->sum('total_price'), 2) . __('SAR') }}
                        </td>
                        <td class="text-success font-weight-bold">
                            {{ number_format($employeeProfits->sum('total_profit'), 2) . __('SAR') }} </td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        @else
            <div class="no-data">{{ __('No completed stages found') }}</div>
        @endif
    </div>

    {{-- Page Break --}}
    <div class="page-break"></div>

    {{-- Wallet Debit Transactions Section --}}
    <div class="section">
        <div class="section-title">{{ __('Wallet Debit Transactions') }}</div>

        @if ($debitTransactions->count() > 0)
            <table>
                <thead>
                    <tr>
                        <th style="width: 5%">{{ __('ID') }}</th>
                        <th style="width: 12%">{{ __('Date') }}</th>
                        <th style="width: 18%">{{ __('Employee') }}</th>
                        <th style="width: 18%">{{ __('Stage') }}</th>
                        <th style="width: 12%">{{ __('Amount') }}</th>
                        <th style="width: 12%">{{ __('Cost') }}</th>
                        <th style="width: 12%">{{ __('Profit') }}</th>
                        <th style="width: 11%">{{ __('Status') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($debitTransactions as $t)
                        @php
                            $cost = $t->employeeStage->amount_cost ?? 0;
                            $profit = $t->amount - $cost;
                        @endphp
                        <tr>
                            <td><strong>#{{ $t->id }}</strong></td>
                            <td>{{ $t->completed_at ? $t->completed_at->format('Y-m-d H:i') : $t->created_at->format('Y-m-d H:i') }}
                            </td>
                            <td>
                                @if ($t->employeeStage && $t->employeeStage->employee)
                                    {{ $t->employeeStage->employee->name }}
                                @else
                                    <span class="text-muted">{{ __('Wallet Charge') }}</span>
                                @endif
                            </td>
                            <td>
                                @if ($t->employeeStage && $t->employeeStage->stage)
                                    <span class="badge badge-info">{{ $t->employeeStage->stage->name }}</span>
                                @else
                                    —
                                @endif
                            </td>
                            <td class="text-primary font-weight-bold">{{ number_format($t->amount, 2) . __('SAR') }}
                            </td>
                            <td class="text-danger">
                                @if ($cost > 0)
                                    {{ number_format($cost, 2) . __('SAR') }}
                                @else
                                    —
                                @endif
                            </td>
                            <td class="text-success font-weight-bold">
                                @if ($profit > 0)
                                    {{ number_format($profit, 2) . __('SAR') }}
                                @else
                                    —
                                @endif
                            </td>
                            <td>
                                <span class="badge badge-{{ $t->status == 'completed' ? 'success' : 'warning' }}">
                                    {{ ucfirst($t->status) }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="4" class="text-right">{{ __('TOTALS') }}:</td>
                        <td class="text-primary font-weight-bold">
                            {{ number_format($debitTransactions->sum('amount'), 2) . __('SAR') }}</td>
                        <td class="text-danger">
                            {{ number_format(
                                $debitTransactions->sum(function ($t) {
                                    return $t->employeeStage ? $t->employeeStage->amount_cost ?? 0 : 0;
                                }),
                                2,
                            ) }}
                            SAR
                        </td>
                        <td class="text-success font-weight-bold">
                            {{ number_format(
                                $debitTransactions->sum(function ($t) {
                                    $cost = $t->employeeStage ? $t->employeeStage->amount_cost ?? 0 : 0;
                                    return $t->amount - $cost;
                                }),
                                2,
                            ) }}
                            SAR
                        </td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        @else
            <div class="no-data">{{ __('No debit transactions found') }}</div>
        @endif
    </div>

    {{-- Page Break --}}
    <div class="page-break"></div>

    {{-- Wallet Credit Transactions Section --}}
    <div class="section">
        <div class="section-title">{{ __('Wallet Credit Transactions') }}</div>

        @if ($creditTransactions->count() > 0)
            <table>
                <thead>
                    <tr>
                        <th style="width: 8%">{{ __('ID') }}</th>
                        <th style="width: 15%">{{ __('Date') }}</th>
                        <th style="width: 25%">{{ __('Employee') }}</th>
                        <th style="width: 25%">{{ __('Stage') }}</th>
                        <th style="width: 15%">{{ __('Amount') }}</th>
                        <th style="width: 12%">{{ __('Status') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($creditTransactions as $t)
                        <tr>
                            <td><strong>#{{ $t->id }}</strong></td>
                            <td>{{ $t->completed_at ? $t->completed_at->format('Y-m-d H:i') : $t->created_at->format('Y-m-d H:i') }}
                            </td>
                            <td>
                                @if ($t->employeeStage && $t->employeeStage->employee)
                                    {{ $t->employeeStage->employee->name }}
                                @else
                                    <span class="text-muted">{{ __('Wallet Top-up') }}</span>
                                @endif
                            </td>
                            <td>
                                @if ($t->employeeStage && $t->employeeStage->stage)
                                    <span class="badge badge-info">{{ $t->employeeStage->stage->name }}</span>
                                @else
                                    —
                                @endif
                            </td>
                            <td class="text-success font-weight-bold">{{ number_format($t->amount, 2) . __('SAR') }}
                            </td>
                            <td>
                                <span class="badge badge-{{ $t->status == 'completed' ? 'success' : 'warning' }}">
                                    {{ ucfirst($t->status) }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="4" class="text-right">{{ __('TOTAL CREDITS') }}:</td>
                        <td class="text-success font-weight-bold">
                            {{ number_format($creditTransactions->sum('amount'), 2) . __('SAR') }}</td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        @else
            <div class="no-data">{{ __('No credit transactions found') }}</div>
        @endif
    </div>

    {{-- Page Break --}}
    <div class="page-break"></div>

    {{-- All Employees List --}}
    <div class="section">
        <div class="section-title">{{ __('All Employees') }}</div>

        @if ($company->employees->count() > 0)
            <table>
                <thead>
                    <tr>
                        <th style="width: 8%">{{ __('ID') }}</th>
                        <th style="width: 22%">{{ __('Name') }}</th>
                        <th style="width: 15%">{{ __('Phone') }}</th>
                        <th style="width: 20%">{{ __('Email') }}</th>
                        <th style="width: 12%">{{ __('Total Stages') }}</th>
                        <th style="width: 12%">{{ __('Completed') }}</th>
                        <th style="width: 11%">{{ __('Status') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($company->employees as $employee)
                        <tr>
                            <td>{{ $employee->id }}</td>
                            <td class="font-weight-bold">{{ $employee->name }}</td>
                            <td>{{ $employee->phone }}</td>
                            <td>{{ $employee->email }}</td>
                            <td class="text-center">{{ $employee->stages->count() }}</td>
                            <td class="text-center">
                                <span class="badge badge-success">
                                    {{ $employee->stages->where('status', 'completed')->count() }}
                                </span>
                            </td>
                            <td class="text-center">
                                <span class="badge badge-{{ $employee->status == 'active' ? 'success' : 'danger' }}">
                                    {{ ucfirst($employee->status) }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="no-data">{{ __('No employees found') }}</div>
        @endif
    </div>

    {{-- Footer --}}
    <div class="footer">
        <p>{{ __('This report was automatically generated on') }} {{ $generatedDate }}</p>
        <p>{{ __('Company') }}: {{ $company->getTranslation('name', app()->getLocale()) }}</p>
        <p>&copy; {{ date('Y') }} {{ config('app.name') }}. {{ __('All rights reserved') }}.</p>
    </div>

</body>

</html>
