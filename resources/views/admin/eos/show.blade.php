<x-dashboard.main-layout>
    <div class="container-fluid py-4 print-container">
        {{-- ======= Page Header ======= --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-bold text-primary">
                <i class="fa-solid fa-file"></i> {{ __('End of Service Details') }}
            </h3>
            <a href="{{ route('admins.eos.index') }}" class="btn btn-secondary print-hide">
                {{ __('Back') }}
            </a>
        </div>

        {{-- ======= Employee Information ======= --}}
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-primary text-white fw-bold">
                {{ __('Employee Information') }}
            </div>
            <div class="card-body">
                <table class="table table-borderless table-sm">
                    <tr>
                        <th>{{ __('Employee Name') }}:</th>
                        <td>{{ $eo->employee->name ?? __('N/A') }}</td>
                        <th>{{ __('Employee ID') }}:</th>
                        <td>{{ $eo->employee->id ?? __('N/A') }}</td>
                    </tr>
                    <tr>
                        <th>{{ __('Joining Date') }}:</th>
                        <td>{{ $eo->joining_date }}</td>
                        <th>{{ __('Leaving Date') }}:</th>
                        <td>{{ $eo->leaving_date }}</td>
                    </tr>
                    <tr>
                        <th>{{ __('Years of Service') }}:</th>
                        <td>{{ number_format(\Carbon\Carbon::parse($eo->joining_date)->diffInYears(\Carbon\Carbon::parse($eo->leaving_date)), 2)  }} {{ __('years') }}</td>
                        <th>{{ __('Basic Salary') }}:</th>
                        <td>AED {{ number_format($eo->basic_salary, 2) }}</td>
                    </tr>
                    <tr>
                        <th>{{ __('Gross Salary') }}:</th>
                        <td>AED {{ number_format($eo->gross_salary, 2) }}</td>
                        <th>{{ __('Annual Leave Balance') }}:</th>
                        <td>{{ $eo->annual_leave_balance }} {{ __('days') }}</td>
                    </tr>
                </table>
            </div>
        </div>

        {{-- ======= Calculation Breakdown ======= --}}
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-success text-white fw-bold">
                {{ __('Calculation Breakdown') }}
            </div>
            <div class="card-body">
                <table class="table table-bordered text-center align-middle table-striped">
                    <thead class="table-light">
                    <tr>
                        <th>{{ __('Category') }}</th>
                        <th>{{ __('Details') }}</th>
                        <th>{{ __('Amount (AED)') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    {{-- Additions --}}
                    <tr>
                        <td rowspan="3" class="fw-bold">{{ __('Additions') }}</td>
                        <td>{{ __('Incentive') }}</td>
                        <td>{{ number_format($eo->incentive, 2) }}</td>
                    </tr>
                    <tr>
                        <td>{{ __('Rewards') }}</td>
                        <td>{{ number_format($eo->rewards, 2) }}</td>
                    </tr>
                    <tr>
                        <td>{{ __('Other Additions') }}</td>
                        <td>{{ number_format($eo->other_additions, 2) }}</td>
                    </tr>

                    <tr class="table-light fw-bold">
                        <td colspan="2">{{ __('Total Additions') }}</td>
                        <td>{{ number_format($eo->incentive + $eo->rewards + $eo->other_additions, 2) }}</td>
                    </tr>

                    {{-- Deductions --}}
                    <tr>
                        <td rowspan="5" class="fw-bold">{{ __('Deductions') }}</td>
                        <td>{{ __('Cash Advance') }}</td>
                        <td>{{ number_format($eo->cash_advance, 2) }}</td>
                    </tr>
                    <tr>
                        <td>{{ __('Petty Cash') }}</td>
                        <td>{{ number_format($eo->petty_cash, 2) }}</td>
                    </tr>
                    <tr>
                        <td>{{ __('Fines') }}</td>
                        <td>{{ number_format($eo->fines, 2) }}</td>
                    </tr>
                    <tr>
                        <td>{{ __('Compensation Notice') }}</td>
                        <td>{{ number_format($eo->compensation_notice, 2) }}</td>
                    </tr>
                    <tr>
                        <td>{{ __('Other Deductions') }}</td>
                        <td>{{ number_format($eo->other_deductions, 2) }}</td>
                    </tr>

                    <tr class="table-light fw-bold">
                        <td colspan="2">{{ __('Total Deductions') }}</td>
                        <td>{{ number_format($eo->cash_advance + $eo->petty_cash + $eo->fines + $eo->compensation_notice + $eo->other_deductions, 2) }}</td>
                    </tr>

                    {{-- Leave Pay --}}
                    <tr>
                        <td colspan="2" class="fw-bold">
                            {{ __('Leave Pay') }} ({{ $eo->annual_leave_balance }} {{ __('days') }} × {{ __('Basic Salary ÷ 30') }})
                        </td>
                        <td>{{ number_format($eo->annual_leave_balance * ($eo->basic_salary / 30), 2) }}</td>
                    </tr>

                    {{-- EOS Entitlement --}}
                    <tr>
                        <td colspan="2" class="fw-bold">{{ __('EOS Entitlement') }}</td>
                        <td>
                            @php
                                $years = \Carbon\Carbon::parse($eo->joining_date)->diffInYears(\Carbon\Carbon::parse($eo->leaving_date));
                                if ($years < 1) $eoAmount = 0;
                                elseif ($years < 5) $eoAmount = $eo->basic_salary * 21 / 30 * $years;
                                else $eoAmount = $eo->basic_salary * 30 / 30 * $years;
                            @endphp
                            {{ number_format($eoAmount, 2) }}
                        </td>
                    </tr>

                    {{-- Net Pay --}}
                    <tr class="table-success fw-bold">
                        <td colspan="2">{{ __('Final Net Pay') }}</td>
                        <td><strong>AED {{ number_format($eo->net_pay, 2) }}</strong></td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>

        {{-- ======= Formula Explanation ======= --}}
        <div class="card shadow-sm print-hide">
            <div class="card-header bg-info text-white fw-bold">
                {{ __('EOS Calculation Formula') }} ({{ __('معادلة حساب نهاية الخدمة') }})
            </div>
            <div class="card-body">
                <ul class="mb-0">
                    <li><strong>{{ __('Years of Service') }}:</strong> <code>{{ __('Leaving Date - Joining Date') }}</code></li>
                    <li><strong>{{ __('EOS Entitlement') }}:</strong>
                        <ul>
                            <li>{{ __('Less than 1 year → No EOS') }}</li>
                            <li>{{ __('1–5 years → Basic Salary × (21 ÷ 30) × Years') }}</li>
                            <li>{{ __('Over 5 years → Basic Salary × (30 ÷ 30) × Years') }}</li>
                        </ul>
                    </li>
                    <li><strong>{{ __('Leave Pay') }}:</strong> <code>{{ __('Annual Leave Balance × (Basic Salary ÷ 30)') }}</code></li>
                    <li><strong>{{ __('Net Pay') }}:</strong> <code>{{ __('EOS + Additions + Leave Pay - Deductions') }}</code></li>
                </ul>
            </div>
        </div>

        {{-- ======= Print Button ======= --}}
        <div class="mt-4 text-end print-hide">
            <button onclick="window.print()" class="btn btn-outline-primary">
                <i class="fa-solid fa-print"></i> {{ __('Print Report') }}
            </button>
        </div>
    </div>
</x-dashboard.main-layout>

{{-- ======= Print Styles ======= --}}
<style>
    @media print {
        body {
            background: white !important;
        }

        .print-hide {
            display: none !important;
        }

        .print-container {
            margin: 0 auto;
            padding: 20px;
            width: 210mm;
            min-height: 297mm;
            font-size: 14px;
            color: #000;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid #999 !important;
            padding: 6px 8px !important;
        }

        .card, .shadow-sm {
            box-shadow: none !important;
            border: none !important;
        }

        .card-header {
            background: #007bff !important;
            color: #fff !important;
            font-weight: bold;
            text-align: center;
            font-size: 16px;
        }

        .table-success td {
            background-color: #d4edda !important;
        }

        h3 {
            text-align: center;
            margin-bottom: 15px;
            font-weight: bold;
        }
    }
</style>
