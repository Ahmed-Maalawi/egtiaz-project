<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>{{ __('Employee Report') }} - {{ $employee->name }}</title>
    <style>
        /* mPDF compatible CSS for PDF */
        body {
            font-family: 'XB Riyaz', 'aealarabiya', 'Traditional Arabic', sans-serif;
            font-size: 11px;
            line-height: 1.4;
            direction: rtl;
            text-align: right;
            margin: 0;
            padding: 15px;
            color: #333;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #2c5aa0;
            padding-bottom: 12px;
        }

        .header h1 {
            font-size: 18px;
            margin: 0 0 5px 0;
            color: #2c5aa0;
        }

        .header h2 {
            font-size: 14px;
            margin: 0;
            color: #666;
        }

        .section {
            margin-bottom: 18px;
            page-break-inside: avoid;
        }

        .section-title {
            background-color: #f8f9fa;
            border-right: 4px solid #2c5aa0;
            padding: 6px 10px;
            margin-bottom: 10px;
            font-weight: bold;
            font-size: 13px;
            color: #2c5aa0;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
            font-size: 10px;
        }

        .info-table td {
            padding: 6px 8px;
            border: 1px solid #dee2e6;
            vertical-align: top;
        }

        .info-table .label {
            width: 30%;
            background-color: #f8f9fa;
            font-weight: bold;
            color: #555;
        }

        .info-table .value {
            width: 70%;
        }

        .stages-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
            font-size: 9px;
        }

        .stages-table th {
            background-color: #2c5aa0;
            color: white;
            padding: 6px 4px;
            text-align: right;
            font-weight: bold;
            border: 1px solid #1e3d6d;
        }

        .stages-table td {
            padding: 4px;
            border: 1px solid #dee2e6;
            text-align: right;
        }

        .payments-table {
            width: 100%;
            border-collapse: collapse;
            margin: 8px 0;
            font-size: 8px;
            background-color: #f8f9fa;
        }

        .payments-table th {
            background-color: #495057;
            color: white;
            padding: 4px 3px;
            text-align: right;
            font-weight: bold;
        }

        .payments-table td {
            padding: 3px;
            border: 1px solid #dee2e6;
        }

        .stage-details {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 3px;
            padding: 8px;
            margin: 6px 0;
            font-size: 9px;
        }

        .badge {
            display: inline-block;
            padding: 1px 4px;
            border-radius: 2px;
            font-size: 8px;
            font-weight: bold;
            margin: 1px;
        }

        .badge-success {
            background-color: #28a745;
            color: white;
        }

        .badge-warning {
            background-color: #ffc107;
            color: #212529;
        }

        .badge-danger {
            background-color: #dc3545;
            color: white;
        }

        .badge-info {
            background-color: #17a2b8;
            color: white;
        }

        .badge-secondary {
            background-color: #6c757d;
            color: white;
        }

        .badge-primary {
            background-color: #2c5aa0;
            color: white;
        }

        .financial-summary {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 4px;
            padding: 10px;
            margin: 12px 0;
        }

        .financial-row {
            display: table;
            width: 100%;
            margin-bottom: 4px;
        }

        .financial-label {
            display: table-cell;
            width: 50%;
            font-weight: bold;
        }

        .financial-value {
            display: table-cell;
            width: 50%;
            text-align: left;
            font-weight: bold;
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

        .text-info {
            color: #17a2b8;
        }

        .footer {
            text-align: center;
            margin-top: 25px;
            padding-top: 12px;
            border-top: 1px solid #dee2e6;
            color: #666;
            font-size: 9px;
        }

        .page-break {
            page-break-before: always;
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

        .notes {
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 3px;
            padding: 6px;
            margin: 6px 0;
            font-size: 9px;
        }

        .stage-header {
            background-color: #e9ecef;
            padding: 4px 8px;
            margin: 8px 0 4px 0;
            border-radius: 3px;
            font-weight: bold;
            font-size: 10px;
        }

        .payment-status-paid {
            background-color: #d4edda;
            color: #155724;
        }

        .payment-status-pending {
            background-color: #fff3cd;
            color: #856404;
        }

        .payment-status-failed {
            background-color: #f8d7da;
            color: #721c24;
        }
    </style>
</head>
<body>
<!-- Header -->
<div class="header">
    <h1 class="arabic-text">تقرير مفصل للموظف</h1>
    <h1 class="english-text">Detailed Employee Report</h1>
    <h2>{{ $employee->name }}</h2>
    <p class="english-text">Generated on: {{ now()->format('d M Y, h:i A') }}</p>
</div>

<!-- Personal Information -->
<div class="section">
    <div class="section-title arabic-text">المعلومات الشخصية / Personal Information</div>
    <table class="info-table">
        <tr>
            <td class="label arabic-text">الاسم الكامل / Full Name</td>
            <td class="value arabic-text">{{ $employee->name }}</td>
        </tr>
        <tr>
            <td class="label">Email</td>
            <td class="value">{{ $employee->email ?? __('N/A') }}</td>
        </tr>
        <tr>
            <td class="label">Phone</td>
            <td class="value">{{ $employee->phone ?? __('N/A') }}</td>
        </tr>
        <tr>
            <td class="label arabic-text">الجنس / Gender</td>
            <td class="value">{{ $employee->gender }}</td>
        </tr>
        <tr>
            <td class="label arabic-text">العنوان / Address</td>
            <td class="value arabic-text">{{ $employee->address ?? __('N/A') }}</td>
        </tr>
        <tr>
            <td class="label arabic-text">رقم الجواز / Passport Number</td>
            <td class="value">{{ $employee->passport_number ?? __('N/A') }}</td>
        </tr>
        <tr>
            <td class="label arabic-text">انتهاء الجواز / Passport Expiry</td>
            <td class="value">
                @if ($employee->expired_date)
                    {{ \Carbon\Carbon::parse($employee->expired_date)->format('d M, Y') }}
                    @if ($employee->expired_date->isPast())
                        <span class="badge badge-danger">منتهي الصلاحية / Expired</span>
                    @elseif ($employee->expired_date->diffInDays(now()) <= 30)
                        <span class="badge badge-warning">ينتهي قريباً / Expiring Soon</span>
                    @else
                        <span class="badge badge-success">ساري / Valid</span>
                    @endif
                @else
                    {{ __('N/A') }}
                @endif
            </td>
        </tr>
        <tr>
            <td class="label arabic-text">تاريخ الانضمام / Joined Date</td>
            <td class="value">{{ \Carbon\Carbon::parse($employee->created_at)->format('d M, Y') }}</td>
        </tr>
        <tr>
            <td class="label arabic-text">الحالة / Status</td>
            <td class="value">
                @if($employee->status == 'active')
                    <span class="badge badge-success">نشط / Active</span>
                @else
                    <span class="badge badge-danger">غير نشط / Inactive</span>
                @endif
            </td>
        </tr>
    </table>
</div>

<!-- Employment Information -->
<div class="section">
    <div class="section-title arabic-text">معلومات التوظيف / Employment Information</div>
    <table class="info-table">
        <tr>
            <td class="label arabic-text">الشركة / Company</td>
            <td class="value arabic-text">{{ $employee->company->name ?? __('N/A') }}</td>
        </tr>
        <tr>
            <td class="label arabic-text">نوع الإقامة / Iqama Type</td>
            <td class="value arabic-text">{{ $employee->iqamaType->name ?? __('N/A') }}</td>
        </tr>
        <tr>
            <td class="label arabic-text">إجمالي الملفات / Total Files</td>
            <td class="value">
                <span class="badge badge-info">{{ $employee->files->count() }}</span>
                @if($employee->files->count() > 0)
                    <small class="text-muted">({{ $employee->getTotalFilesSize() }})</small>
                @endif
            </td>
        </tr>
        <tr>
            <td class="label arabic-text">إجمالي المراحل / Total Stages</td>
            <td class="value">
                <span class="badge badge-primary">{{ $employee->employeeStages->count() }}</span>
                <small class="text-muted">
                    {{ $employee->completedStages()->count() }} مكتمل / completed
                </small>
            </td>
        </tr>
        <tr>
            <td class="label arabic-text">المرحلة الحالية / Current Stage</td>
            <td class="value arabic-text">
                @if($employee->upcomingStage)
                    <span class="font-weight-bold">{{ $employee->upcomingStage->stage->name ?? __('N/A') }}</span>
                    <span class="badge badge-warning">قيد الانتظار / Pending</span>
                @elseif($employee->checkAllPapersCompleted())
                    <span class="badge badge-success">جميع المراحل مكتملة / All Stages Completed</span>
                @else
                    <span class="badge badge-secondary">لا توجد مرحلة نشطة / No Active Stage</span>
                @endif
            </td>
        </tr>
    </table>
</div>

<!-- Financial Summary -->
<div class="section">
    <div class="section-title arabic-text">ملخص مالي / Financial Summary</div>
    <div class="financial-summary">
        <div class="financial-row">
            <span class="financial-label arabic-text">إجمالي التكلفة / Total Cost:</span>
            <span class="financial-value text-danger">{{ number_format($employee->total_cost, 2) . ' ' . __('SAR') }}</span>
        </div>
        <div class="financial-row">
            <span class="financial-label arabic-text">إجمالي السعر / Total Price:</span>
            <span class="financial-value text-success">{{ number_format($employee->total_price, 2) . ' ' . __('SAR') }}</span>
        </div>
        <div class="financial-row">
            <span class="financial-label arabic-text">إجمالي الربح / Total Profit:</span>
            <span class="financial-value {{ $employee->total_profit >= 0 ? 'text-success' : 'text-danger' }}">
                    {{ number_format($employee->total_profit, 2) . ' ' . __('SAR') }}
                </span>
        </div>
        <div class="financial-row">
            <span class="financial-label arabic-text">إجمالي المدفوع / Total Paid:</span>
            <span class="financial-value text-info">{{ number_format($employee->total_paid, 2) . ' ' . __('SAR') }}</span>
        </div>
        <div class="financial-row">
            <span class="financial-label arabic-text">المستحق / Total Due:</span>
            <span class="financial-value text-warning">{{ number_format($employee->total_due, 2) }} SAR</span>
        </div>
    </div>
</div>

<!-- Detailed Stages Information -->
@if($employee->employeeStages->count() > 0)
    <div class="section">
        <div class="section-title arabic-text">تفاصيل المراحل والمدفوعات / Stages & Payments Details</div>

        @foreach($employee->employeeStages->sortBy('stage.order') as $employeeStage)
            <div class="stage-details">
                <!-- Stage Header -->
                <div class="stage-header arabic-text">
                    {{ $employeeStage->stage->name ?? __('N/A') }}
                    <span class="badge {{ $employeeStage->status == 'completed' ? 'badge-success' : 'badge-warning' }}">
                            {{ $employeeStage->status == 'completed' ? 'مكتمل / Completed' : 'قيد الانتظار / Pending' }}
                        </span>
                </div>

                <!-- Stage Basic Information -->
                <table class="info-table">
                    <tr>
                        <td class="label arabic-text">تاريخ البدء / Start Date</td>
                        <td class="value">
                            @if($employeeStage->created_at)
                                {{ \Carbon\Carbon::parse($employeeStage->created_at)->format('d M, Y') }}
                            @else
                                {{ __('N/A') }}
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td class="label arabic-text">تاريخ الإكمال / Completion Date</td>
                        <td class="value">
                            @if($employeeStage->completed_at)
                                {{ \Carbon\Carbon::parse($employeeStage->completed_at)->format('d M, Y') }}
                            @else
                                {{ __('N/A') }}
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td class="label arabic-text">مدة التنفيذ / Duration</td>
                        <td class="value">
                            @if($employeeStage->completed_at && $employeeStage->created_at)
                                {{ round($employeeStage->created_at->diffInDays($employeeStage->completed_at)) }} أيام / days
                            @else
                                {{ __('N/A') }}
                            @endif
                        </td>
                    </tr>
                </table>

                <!-- Stage Financial Information -->
                <table class="info-table">
                    <tr>
                        <td class="label arabic-text">سعر المرحلة / Stage Price</td>
                        <td class="value text-success">{{ number_format($employeeStage->amount_paid, 2) . ' ' . __('SAR')}}</td>
                    </tr>
                    <tr>
                        <td class="label arabic-text">تكلفة المرحلة / Stage Cost</td>
                        <td class="value text-danger">{{ number_format($employeeStage->amount_cost, 2) . ' ' . __('SAR')}}</td>
                    </tr>
                    <tr>
                        <td class="label arabic-text">ربح المرحلة / Stage Profit</td>
                        <td class="value {{ $employeeStage->profit >= 0 ? 'text-success' : 'text-danger' }}">
                            {{ number_format($employeeStage->profit, 2) . ' ' . __('SAR')}}
{{--                            @if($employeeStage->amount_paid && $employeeStage->amount_cost)--}}
{{--                                <small>({{ number_format(($employeeStage->profit / $employeeStage->amount_paid) * 100, 1) }}%)</small>--}}
{{--                            @endif--}}
                        </td>
                    </tr>
                    @if($employeeStage->doneBy)
                        <tr>
                            <td class="label arabic-text">دفع بواسطة / Payed By</td>
                            <td class="value">
                                {{ $employeeStage->doneBy->name }}
                            </td>
                        </tr>
                    @endif
                </table>

                <!-- Payments for this Stage -->
                @if($employeeStage->payments && $employeeStage->payments->count() > 0)
                    <div style="margin-top: 8px;">
                        <strong class="arabic-text">المدفوعات / Payments:</strong>
                        <table class="payments-table">
                            <thead>
                            <tr>
                                <th class="arabic-text">المبلغ / Amount</th>
                                <th class="arabic-text">طريقة الدفع / Method</th>
                                <th class="arabic-text">الحالة / Status</th>
                                <th class="arabic-text">تاريخ الدفع / Payment Date</th>
                                <th class="arabic-text">رقم المرجع / Reference</th>
                                <th class="arabic-text">الملاحظات / Notes</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($employeeStage->payments as $payment)
                                <tr class="payment-status-{{ $payment->status }}">
                                    <td>{{ number_format($payment->amount, 2) . ' ' . __('SAR')}}</td>
                                    <td>{{ $payment->payment_method ?? 'N/A' }}</td>
                                    <td>
                                        @if($payment->status == 'paid')
                                            <span class="badge badge-success">مدفوع / Paid</span>
                                        @elseif($payment->status == 'pending')
                                            <span class="badge badge-warning">قيد الانتظار / Pending</span>
                                        @else
                                            <span class="badge badge-danger">فاشل / Failed</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($payment->paid_at)
                                            {{ \Carbon\Carbon::parse($payment->paid_at)->format('d M, Y') }}
                                        @else
                                            {{ __('N/A') }}
                                        @endif
                                    </td>
                                    <td>{{ $payment->reference_number ?? 'N/A' }}</td>
                                    <td>{{ $payment->notes ?? 'N/A' }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                            <tfoot>
                            <tr>
                                <td colspan="6" class="text-center" style="background-color: #e9ecef;">
                                    <strong class="arabic-text">
                                        إجمالي مدفوعات المرحلة / Stage Total Paid:
                                        {{ number_format($employeeStage->payments->where('status', 'paid')->sum('amount'), 2) }} SAR
                                    </strong>
                                </td>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                @else
                    <div class="notes arabic-text">
                        لا توجد مدفوعات مسجلة لهذه المرحلة / No payments recorded for this stage
                    </div>
                @endif

                <!-- Stage Files -->
                @if($employeeStage->files && $employeeStage->files->count() > 0)
                    <div style="margin-top: 8px;">
                        <strong class="arabic-text">ملفات المرحلة / Stage Files:</strong>
                        <div class="arabic-text">
                            @foreach($employeeStage->files as $file)
                                <span class="badge badge-secondary">{{ basename($file->path) }}</span>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Stage Notes -->
                @if($employeeStage->notes)
                    <div style="margin-top: 8px;">
                        <strong class="arabic-text">ملاحظات المرحلة / Stage Notes:</strong>
                        <div class="notes arabic-text">{{ $employeeStage->notes }}</div>
                    </div>
                @endif
            </div>
        @endforeach
    </div>
@endif

<!-- Payments for this Stage -->
@php
    $paymentData = $employeeStage->payment_data ?? null;
    $hasPayment = $paymentData && $paymentData['amount'] > 0;
@endphp

@if($hasPayment)
    <div style="margin-top: 8px;">
        <strong class="arabic-text">المدفوعات / Payments:</strong>
        <table class="payments-table">
            <thead>
            <tr>
                <th class="arabic-text">المبلغ / Amount</th>
                <th class="arabic-text">طريقة الدفع / Method</th>
                <th class="arabic-text">الحالة / Status</th>
                <th class="arabic-text">تاريخ الدفع / Payment Date</th>
                <th class="arabic-text">رقم المرجع / Reference</th>
                <th class="arabic-text">الملاحظات / Notes</th>
            </tr>
            </thead>
            <tbody>
            <tr class="payment-status-{{ $paymentData['status'] }}">
                <td>{{ number_format($paymentData['amount'], 2) }} SAR</td>
                <td>{{ $paymentData['payment_method'] ?? 'N/A' }}</td>
                <td>
                    @if($paymentData['status'] == 'completed' || $paymentData['status'] == 'paid')
                        <span class="badge badge-success">مدفوع / Paid</span>
                    @elseif($paymentData['status'] == 'pending')
                        <span class="badge badge-warning">قيد الانتظار / Pending</span>
                    @else
                        <span class="badge badge-danger">فاشل / Failed</span>
                    @endif
                </td>
                <td>
                    @if($paymentData['paid_at'])
                        {{ \Carbon\Carbon::parse($paymentData['paid_at'])->format('d M, Y') }}
                    @else
                        {{ __('N/A') }}
                    @endif
                </td>
                <td>{{ $paymentData['reference_number'] ?? 'N/A' }}</td>
                <td>{{ $paymentData['notes'] ?? 'N/A' }}</td>
            </tr>
            </tbody>
        </table>
    </div>
@else
    <div class="notes arabic-text">
        لا توجد مدفوعات مسجلة لهذه المرحلة / No payments recorded for this stage
    </div>
@endif

<!-- Overall Payment Summary -->
<div class="section">
    <div class="section-title arabic-text">ملخص المدفوعات الإجمالي / Overall Payment Summary</div>
    <table class="info-table">
        <tr>
            <td class="label arabic-text">إجمالي المبلغ المستحق / Total Amount Due</td>
            <td class="value text-warning">{{ number_format($employee->total_price, 2) }} SAR</td>
        </tr>
        <tr>
            <td class="label arabic-text">إجمالي المبلغ المدفوع / Total Amount Paid</td>
            <td class="value text-success">{{ number_format($employee->total_paid, 2) }} SAR</td>
        </tr>
        <tr>
            <td class="label arabic-text">المبلغ المتبقي / Remaining Balance</td>
            <td class="value text-danger">{{ number_format($employee->total_due, 2) }} SAR</td>
        </tr>
        <tr>
            <td class="label arabic-text">نسبة السداد / Payment Percentage</td>
            <td class="value">
                @if($employee->total_price > 0)
                    {{ number_format(($employee->total_paid / $employee->total_price) * 100, 1) }}%
                @else
                    0%
                @endif
            </td>
        </tr>
    </table>
</div>

<!-- Footer -->
<div class="footer">
    <p class="arabic-text">شكراً لاستخدامكم نظامنا</p>
    <p class="english-text">Thank you for using our system</p>
    <p>Report generated by {{ config('app.name') }} on {{ now()->format('d M Y, h:i A') }}</p>
</div>
</body>
</html>
