@php
    $local = app()->getLocale();
@endphp
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap4.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<x-dashboard.main-layout>


    <div class="container-fluid py-4">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <span>{{ __('Transactions Management') }}</span>
                <div>
                    {{-- Active Filters Badge --}}
                    @php
                        $activeFilterCount = 0;
                        $filterFields = ['transaction_id', 'status', 'type', 'payment_account_id', 'amount_min', 'amount_max', 'date_range'];
                        foreach ($filterFields as $field) {
                            if (request()->filled($field)) {
                                $activeFilterCount++;
                            }
                        }
                    @endphp

                    @if($activeFilterCount > 0)
                        <span class="badge badge-warning me-2" id="activeFiltersCount">
                        {{ $activeFilterCount }} {{ __('Active Filters') }}
                    </span>
                    @endif

                    <button class="btn btn-light btn-sm" type="button" data-toggle="modal" data-target="#filterModal">
                        <i class="fa fa-filter"></i> {{ __('Filters') }}
                    </button>
                </div>
            </div>

            {{-- Active Filters Display --}}
            @if($activeFilterCount > 0)
                <div class="card-body py-2 border-bottom bg-light">
                    <div class="active-filters">
                        <small class="text-muted">{{ __('Active Filters') }}:</small>

                        @php
                            // Helper function to remove query parameters
                            function removeQueryParams($paramsToRemove) {
                                $currentQuery = request()->query();
                                if (is_array($paramsToRemove)) {
                                    foreach ($paramsToRemove as $param) {
                                        unset($currentQuery[$param]);
                                    }
                                } else {
                                    unset($currentQuery[$paramsToRemove]);
                                }
                                return url()->current() . (!empty($currentQuery) ? '?' . http_build_query($currentQuery) : '');
                            }
                        @endphp

                        @if(request('transaction_id'))
                            <span class="badge badge-primary me-1 mb-1 text-white">
                                {{ __('ID') }}: {{ request('transaction_id') }}
                                <a href="{{ removeQueryParams('transaction_id') }}" class="text-white ms-1" style="text-decoration: none;">×</a>
                            </span>
                        @endif

                        @if(request('status'))
                            <span class="badge badge-info me-1 mb-1 text-white">
                                {{ __('Status') }}: {{ ucfirst(request('status')) }}
                                <a href="{{ removeQueryParams('status') }}" class="text-white ms-1" style="text-decoration: none;">×</a>
                            </span>
                        @endif

                        @if(request('type'))
                            <span class="badge badge-info me-1 mb-1 text-white">
                                {{ __('Type') }}: {{ ucfirst(request('type')) }}
                                <a href="{{ removeQueryParams('type') }}" class="text-white ms-1" style="text-decoration: none;">×</a>
                            </span>
                        @endif

                        @if(request('payment_account_id'))
                             @php
                                $accountName = $accounts->where('id', request('payment_account_id'))->first()->name ?? 'N/A';
                            @endphp
                            <span class="badge badge-secondary me-1 mb-1 text-white">
                                {{ __('Account') }}: {{ $accountName }}
                                <a href="{{ removeQueryParams('payment_account_id') }}" class="text-white ms-1" style="text-decoration: none;">×</a>
                            </span>
                        @endif

                        @if(request('amount_min') || request('amount_max'))
                            <span class="badge badge-success me-1 mb-1 text-white">
                                {{ __('Amount') }}: {{ request('amount_min', 0) }} - {{ request('amount_max', '∞') }}
                                <a href="{{ removeQueryParams(['amount_min', 'amount_max']) }}" class="text-white ms-1" style="text-decoration: none;">×</a>
                            </span>
                        @endif

                        @if(request('date_range'))
                            <span class="badge badge-secondary me-1 mb-1 text-white">
                                {{ __('Date') }}: {{ request('date_range') }}
                                <a href="{{ removeQueryParams('date_range') }}" class="text-white ms-1" style="text-decoration: none;">×</a>
                            </span>
                        @endif

                        <a href="{{ url()->current() }}" class="btn btn-outline-danger btn-sm ms-2">
                            <i class="fa fa-times"></i> {{ __('Clear All') }}
                        </a>
                    </div>
                </div>
            @endif

            <div class="card-body" style="overflow-x: auto;">

            <table id="transactionsTable" class="table table-hover align-middle text-center w-100">
                    <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>{{ __('Transaction ID') }}</th>
{{--                        <th>{{ __('From Account') }}</th>--}}
{{--                        <th>{{ __('To Wallet') }}</th>--}}
                        <th>{{ __('Type') }}</th>
                        <th>{{ __('User') }}</th>
                        <th>{{ __('Amount') }}</th>
                        <th>{{ __('Balance Before') }}</th>
                        <th>{{ __('Balance After') }}</th>
                        <th>{{ __('Status') }}</th>
                        <th>{{ __('Processed At') }}</th>
                        <th>{{ __('Created By') }}</th>
                        <th>{{ __('Created At') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($transactions as $transaction)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $transaction->transaction_id }}</td>
{{--                            <td>{{ $transaction->fromPaymentAccount->getTranslation('name', $local) ?? 'N/A' }}</td>--}}
{{--                            <td>{{ $transaction->toWallet->company->getTranslation('name', app()->getLocale()) ?? 'N/A' }}</td>--}}
                            <td>
                                <span class="text-white badge badge-info">
                                    {{ $transaction->transactionable_type_name  }}
                                </span>
                            </td>
                            <td>
                                @if($transaction->transactionable_type_name == 'Employee Stage')
                                    {{ $transaction?->transactionable->employee->name ?? 'N/A' }}
                                @elseif($transaction->transactionable_type_name == 'Salary')
                                    {{ $transaction?->transactionable->user->name ?? 'N/A' }}
                                @else
                                    {{ __('N/A') }}
                                @endif
                            </td>
                            <td>{{ number_format($transaction->amount ?? 0, 2) }}</td>
                            <td>{{ number_format($transaction->from_balance_before ?? 0, 2) }}</td>
                            <td>{{ number_format($transaction->from_balance_after  ?? 0, 2) }}</td>
                            <td>
                                @php
                                    $statusColors = [
                                        'pending' => 'warning',
                                        'completed' => 'success',
                                        'failed' => 'danger',
                                        'refund' => 'secondary',
                                        'canceled' => 'dark'
                                    ];
                                @endphp
                                <span class="text-white badge badge-{{ $statusColors[$transaction->status] ?? 'light' }}">
                                        {{ ucfirst($transaction->status) }}
                                    </span>
                            </td>
                            <td>{{ $transaction->processed_at?->format('Y-m-d H:i') ?? 'N/A' }}</td>
                            <td>{{ $transaction?->createdBy->name ?? 'System' }}</td>
                            <td>{{ $transaction->created_at->format('Y-m-d H:i') }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                 <div class="mt-3">
                    {{ $transactions->withQueryString()->links() }}
                </div>
            </div>
        </div>
    </div>

    {{-- Filter Modal --}}
    <div class="modal fade" id="filterModal" tabindex="-1" role="dialog" aria-labelledby="filterModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="filterModalLabel">
                        <i class="fa fa-filter"></i> {{ __('Filter Transactions') }}
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="filterForm" method="GET" action="{{ url()->current() }}">
                        <div class="row">
                            {{-- Transaction ID --}}
                            <div class="col-md-6 mb-3">
                                <label for="transaction_id" class="form-label">{{ __('Transaction ID') }}</label>
                                <input type="text" id="transaction_id" name="transaction_id" class="form-control"
                                       placeholder="{{ __('Enter ID') }}" value="{{ request('transaction_id') }}">
                            </div>

                            {{-- Payment Account --}}
                            <div class="col-md-6 mb-3">
                                <label for="payment_account_id" class="form-label">{{ __('Payment Account') }}</label>
                                <select id="payment_account_id" name="payment_account_id" class="form-control select2-filter">
                                    <option value="">{{ __('All Accounts') }}</option>
                                    @foreach($accounts as $account)
                                        <option value="{{ $account->id }}" {{ request('payment_account_id') == $account->id ? 'selected' : '' }}>
                                            {{ $account->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Status --}}
                            <div class="col-md-6 mb-3">
                                <label for="status" class="form-label">{{ __('Status') }}</label>
                                <select id="status" name="status" class="form-control">
                                    <option value="">{{ __('All Statuses') }}</option>
                                    @foreach($statuses as $status)
                                        <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>
                                            {{ ucfirst($status) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Type --}}
                            <div class="col-md-6 mb-3">
                                <label for="type" class="form-label">{{ __('Type') }}</label>
                                <select id="type" name="type" class="form-control">
                                    <option value="">{{ __('All Types') }}</option>
                                    @foreach($types as $type)
                                        <option value="{{ $type }}" {{ request('type') == $type ? 'selected' : '' }}>
                                            {{ ucfirst($type) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                             {{-- Date Range Filter --}}
                            <div class="col-md-6 mb-3">
                                <label for="date_range" class="form-label">{{ __('Date Range') }}</label>
                                <input type="text" id="date_range" name="date_range" class="form-control date-range-picker"
                                       placeholder="{{ __('Select date range') }}"
                                       value="{{ request('date_range') }}">
                            </div>

                            {{-- Amount Range --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label">{{ __('Amount Range') }}</label>
                                <div class="row">
                                    <div class="col">
                                        <input type="number" id="amount_min" name="amount_min" class="form-control"
                                               placeholder="{{ __('Min') }}" value="{{ request('amount_min') }}"
                                               min="0" step="0.01">
                                    </div>
                                    <div class="col">
                                        <input type="number" id="amount_max" name="amount_max" class="form-control"
                                               placeholder="{{ __('Max') }}" value="{{ request('amount_max') }}"
                                               min="0" step="0.01">
                                    </div>
                                </div>
                            </div>

                            {{-- Quick Filters --}}
                            <div class="col-12 mb-3">
                                <label class="form-label">{{ __('Quick Date Filters') }}</label>
                                <div class="d-flex flex-wrap">
                                    <button type="button" class="btn btn-outline-primary btn-sm mr-2 mb-2 quick-filter" data-days="0">
                                        {{ __('Today') }}
                                    </button>
                                    <button type="button" class="btn btn-outline-primary btn-sm mr-2 mb-2 quick-filter" data-days="7">
                                        {{ __('Last 7 Days') }}
                                    </button>
                                    <button type="button" class="btn btn-outline-primary btn-sm mr-2 mb-2 quick-filter" data-days="30">
                                        {{ __('Last 30 Days') }}
                                    </button>
                                    <button type="button" class="btn btn-outline-primary btn-sm mr-2 mb-2 quick-filter" data-days="90">
                                        {{ __('Last 3 Months') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fa fa-times"></i> {{ __('Cancel') }}
                    </button>
                    <a href="{{ url()->current() }}" class="btn btn-warning">
                        <i class="fa fa-refresh"></i> {{ __('Reset All') }}
                    </a>
                    <button type="submit" form="filterForm" class="btn btn-primary">
                        <i class="fa fa-search"></i> {{ __('Apply Filters') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</x-dashboard.main-layout>

{{-- JS - Only load what's not already in the layout --}}
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://npmcdn.com/flatpickr/dist/l10n/ar.js"></script>

<script>
    $(document).ready(function () {
        // Helper function to format numbers
        function formatNumber(number) {
            if (number === null || number === undefined || isNaN(number)) {
                return '0.00';
            }
            // If it's already a formatted string with commas, remove them first
            if (typeof number === 'string') {
                number = number.replace(/[^0-9.-]/g, '');
            }
            return parseFloat(number).toLocaleString('en-US', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
        }

        // Helper function to parse values
        function intVal(i) {
            return typeof i === 'string' ?
                i.replace(/[^\d.-]/g, '') * 1 :
                typeof i === 'number' ?
                    i : 0;
        }

        // Initialize Select2 for modal dropdowns
        $('#payment_account_id').select2({
            placeholder: "{{ __('Select Account') }}",
            allowClear: true,
            dropdownParent: $('#filterModal')
        });

        // Initialize Date Range Picker
        flatpickr("#date_range", {
            mode: "range",
            dateFormat: "Y-m-d",
            locale: "{{ app()->getLocale() }}",
            allowInput: true
        });

        // Quick filter buttons
        $('.quick-filter').on('click', function() {
            var days = $(this).data('days');
            var endDate = new Date();
            var startDate = new Date();
            startDate.setDate(startDate.getDate() - days);

            var dateRange = startDate.toISOString().split('T')[0] + ' to ' + endDate.toISOString().split('T')[0];
            $('#date_range').val(dateRange);
        });

        // Initialize DataTable with search enabled
        var table = $('#transactionsTable').DataTable({
            paging: false, // Disable DataTables pagination since we use Laravel's
            info: false,   // Disable info display
            searching: true, // Enable client-side search
            scrollX: true,
            dom: 'Bfrtip',
            buttons: [
                {
                    extend: 'print',
                    text: '<i class="fa fa-print"></i> {{ __("Print") }}',
                    className: 'btn btn-primary btn-sm',
                    title: '',
                    footer: true,
                    customize: function (win) {
                        // Calculate totals for print
                        var totalTransactions = {{ $transactions->total() }};
                        var totalAmount = {{ $transactions->sum('amount') }};
                        var completedTransactions = {{ $transactions->where('status', 'completed')->count() }};
                        var pendingTransactions = {{ $transactions->where('status', 'pending')->count() }};
                        var failedTransactions = {{ $transactions->where('status', 'failed')->count() }};

                        // Apply styling
                        $(win.document.body)
                            .css('font-family', '"Cairo", sans-serif')
                            .css('font-size', '12pt')
                            .css('color', '#000')
                            .css('direction', 'ltr');

                        // Add custom header
                        $(win.document.body).prepend(`
                            <div style="text-align:center; margin-bottom:25px; font-family: 'Cairo', sans-serif;">
                                <h2 style="margin:0; font-family: 'Cairo', sans-serif; color: #007bff;">{{ config('app.name') }}</h2>
                                <p style="margin:0; font-family: 'Cairo', sans-serif; font-size: 14pt; font-weight: bold;">{{ __("Transactions Report") }}</p>
                                <hr style="border-top:2px solid #007bff; width:80%; margin:10px auto;">
                                <p style="font-size:11pt; margin:5px 0;">{{ __("Generated on") }}: ${new Date().toLocaleDateString()}</p>
                                <div style="background: #f8f9fa; padding: 10px; border-radius: 5px; margin: 10px 0; font-size: 10pt;">
                                    <div style="display: inline-block; margin: 0 15px;">
                                        <strong>{{ __("Total Transactions") }}:</strong> ${totalTransactions}
                                    </div>
                                    <div style="display: inline-block; margin: 0 15px;">
                                        <strong>{{ __("Total Amount") }}:</strong> ${formatNumber(totalAmount)}
                                    </div>
                                    <div style="display: inline-block; margin: 0 15px;">
                                        <strong>{{ __("Completed") }}:</strong> ${completedTransactions}
                                    </div>
                                    <div style="display: inline-block; margin: 0 15px;">
                                        <strong>{{ __("Pending") }}:</strong> ${pendingTransactions}
                                    </div>
                                    <div style="display: inline-block; margin: 0 15px;">
                                        <strong>{{ __("Failed") }}:</strong> ${failedTransactions}
                                    </div>
                                </div>
                            </div>
                        `);

                        // Style the table for printing
                        $(win.document.body).find('table')
                            .addClass('table table-bordered')
                            .css({
                                'border-collapse': 'collapse',
                                'font-family': '"Cairo", sans-serif',
                                'font-size': '10pt',
                                'width': '100%',
                                'text-align': 'center',
                                'border': '2px solid #000'
                            });

                        // Style table headers
                        $(win.document.body).find('thead th')
                            .css({
                                'background-color': 'transparent',
                                'color': '#000',
                                'font-family': '"Cairo", sans-serif',
                                'font-weight': 'bold',
                                'font-size': '11pt',
                                'padding': '8px',
                                'border': '2px solid #000',
                                'border-bottom': '3px solid #007bff'
                            });

                        // Style table cells
                        $(win.document.body).find('td')
                            .css({
                                'font-family': '"Cairo", sans-serif',
                                'padding': '6px',
                                'border': '1px solid #ddd'
                            });

                        // Style the Amount column to make it stand out
                        $(win.document.body).find('td:nth-child(5), td:nth-child(6), td:nth-child(7)')
                            .css({
                                'font-weight': 'bold',
                                'color': '#000'
                            });

                        // Add totals row at the bottom of the table
                        var tfoot = `
                            <tfoot>
                                <tr style="background-color: #f8f9fa; font-weight: bold; border-top: 3px double #007bff;">
                                    <td colspan="4" style="text-align: right; padding: 8px; border: 1px solid #ddd; font-family: 'Cairo', sans-serif;">
                                        {{ __("TOTALS:") }}
                        </td>
                        <td style="padding: 8px; border: 1px solid #ddd; font-family: 'Cairo', sans-serif; font-weight: bold; color: #007bff;">
${formatNumber(totalAmount)}
                                    </td>
                                    <td style="padding: 8px; border: 1px solid #ddd; font-family: 'Cairo', sans-serif; font-weight: bold;">
                                        -
                                    </td>
                                    <td style="padding: 8px; border: 1px solid #ddd; font-family: 'Cairo', sans-serif; font-weight: bold;">
                                        -
                                    </td>
                                    <td colspan="4" style="text-align: center; padding: 8px; border: 1px solid #ddd; font-family: 'Cairo', sans-serif;">
                                        {{ __("Summary") }}: {{ __("Total Transactions") }}: ${totalTransactions} | {{ __("Total Amount") }}: ${formatNumber(totalAmount)}
                                    </td>
                                </tr>
                            </tfoot>
                        `;

                        $(win.document.body).find('table').append(tfoot);

                        // Style the footer
                        $(win.document.body).find('tfoot td')
                            .css({
                                'font-family': '"Cairo", sans-serif',
                                'padding': '8px',
                                'border': '1px solid #ddd',
                                'font-weight': 'bold'
                            });

                        // Add CSS for print
                        var printStyle = `
                            <style type="text/css" media="print">
                                @media print {
                                    body {
                                        font-family: "Cairo", sans-serif !important;
                                        color: #000 !important;
                                        direction: ltr !important;
                                    }
                                    thead th {
                                        background-color: transparent !important;
                                        color: #000000 !important;
                                        font-weight: bold !important;
                                        border: 2px solid #000000 !important;
                                        border-bottom: 3px solid #007bff !important;
                                    }
                                    table {
                                        border-collapse: collapse !important;
                                        width: 100% !important;
                                        border: 2px solid #000 !important;
                                    }
                                    th, td {
                                        border: 1px solid #ddd !important;
                                    }
                                    .table-bordered {
                                        border: 2px solid #000 !important;
                                    }
                                    td:nth-child(5), td:nth-child(6), td:nth-child(7) {
                                        font-weight: bold !important;
                                        color: #000 !important;
                                    }
                                    tfoot tr:first-child {
                                        background-color: #f8f9fa !important;
                                        border-top: 3px double #007bff !important;
                                    }
                                    tfoot td {
                                        font-weight: bold !important;
                                    }
                                    tfoot td:nth-child(5) {
                                        color: #007bff !important;
                                    }
                                }
                            </style>
                        `;
                        $(win.document.head).append(printStyle);

                        // Add footer with generation info
                        $(win.document.body).append(`
                            <div style="text-align:center; margin-top:30px; font-size:10pt; font-family: 'Cairo', sans-serif;">
                                <hr style="border-top:1px solid #ccc; margin:20px 0;">
                                <p>{{ __("Generated by") }}: {{ Auth::user()->name ?? 'System' }}</p>
                                <p style="color: #666; font-size: 9pt;">{{ __("Printed on") }}: ${new Date().toLocaleString()}</p>
                            </div>
                        `);

                        // Remove DataTables default elements from print
                        $(win.document.body).find('.dataTables_length, .dataTables_filter, .dataTables_info, .dataTables_paginate, .dt-buttons')
                            .remove();
                    }
                },
                {
                    extend: 'excelHtml5',
                    text: '<i class="fa fa-file-excel"></i> {{ __("Export Excel") }}',
                    className: 'btn btn-success btn-sm',
                    title: 'Transactions_Report_{{ now()->format("Y_m_d") }}',
                    messageTop: function() {
                        var totalTransactions = {{ $transactions->total() }};
                        var totalAmount = {{ $transactions->sum('amount') }};
                        var completedCount = {{ $transactions->where('status', 'completed')->count() }};
                        var pendingCount = {{ $transactions->where('status', 'pending')->count() }};

                        return 'Report Generated: ' + new Date().toLocaleDateString() + '\n' +
                            'Total Transactions: ' + totalTransactions + '\n' +
                            'Total Amount: ' + formatNumber(totalAmount) + '\n' +
                            'Completed: ' + completedCount + ' | Pending: ' + pendingCount;
                    },
                    footer: true,
                    exportOptions: {
                        columns: ':visible',
                        format: {
                            body: function(data, row, column, node) {
                                return data.replace(/<[^>]*>/g, '').trim();
                            }
                        }
                    },
                    customize: function (xlsx) {
                        var sheet = xlsx.xl.worksheets['sheet1.xml'];
                        var rows = $('row', sheet);

                        var totalTransactions = {{ $transactions->total() }};
                        var totalAmount = {{ $transactions->sum('amount') }};

                        var lastRowNum = rows.length + 1;

                        // Add empty row
                        $('sheetData', sheet).append('<row r="' + lastRowNum + '"></row>');
                        lastRowNum++;

                        // Add totals row
                        // Columns: # | Trans ID | Type | User | Amount | Bal Before | Bal After | Status | Processed | Created By | Created At
                        var totalsRow = '<row r="' + lastRowNum + '">' +
                            '<c r="A' + lastRowNum + '" t="inlineStr"><is><t><b>TOTALS</b></t></is></c>' +
                            '<c r="B' + lastRowNum + '"></c>' +
                            '<c r="C' + lastRowNum + '"></c>' +
                            '<c r="D' + lastRowNum + '"></c>' +
                            '<c r="E' + lastRowNum + '"><v>' + totalAmount + '</v></c>' +
                            '<c r="F' + lastRowNum + '"></c>' +
                            '<c r="G' + lastRowNum + '"></c>' +
                            '<c r="H' + lastRowNum + '"></c>' +
                            '<c r="I' + lastRowNum + '"></c>' +
                            '<c r="J' + lastRowNum + '"></c>' +
                            '<c r="K' + lastRowNum + '" t="inlineStr"><is><t>' + totalTransactions + ' transactions</t></is></c>' +
                            '</row>';

                        $('sheetData', sheet).append(totalsRow);

                        // Set column widths
                        var colWidths = [
                            { wch: 5 },   // #
                            { wch: 20 },  // Trans ID
                            { wch: 15 },  // Type
                            { wch: 20 },  // User
                            { wch: 15 },  // Amount
                            { wch: 15 },  // Bal Before
                            { wch: 15 },  // Bal After
                            { wch: 12 },  // Status
                            { wch: 18 },  // Processed
                            { wch: 20 },  // Created By
                            { wch: 18 }   // Created At
                        ];

                        var cols = $('cols', sheet);
                        if (cols.length === 0) {
                            cols = $('<cols/>');
                            $('sheetData', sheet).before(cols);
                        }

                        cols.empty();
                        for (var i = 0; i < colWidths.length; i++) {
                            cols.append('<col min="' + (i + 1) + '" max="' + (i + 1) +
                                '" width="' + colWidths[i].wch + '" customWidth="1"/>');
                        }
                    }
                }
            ],
            columnDefs: [
                {
                    className: "text-center",
                    targets: "_all"
                },
                // Add render functions for numeric columns (Amount, Balance Before, Balance After)
                {
                    targets: [4, 5, 6], // Amount (5), Balance Before (6), Balance After (7) columns
                    render: function(data, type, row) {
                        if (type === 'display' || type === 'filter') {
                            return formatNumber(data);
                        }
                        return data;
                    }
                }
            ],
            language: {
                search: "{{ __('Search:') }}",
                lengthMenu: "{{ __('Show _MENU_ records per page') }}",
                zeroRecords: "{{ __('No matching records found') }}",
                info: "{{ __('Showing _START_ to _END_ of _TOTAL_ records') }}",
                infoEmpty: "{{ __('No records available') }}",
                infoFiltered: "{{ __('(filtered from _MAX_ total records)') }}",
                paginate: {
                    previous: "{{ __('Previous') }}",
                    next: "{{ __('Next') }}"
                }
            },
            // Add footer callback for DataTables display
            footerCallback: function (row, data, start, end, display) {
                var api = this.api();

                // Calculate total amount (column 4, index 4)
                var amountTotal = api
                    .column(4, { page: 'current' })
                    .data()
                    .reduce(function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);

                // Calculate balance before total (column 5, index 5)
                var balanceBeforeTotal = api
                    .column(5, { page: 'current' })
                    .data()
                    .reduce(function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);

                // Calculate balance after total (column 6, index 6)
                var balanceAfterTotal = api
                    .column(6, { page: 'current' })
                    .data()
                    .reduce(function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);

                // Calculate total records on current page
                var pageTotal = api.rows({ page: 'current' }).count();

                // Count statuses on current page
                var statusData = api.column(7).data().toArray();
                var completedCount = statusData.filter(status => status.includes('completed') || status.includes('Completed')).length;
                var pendingCount = statusData.filter(status => status.includes('pending') || status.includes('Pending')).length;
                var failedCount = statusData.filter(status => status.includes('failed') || status.includes('Failed')).length;

                // Update footer in the main table view
                if ($(api.table().footer()).length === 0) {
                    $(api.table()).append('<tfoot><tr></tr></tfoot>');
                }

                var footerRow = $(api.table().footer()).find('tr');
                footerRow.html(`
                    <td colspan="4" class="text-end" style="font-family: 'Cairo', sans-serif;">
                        <strong>{{ __("Page Totals:") }}</strong>
                    </td>
                    <td class="text-center" style="font-family: 'Cairo', sans-serif; font-weight: bold; color: #007bff;">
                        <strong>${formatNumber(amountTotal)}</strong>
                    </td>
                    <td class="text-center" style="font-family: 'Cairo', sans-serif; font-weight: bold;">
                        <strong>${formatNumber(balanceBeforeTotal)}</strong>
                    </td>
                    <td class="text-center" style="font-family: 'Cairo', sans-serif; font-weight: bold;">
                        <strong>${formatNumber(balanceAfterTotal)}</strong>
                    </td>
                    <td colspan="4" class="text-center" style="font-family: 'Cairo', sans-serif;">
                        <small class="text-muted">{{ __("Page Records") }}: ${pageTotal} | {{ __("Completed") }}: ${completedCount} | {{ __("Pending") }}: ${pendingCount} | {{ __("Failed") }}: ${failedCount}</small>
                    </td>
                `);
            },
            drawCallback: function (settings) {
                // Calculate global totals from original data
                var globalTotalAmount = {{ $transactions->sum('amount') }};
                var globalTotalRecords = {{ $transactions->total() }};
                var globalCompletedRecords = {{ $transactions->where('status', 'completed')->count() }};
                var globalPendingRecords = {{ $transactions->where('status', 'pending')->count() }};
                var globalFailedRecords = {{ $transactions->where('status', 'failed')->count() }};

                // Find or create the info element
                var infoElement = $(this).closest('.dataTables_wrapper').find('.dataTables_info');
                if (infoElement.length === 0) {
                    // Create info element if it doesn't exist
                    infoElement = $('<div class="dataTables_info"></div>');
                    $(this).closest('.dataTables_wrapper').find('.dataTables_filter').after(infoElement);
                }

                // Add global totals to the info text
                infoElement.html(
                    '<span class="text-primary">{{ __("Total Amount") }}: <strong>' + formatNumber(globalTotalAmount) + '</strong></span>' +
                    ' | <span class="text-success">{{ __("Completed") }}: <strong>' + globalCompletedRecords + '</strong></span>' +
                    ' | <span class="text-warning">{{ __("Pending") }}: <strong>' + globalPendingRecords + '</strong></span>' +
                    ' | <span class="text-danger">{{ __("Failed") }}: <strong>' + globalFailedRecords + '</strong></span>' +
                    ' | <span class="text-muted">{{ __("Total Records") }}: <strong>' + globalTotalRecords + '</strong></span>'
                );
            }
        });
    });
</script>

<style>
    /* Add Cairo font import */
    @import url('https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700&display=swap');

    #transactionsTable thead th {
        text-align: center;
        vertical-align: middle;
        font-family: 'Cairo', sans-serif;
        background-color: #007bff;
        color: #ffffff;
    }

    #transactionsTable tbody td {
        font-family: 'Cairo', sans-serif;
    }

    #transactionsTable tfoot {
        background-color: #f8f9fa;
        font-weight: bold;
    }

    #transactionsTable tfoot td {
        border-top: 2px solid #007bff !important;
        text-align: center;
        vertical-align: middle;
        padding: 10px !important;
        font-family: 'Cairo', sans-serif;
    }

    #transactionsTable tfoot td:nth-child(5) {
        color: #007bff;
        font-size: 1.1em;
    }

    #transactionsTable tbody td:nth-child(5),
    #transactionsTable tbody td:nth-child(6),
    #transactionsTable tbody td:nth-child(7) {
        font-weight: bold;
        color: #007bff;
    }

    .active-filters {
        padding: 10px;
        background: #f8f9fa;
        border-radius: 0.375rem;
        border: 1px solid #e9ecef;
    }

    .active-filters .badge {
        font-size: 0.75rem;
        padding: 0.35em 0.65em;
    }

    .select2-container {
        width: 100% !important;
    }

    .dataTables_wrapper .dataTables_filter {
        float: right;
        text-align: right;
        margin-bottom: 10px;
    }

    .dataTables_wrapper .dataTables_filter input {
        margin-left: 0.5em;
    }

    .dataTables_info {
        margin-top: 10px;
        font-family: 'Cairo', sans-serif;
    }

    /* Print-specific styles for footer */
    @media print {
        #transactionsTable tfoot {
            background-color: #f8f9fa !important;
            -webkit-print-color-adjust: exact;
        }

        #transactionsTable tfoot td {
            border-top: 3px double #007bff !important;
            font-weight: bold !important;
        }

        #transactionsTable tfoot td:nth-child(5) {
            color: #007bff !important;
        }

        #transactionsTable tbody td:nth-child(5),
        #transactionsTable tbody td:nth-child(6),
        #transactionsTable tbody td:nth-child(7) {
            font-weight: bold !important;
            color: #000 !important;
        }
    }
</style>
