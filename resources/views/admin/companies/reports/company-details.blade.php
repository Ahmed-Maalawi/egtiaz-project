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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 5px;
        }

        .header h1 {
            font-size: 24px;
            margin-bottom: 5px;
        }

        .header p {
            font-size: 11px;
            opacity: 0.9;
        }

        .company-info {
            background: #f8f9fa;
            padding: 15px;
            margin-bottom: 20px;
            border-left: 4px solid #667eea;
            border-radius: 3px;
        }

        .company-info h2 {
            font-size: 16px;
            color: #667eea;
            margin-bottom: 10px;
        }

        .info-grid {
            display: table;
            width: 100%;
            margin-top: 10px;
        }

        .info-row {
            display: table-row;
        }

        .info-label {
            display: table-cell;
            font-weight: bold;
            padding: 5px 10px 5px 0;
            width: 30%;
        }

        .info-value {
            display: table-cell;
            padding: 5px 0;
        }

        .summary-cards {
            display: table;
            width: 100%;
            margin-bottom: 20px;
            border-spacing: 10px;
        }

        .summary-row {
            display: table-row;
        }

        .summary-card {
            display: table-cell;
            background: #fff;
            border: 2px solid #e0e0e0;
            border-radius: 5px;
            padding: 15px;
            text-align: center;
            width: 25%;
        }

        .summary-card.primary { border-color: #4CAF50; }
        .summary-card.warning { border-color: #FF9800; }
        .summary-card.info { border-color: #2196F3; }
        .summary-card.success { border-color: #8BC34A; }

        .summary-card h3 {
            font-size: 10px;
            color: #666;
            text-transform: uppercase;
            margin-bottom: 8px;
        }

        .summary-card .value {
            font-size: 20px;
            font-weight: bold;
            color: #333;
            margin-bottom: 5px;
        }

        .summary-card .subtitle {
            font-size: 8px;
            color: #999;
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
            margin-bottom: 15px;
            font-size: 8px;
        }

        table thead {
            background: #f1f3f5;
        }

        table th {
            padding: 8px 5px;
            text-align: left;
            font-weight: bold;
            border-bottom: 2px solid #dee2e6;
            font-size: 8px;
        }

        table td {
            padding: 6px 5px;
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
            padding: 10px 5px;
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

        .badge-success { background: #d4edda; color: #155724; }
        .badge-warning { background: #fff3cd; color: #856404; }
        .badge-danger { background: #f8d7da; color: #721c24; }
        .badge-info { background: #d1ecf1; color: #0c5460; }
        .badge-primary { background: #cce5ff; color: #004085; }

        .text-success { color: #28a745; }
        .text-danger { color: #dc3545; }
        .text-warning { color: #ffc107; }
        .text-primary { color: #007bff; }
        .text-muted { color: #6c757d; }

        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .font-weight-bold { font-weight: bold; }

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
    <h2>{{ $company->getTranslation('name', app()->getLocale()) }}</h2>
    <div class="info-grid">
        <div class="info-row">
            <div class="info-label">{{ __('Description') }}:</div>
            <div class="info-value">{{ $company->getTranslation('description', app()->getLocale()) }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">{{ __('Status') }}:</div>
            <div class="info-value">
                <span class="badge badge-{{ $company->status == 'active' ? 'success' : 'danger' }}">
                    {{ ucfirst($company->status) }}
                </span>
            </div>
        </div>
        <div class="info-row">
            <div class="info-label">{{ __('Current Wallet Balance') }}:</div>
            <div class="info-value font-weight-bold text-primary">
                {{ number_format($summary['current_wallet_balance'], 2) }} {{ __('SAR') }}
            </div>
        </div>
    </div>
</div>

{{-- Summary Statistics --}}
<div class="summary-cards">
    <div class="summary-row">
        <div class="summary-card info">
            <h3>{{ __('Total Employees') }}</h3>
            <div class="value">{{ $summary['total_employees'] }}</div>
            <div class="subtitle">{{ __('Active Employees') }}</div>
        </div>
        <div class="summary-card warning">
            <h3>{{ __('Total Cost') }}</h3>
            <div class="value text-danger">{{ number_format($summary['total_cost'], 2) }}</div>
            <div class="subtitle">{{ __('SAR') }}</div>
        </div>
        <div class="summary-card primary">
            <h3>{{ __('Total Revenue') }}</h3>
            <div class="value text-primary">{{ number_format($summary['total_price'], 2) }}</div>
            <div class="subtitle">{{ __('SAR') }}</div>
        </div>
        <div class="summary-card success">
            <h3>{{ __('Total Profit') }}</h3>
            <div class="value text-success">{{ number_format($summary['total_profit'], 2) }}</div>
            <div class="subtitle">{{ number_format($summary['profit_margin'], 1) }}% {{ __('Margin') }}</div>
        </div>
    </div>
</div>

{{-- Additional Summary Info --}}
<div class="summary-cards">
    <div class="summary-row">
        <div class="summary-card">
            <h3>{{ __('Completed Stages') }}</h3>
            <div class="value">{{ $summary['total_stages_completed'] }}</div>
        </div>
        <div class="summary-card">
            <h3>{{ __('Total Wallet Charges') }}</h3>
            <div class="value">{{ number_format($summary['total_wallet_charges'], 2) }}</div>
            <div class="subtitle">{{ __('SAR') }}</div>
        </div>
        <div class="summary-card">
            <h3>{{ __('Wallet Transactions') }}</h3>
            <div class="value">{{ $walletTransactions->count() }}</div>
        </div>
        <div class="summary-card">
            <h3>{{ __('Payment Transactions') }}</h3>
            <div class="value">{{ $paymentTransactions->count() }}</div>
        </div>
    </div>
</div>

{{-- Wallet Transactions Section --}}
<div class="section">
    <div class="section-title">{{ __('Wallet Transactions (Company Payments)') }}</div>

    @if($walletTransactions->count() > 0)
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
            @foreach($walletTransactions as $transaction)
                @php
                    $cost = $transaction->employeeStage ? ($transaction->employeeStage->amount_cost ?? 0) : 0;
                    $profit = $transaction->amount - $cost;
                @endphp
                <tr>
                    <td><strong>#{{ $transaction->id }}</strong></td>
                    <td>{{ $transaction->completed_at ? $transaction->completed_at->format('Y-m-d H:i') : $transaction->created_at->format('Y-m-d H:i') }}</td>
                    <td>
                        @if($transaction->employeeStage && $transaction->employeeStage->employee)
                            {{ $transaction->employeeStage->employee->name }}
                        @else
                            <span class="text-muted">{{ __('Wallet Charge') }}</span>
                        @endif
                    </td>
                    <td>
                        @if($transaction->employeeStage && $transaction->employeeStage->stage)
                            <span class="badge badge-info">{{ $transaction->employeeStage->stage->name }}</span>
                        @else
                            —
                        @endif
                    </td>
                    <td class="text-primary font-weight-bold">{{ number_format($transaction->amount, 2) }}</td>
                    <td class="text-danger">
                        @if($cost > 0)
                            {{ number_format($cost, 2) }}
                        @else
                            —
                        @endif
                    </td>
                    <td class="text-success font-weight-bold">
                        @if($profit > 0)
                            {{ number_format($profit, 2) }}
                        @else
                            —
                        @endif
                    </td>
                    <td>
                            <span class="badge badge-{{ $transaction->status == 'completed' ? 'success' : 'warning' }}">
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
                    {{ number_format($walletTransactions->sum(function($t) {
                        return $t->employeeStage ? ($t->employeeStage->amount_cost ?? 0) : 0;
                    }), 2) }}
                </td>
                <td class="text-success">
                    {{ number_format($walletTransactions->sum(function($t) {
                        $cost = $t->employeeStage ? ($t->employeeStage->amount_cost ?? 0) : 0;
                        return $t->amount - $cost;
                    }), 2) }}
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

    @if($paymentTransactions->count() > 0)
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
            @foreach($paymentTransactions as $transaction)
                <tr>
                    <td><strong>#{{ $transaction->id }}</strong></td>
                    <td>{{ $transaction->processed_at ? $transaction->processed_at->format('Y-m-d H:i') : $transaction->created_at->format('Y-m-d H:i') }}</td>
                    <td>
                        @if($transaction->employeeStage && $transaction->employeeStage->employee)
                            {{ $transaction->employeeStage->employee->name }}
                        @else
                            —
                        @endif
                    </td>
                    <td>
                        @if($transaction->employeeStage && $transaction->employeeStage->stage)
                            <span class="badge badge-info">{{ $transaction->employeeStage->stage->name }}</span>
                        @else
                            —
                        @endif
                    </td>
                    <td>{{ $transaction->paymentAccount ? $transaction->paymentAccount->name : '—' }}</td>
                    <td class="text-danger font-weight-bold">{{ number_format($transaction->amount, 2) }}</td>
                    <td>{{ number_format($transaction->from_balance_before ?? 0, 2) }}</td>
                    <td>{{ number_format($transaction->from_balance_after ?? 0, 2) }}</td>
                    <td>
                            <span class="badge badge-{{ $transaction->status == 'completed' ? 'success' : 'warning' }}">
                                {{ ucfirst($transaction->status) }}
                            </span>
                    </td>
                </tr>
            @endforeach
            </tbody>
            <tfoot>
            <tr>
                <td colspan="5" class="text-right">{{ __('TOTAL COST') }}:</td>
                <td class="text-danger font-weight-bold">{{ number_format($paymentTransactions->sum('amount'), 2) }}</td>
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

    @if($employeeProfits->count() > 0)
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
            @foreach($employeeProfits as $item)
                @php
                    $margin = $item['total_price'] > 0 ? ($item['total_profit'] / $item['total_price']) * 100 : 0;
                @endphp
                <tr>
                    <td class="font-weight-bold">{{ $item['employee']->name }}</td>
                    <td class="text-center">
                        <span class="badge badge-info">{{ $item['completed_stages'] }}</span>
                    </td>
                    <td class="text-danger">{{ number_format($item['total_cost'], 2) }} SAR</td>
                    <td class="text-primary">{{ number_format($item['total_price'], 2) }} SAR</td>
                    <td class="text-success font-weight-bold">{{ number_format($item['total_profit'], 2) }} SAR</td>
                    <td class="text-center">
                            <span class="badge badge-{{ $margin >= 20 ? 'success' : ($margin >= 10 ? 'warning' : 'danger') }}">
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
                <td class="text-danger">{{ number_format($employeeProfits->sum('total_cost'), 2) }} SAR</td>
                <td class="text-primary">{{ number_format($employeeProfits->sum('total_price'), 2) }} SAR</td>
                <td class="text-success font-weight-bold">{{ number_format($employeeProfits->sum('total_profit'), 2) }} SAR</td>
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

{{-- All Employees List --}}
<div class="section">
    <div class="section-title">{{ __('All Employees') }}</div>

    @if($company->employees->count() > 0)
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
            @foreach($company->employees as $employee)
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
