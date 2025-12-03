<x-dashboard.main-layout>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" />

    <div class="container-fluid py-4">

        {{-- Filter Form --}}
        <form method="GET" action="{{ route('admins.reports.wallet-transactions.report') }}" class="row g-3 mb-4">
            <div class="col-md-3">
                <label for="user_id" class="form-label">{{ __('User') }}</label>
                <select name="user_id" id="user_id" class="form-control select2-filter">
                    <option value="">{{ __('All Users') }}</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                            {{ $user->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3">
                <label for="company_id" class="form-label">{{ __('Company') }}</label>
                <select name="company_id" id="company_id" class="form-control select2-filter">
                    <option value="">{{ __('All Companies') }}</option>
                    @foreach($companies as $company)
                        <option value="{{ $company->id }}" {{ request('company_id') == $company->id ? 'selected' : '' }}>
                            {{ $company->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-2">
                <label for="status" class="form-label">{{ __('Status') }}</label>
                <select name="status" id="status" class="form-control">
                    <option value="">{{ __('All Statuses') }}</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>{{ __('Pending') }}
                    </option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>
                        {{ __('Completed') }}</option>
                    <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>{{ __('Failed') }}
                    </option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>
                        {{ __('Cancelled') }}</option>
                </select>
            </div>

            <div class="col-md-2">
                <label for="from_date" class="form-label">{{ __('From Date') }}</label>
                <input type="date" name="from_date" id="from_date" class="form-control"
                       value="{{ request('from_date') }}">
            </div>

            <div class="col-md-2">
                <label for="to_date" class="form-label">{{ __('To Date') }}</label>
                <input type="date" name="to_date" id="to_date" class="form-control" value="{{ request('to_date') }}">
            </div>

            <div class="col-12 text-end">
                <button type="submit" class="btn btn-primary">{{ __('Filter') }}</button>
                <a href="{{ route('admins.reports.wallet-transactions.report') }}"
                   class="btn btn-secondary">{{ __('Reset') }}</a>
            </div>
        </form>

        {{-- Summary Cards --}}
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card bg-info text-white">
                    <div class="card-body">
                        <h6 class="mb-0">{{ __('Total Amount') }}</h6>
                        <h3 class="mb-0">{{ number_format($totalAmount, 2) }} SAR</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <h6 class="mb-0">{{ __('Total Credit (Wallet Charges)') }}</h6>
                        <h3 class="mb-0">{{ number_format($totalCredit, 2) }} SAR</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-danger text-white">
                    <div class="card-body">
                        <h6 class="mb-0">{{ __('Total Debit (Payments)') }}</h6>
                        <h3 class="mb-0">{{ number_format($totalDebit, 2) }} SAR</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-warning text-white">
                    <div class="card-body">
                        <h6 class="mb-0">{{ __('Total Companies') }}</h6>
                        <h3 class="mb-0">{{ $companies->count() }}</h3>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tabs --}}
        <ul class="nav nav-tabs mb-3" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" data-toggle="tab" href="#all-transactions">
                    <i class="fas fa-list"></i> {{ __('All Transactions') }}
                    <span class="badge badge-primary">{{ count($transactions) }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#credit-transactions">
                    <i class="fas fa-arrow-up"></i> {{ __('Wallet Credit') }}
                    <span class="badge badge-success">{{ count($creditTransactions) }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#debit-transactions">
                    <i class="fas fa-arrow-down"></i> {{ __('Wallet Debit') }}
                    <span class="badge badge-danger">{{ count($debitTransactions) }}</span>
                </a>
            </li>
        </ul>

        {{-- Tab Content --}}
        <div class="tab-content">
            {{-- All Transactions Tab --}}
            <div class="tab-pane fade show active" id="all-transactions">
                @include('admin.reports.partials.wallet-transactions-table', [
                    'transactions' => $transactions,
                    'title' => __('All Wallet Transactions'),
                    'tableId' => 'allTransactionsTable',
                    'showCompany' => true
                ])
            </div>

            {{-- Credit Transactions Tab --}}
            <div class="tab-pane fade" id="credit-transactions">
                @include('admin.reports.partials.wallet-transactions-table', [
                    'transactions' => $creditTransactions,
                    'title' => __('Wallet Credit Transactions (Charges)'),
                    'tableId' => 'creditTransactionsTable',
                    'showType' => false,
                    'showCompany' => true
                ])
            </div>

            {{-- Debit Transactions Tab --}}
            <div class="tab-pane fade" id="debit-transactions">
                @include('admin.reports.partials.wallet-transactions-table', [
                    'transactions' => $debitTransactions,
                    'title' => __('Wallet Debit Transactions (Payments)'),
                    'tableId' => 'debitTransactionsTable',
                    'showEmployee' => true,
                    'showCompany' => true
                ])
            </div>
        </div>
    </div>

    {{-- JS --}}
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap4.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

    <script>
        $(document).ready(function () {
            // Initialize Select2
            $('.select2-filter').select2({
                placeholder: function() {
                    return $(this).data('placeholder') || 'Select...';
                },
                allowClear: true
            });

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

            // Array to store DataTable instances
            var dataTables = {};

            // Function to get print customization
            function getPrintCustomization(tableId, title, amountColIndex, totalCols, showEmployee = false, showCompany = false) {
                return function (win) {
                    // Get the DataTable instance
                    var api = dataTables[tableId];

                    // Calculate totals for print
                    var totalAmount = api.column(amountColIndex).data().reduce(function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);

                    var totalRecords = api.rows().count();

                    // Status counts
                    var statusData = api.column(amountColIndex + 3).data().toArray();
                    var completedCount = statusData.filter(status => status.includes('completed') || status.includes('Completed')).length;
                    var pendingCount = statusData.filter(status => status.includes('pending') || status.includes('Pending')).length;
                    var failedCount = statusData.filter(status => status.includes('failed') || status.includes('Failed')).length;
                    var cancelledCount = statusData.filter(status => status.includes('cancelled') || status.includes('Cancelled')).length;

                    // Apply Cairo font
                    $(win.document.body)
                        .css('font-family', '"Cairo", sans-serif')
                        .css('font-size', '12pt')
                        .css('color', '#000')
                        .css('direction', 'ltr');

                    // Add custom header
                    $(win.document.body).prepend(`
                    <div style="text-align:center; margin-bottom:25px; font-family: 'Cairo', sans-serif;">
                        <h2 style="margin:0; font-family: 'Cairo', sans-serif; color: #007bff;">{{ config('app.name') }}</h2>
                        <p style="margin:0; font-family: 'Cairo', sans-serif; font-size: 14pt; font-weight: bold;">${title}</p>
                        <hr style="border-top:2px solid #007bff; width:80%; margin:10px auto;">
                        <p style="font-size:11pt; margin:5px 0;">{{ __("Generated on") }}: ${new Date().toLocaleDateString()}</p>
                        <div style="background: #f8f9fa; padding: 10px; border-radius: 5px; margin: 10px 0; font-size: 10pt;">
                            <div style="display: inline-block; margin: 0 15px;">
                                <strong>{{ __("Total Records") }}:</strong> ${totalRecords}
                            </div>
                            <div style="display: inline-block; margin: 0 15px;">
                                <strong>{{ __("Total Amount") }}:</strong> ${formatNumber(totalAmount)} SAR
                            </div>
                            <div style="display: inline-block; margin: 0 15px;">
                                <strong>{{ __("Completed") }}:</strong> ${completedCount}
                            </div>
                            <div style="display: inline-block; margin: 0 15px;">
                                <strong>{{ __("Pending") }}:</strong> ${pendingCount}
                            </div>
                            <div style="display: inline-block; margin: 0 15px;">
                                <strong>{{ __("Failed") }}:</strong> ${failedCount}
                            </div>
                            <div style="display: inline-block; margin: 0 15px;">
                                <strong>{{ __("Cancelled") }}:</strong> ${cancelledCount}
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

                    // Style table headers with bold text and distinctive borders
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
                    $(win.document.body).find('td:nth-child(' + (amountColIndex + 1) + ')')
                        .css({
                            'font-weight': 'bold',
                            'color': '#000'
                        });

                    // Add totals row at the bottom of the table
                    var tfoot = `
                    <tfoot>
                        <tr style="background-color: #f8f9fa; font-weight: bold; border-top: 3px double #007bff;">
                            <td colspan="${amountColIndex}" style="text-align: right; padding: 8px; border: 1px solid #ddd; font-family: 'Cairo', sans-serif;">
                                {{ __("TOTALS:") }}
                    </td>
                    <td style="padding: 8px; border: 1px solid #ddd; font-family: 'Cairo', sans-serif; font-weight: bold; color: #007bff;">
${formatNumber(totalAmount)} SAR
                            </td>
                            <td colspan="${totalCols - amountColIndex - 1}" style="text-align: center; padding: 8px; border: 1px solid #ddd; font-family: 'Cairo', sans-serif;">
                                {{ __("Summary") }}: {{ __("Total Records") }}: ${totalRecords} | {{ __("Total Amount") }}: ${formatNumber(totalAmount)} SAR
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
                            td:nth-child(${amountColIndex + 1}) {
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
                            tfoot td:nth-child(${amountColIndex + 1}) {
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
                };
            }

            // Initialize DataTable for a specific tab
            function initDataTableForTab(tabId, tableId, title, showEmployee = false, showCompany = false) {
                // Check if DataTable is already initialized
                if ($.fn.DataTable.isDataTable('#' + tableId)) {
                    return dataTables[tableId];
                }

                // Determine amount column index based on configuration
                var amountColIndex = 3; // Default (User, then Amount)

                if (showCompany && showEmployee) {
                    amountColIndex = 5; // User, Company, Employee, then Amount
                } else if (showCompany) {
                    amountColIndex = 4; // User, Company, then Amount
                } else if (showEmployee) {
                    amountColIndex = 4; // User, Employee, then Amount
                }

                // Count total columns in the table
                var $table = $('#' + tableId);
                var totalCols = $table.find('thead th').length;

                // Initialize DataTable
                var table = $table.DataTable({
                    pageLength: 10,
                    scrollX: true,
                    dom: 'Blfrtip',
                    buttons: [
                        {
                            extend: 'print',
                            text: '<i class="fa fa-print"></i> {{ __("Print") }}',
                            className: 'btn btn-primary',
                            title: '',
                            footer: true,
                            customize: getPrintCustomization(tableId, title, amountColIndex, totalCols, showEmployee, showCompany)
                        },
                        {
                            extend: 'excelHtml5',
                            text: '<i class="fa fa-file-excel"></i> {{ __("Excel") }}',
                            className: 'btn btn-success',
                            title: title.replace(/[^a-z0-9]/gi, '_') + '_{{ now()->format("Y_m_d") }}',
                            message: 'Filtered Results',
                            footer: true,
                            customize: function (xlsx) {
                                var sheet = xlsx.xl.worksheets['sheet1.xml'];

                                // Get totals from DataTable
                                var api = dataTables[tableId];
                                var totalAmount = api.column(amountColIndex).data().reduce(function (a, b) {
                                    return intVal(a) + intVal(b);
                                }, 0);
                                var totalRecords = api.rows().count();

                                // Find the last row number
                                var rows = $('row', sheet);
                                var lastRowNum = rows.length;

                                // Add totals row
                                var totalsRow = '<row r="' + (lastRowNum + 1) + '">' +
                                    '<c r="A' + (lastRowNum + 1) + '" t="inlineStr"><is><t>{{ __("TOTALS") }}</t></is></c>' +
                                    '<c r="' + String.fromCharCode(65 + amountColIndex) + (lastRowNum + 1) + '"><v>' + totalAmount + '</v></c>' +
                                    '<c r="' + String.fromCharCode(65 + totalCols - 1) + (lastRowNum + 1) + '" t="inlineStr"><is><t>' + totalRecords + ' {{ __("records") }}</t></is></c>' +
                                    '</row>';

                                $('sheetData', sheet).append(totalsRow);
                            }
                        }
                    ],
                    columnDefs: [
                        {
                            className: "text-center",
                            targets: "_all"
                        },
                        {
                            targets: [amountColIndex],
                            render: function(data, type, row) {
                                if (type === 'display' || type === 'filter') {
                                    return formatNumber(data) + ' SAR';
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
                    footerCallback: function (row, data, start, end, display) {
                        var api = this.api();

                        // Calculate total amount
                        var amountTotal = api
                            .column(amountColIndex, { page: 'current' })
                            .data()
                            .reduce(function (a, b) {
                                return intVal(a) + intVal(b);
                            }, 0);

                        // Calculate total records on current page
                        var pageTotal = api.page.info().recordsDisplay;

                        // Update footer in the main table view
                        if ($(api.table().footer()).length === 0) {
                            $(api.table()).append('<tfoot><tr></tr></tfoot>');
                        }

                        var footerRow = $(api.table().footer()).find('tr');
                        footerRow.html(`
                        <td colspan="${amountColIndex}" class="text-end" style="font-family: 'Cairo', sans-serif;">
                            <strong>{{ __("Page Totals:") }}</strong>
                        </td>
                        <td class="text-center" style="font-family: 'Cairo', sans-serif; font-weight: bold; color: #007bff;">
                            <strong>${formatNumber(amountTotal)} SAR</strong>
                        </td>
                        <td colspan="${totalCols - amountColIndex - 1}" class="text-center" style="font-family: 'Cairo', sans-serif;">
                            <small class="text-muted">{{ __("Page Records") }}: ${pageTotal} | {{ __("Page Amount") }}: ${formatNumber(amountTotal)} SAR</small>
                        </td>
                    `);
                    },
                    drawCallback: function (settings) {
                        var api = this.api();

                        // Calculate global totals
                        var globalTotalAmount = api.column(amountColIndex).data().reduce(function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0);
                        var globalTotalRecords = api.rows().count();

                        var info = $(this).closest('.dataTables_wrapper').find('.dataTables_info');

                        // Remove any existing total info
                        var infoText = info.html();
                        if (infoText.includes('|')) {
                            info.html(infoText.split('|')[0]);
                        }

                        // Add global totals to the info text
                        info.html(info.html() +
                            ' <span class="text-primary">| {{ __("Total Amount") }}: <strong>' +
                            formatNumber(globalTotalAmount) + ' SAR</strong></span>' +
                            ' <span class="text-success">| {{ __("Total Records") }}: <strong>' +
                            globalTotalRecords + '</strong></span>');
                    }
                });

                // Store the DataTable instance
                dataTables[tableId] = table;

                return table;
            }

            // Function to initialize all DataTables
            function initializeAllDataTables() {
                // Initialize All Transactions table
                if ($('#allTransactionsTable').length) {
                    initDataTableForTab('all-transactions', 'allTransactionsTable', '{{ __("All Wallet Transactions") }}', false, true);
                }

                // Initialize Credit Transactions table
                if ($('#creditTransactionsTable').length) {
                    initDataTableForTab('credit-transactions', 'creditTransactionsTable', '{{ __("Wallet Credit Transactions (Charges)") }}', false, true);
                }

                // Initialize Debit Transactions table
                if ($('#debitTransactionsTable').length) {
                    initDataTableForTab('debit-transactions', 'debitTransactionsTable', '{{ __("Wallet Debit Transactions (Payments)") }}', true, true);
                }
            }

            // Initialize DataTables when the page loads
            initializeAllDataTables();

            // Re-initialize DataTables when switching tabs (optional)
            $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
                var target = $(e.target).attr("href");

                // Small delay to ensure tab content is visible
                setTimeout(function() {
                    if (target === '#all-transactions' && $('#allTransactionsTable').length) {
                        var table = dataTables['allTransactionsTable'];
                        if (table) {
                            table.columns.adjust().draw();
                        }
                    } else if (target === '#credit-transactions' && $('#creditTransactionsTable').length) {
                        var table = dataTables['creditTransactionsTable'];
                        if (table) {
                            table.columns.adjust().draw();
                        }
                    } else if (target === '#debit-transactions' && $('#debitTransactionsTable').length) {
                        var table = dataTables['debitTransactionsTable'];
                        if (table) {
                            table.columns.adjust().draw();
                        }
                    }
                }, 100);
            });
        });
    </script>

    <style>
        /* Add Cairo font import if not already present */
        @import url('https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700&display=swap');

        .table tfoot {
            background-color: #f8f9fa;
            font-weight: bold;
        }

        .table tfoot td {
            border-top: 2px solid #007bff !important;
            text-align: center;
            vertical-align: middle;
            padding: 10px !important;
            font-family: 'Cairo', sans-serif;
        }

        .table tbody td {
            font-family: 'Cairo', sans-serif;
        }

        .table thead th {
            font-family: 'Cairo', sans-serif;
        }

        .dataTables_length, .dataTables_filter {
            margin-bottom: 1rem;
            font-family: 'Cairo', sans-serif;
        }

        .dt-buttons {
            margin-bottom: 1rem;
        }

        .dt-buttons .btn {
            margin-right: 5px;
            font-family: 'Cairo', sans-serif;
        }

        body {
            font-family: 'Cairo', sans-serif;
        }

        /* Print-specific styles for footer */
        @media print {
            .table tfoot {
                background-color: #f8f9fa !important;
                -webkit-print-color-adjust: exact;
            }

            .table tfoot td {
                border-top: 3px double #007bff !important;
                font-weight: bold !important;
            }
        }

        /* Tab styling */
        .nav-tabs .nav-link {
            font-family: 'Cairo', sans-serif;
        }

        /* Card styling */
        .card h5, .card h6 {
            font-family: 'Cairo', sans-serif;
        }

        /* Summary cards styling */
        .card.bg-info h3,
        .card.bg-success h3,
        .card.bg-danger h3,
        .card.bg-warning h3 {
            font-family: 'Cairo', sans-serif;
        }

        /* Filter form styling */
        .form-label {
            font-family: 'Cairo', sans-serif;
            font-weight: 600;
        }

        /* Select2 styling */
        .select2-container .select2-selection--single {
            height: 38px;
            font-family: 'Cairo', sans-serif;
        }
    </style>
</x-dashboard.main-layout>
