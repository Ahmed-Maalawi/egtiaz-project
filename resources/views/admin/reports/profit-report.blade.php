<x-dashboard.main-layout>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css">


    <div class="container-fluid py-4">

        {{-- Filter Form --}}
        <form method="GET" action="{{ route('admins.reports.profit.report') }}" class="row g-3 mb-4">
            <div class="col-md-3">
                <label for="employee_id" class="form-label">{{ __('Employee') }}</label>
                <select name="employee_id" id="employee_id" class="form-select">
                    <option value="">{{ __('All Employees') }}</option>
                    @foreach($employees as $employee)
                        <option value="{{ $employee->id }}" {{ request('employee_id') == $employee->id ? 'selected' : '' }}>
                            {{ $employee->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3">
                <label for="company_id" class="form-label">{{ __('Company') }}</label>
                <select name="company_id" id="company_id" class="form-select from_date">
                    <option value="">{{ __('All Companies') }}</option>
                    @foreach($companies as $company)
                        <option value="{{ $company->id }}" {{ request('company_id') == $company->id ? 'selected' : '' }}>
                            {{ $company->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-2">
                <label for="from_date" class="form-label">{{ __('From Date') }}</label>
                <input type="date" name="from_date" id="from_date" class="form-control" value="{{ request('from_date') }}">
            </div>

            <div class="col-md-2">
                <label for="to_date" class="form-label">{{ __('To Date') }}</label>
                <input type="date" name="to_date" id="to_date" class="form-control" value="{{ request('to_date') }}">
            </div>

            <div class="col-12 text-end">
                <button type="submit" class="btn btn-primary">{{ __('Filter') }}</button>
                <a href="{{ route('admins.reports.profit.report') }}" class="btn btn-secondary">{{ __('Reset') }}</a>
            </div>
        </form>

        {{-- Table --}}
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <span>{{ __('Profit Report') }}</span>
                <span class="badge bg-success">{{ __('Total Profit') }}: {{ number_format($totalProfit, 2) }}</span>
            </div>

            <div class="card-body" style="overflow-x: auto;">
                <table id="profitTable" class="table table-hover align-middle text-center w-100">
                    <thead class="table-light">
                    <tr class="text-center">
                        <th>#</th>
                        <th>{{ __('Stage Name') }}</th>
                        <th>{{ __('Employee') }}</th>
                        <th>{{ __('Profit') }}</th>
                        <th>{{ __('Completed At') }}</th>
                        <th>{{ __('Done By') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($employeeStages as $stage)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $stage->stage->getTranslation('name', app()->getLocale()) ?? 'N/A' }}</td>
                            <td>{{ $stage->employee->name ?? 'N/A' }}</td>
                            <td>{{ number_format($stage->profit ?? 0, 2) }}</td>
                            <td>{{ $stage->completed_at?->format('Y-m-d H:i') ?? 'N/A' }}</td>
                            <td>{{ $stage->doneBy?->name ?? 'System' }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- JS --}}
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>

    <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>

    <script>
        $(function () {
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

            // Initialize DataTable
            var table = $('#profitTable').DataTable({
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
                        customize: function (win) {
                            // Calculate totals for print
                            var totalProfit = {{ $totalProfit }};
                            var totalRecords = {{ $employeeStages->count() }};
                            var uniqueEmployees = {{ $employeeStages->unique('employee_id')->count() }};
                            var uniqueStages = {{ $employeeStages->unique('stage_id')->count() }};

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
                                <p style="margin:0; font-family: 'Cairo', sans-serif; font-size: 14pt; font-weight: bold;">{{ __("Profit Report") }}</p>
                                <hr style="border-top:2px solid #007bff; width:80%; margin:10px auto;">
                                <p style="font-size:11pt; margin:5px 0;">{{ __("Generated on") }}: ${new Date().toLocaleDateString()}</p>
                                <div style="background: #f8f9fa; padding: 10px; border-radius: 5px; margin: 10px 0; font-size: 10pt;">
                                    <div style="display: inline-block; margin: 0 15px;">
                                        <strong>{{ __("Total Records") }}:</strong> ${totalRecords}
                                    </div>
                                    <div style="display: inline-block; margin: 0 15px;">
                                        <strong>{{ __("Total Profit") }}:</strong> ${formatNumber(totalProfit)}
                                    </div>
                                    <div style="display: inline-block; margin: 0 15px;">
                                        <strong>{{ __("Unique Employees") }}:</strong> ${uniqueEmployees}
                                    </div>
                                    <div style="display: inline-block; margin: 0 15px;">
                                        <strong>{{ __("Unique Stages") }}:</strong> ${uniqueStages}
                                    </div>
                                </div>
                                @if(request()->hasAny(['employee_id', 'company_id', 'from_date', 'to_date']))
                            <div style="background: #e9f7fe; padding: 8px; border-radius: 5px; margin: 10px 0; font-size: 9pt; text-align: left;">
                                <strong>{{ __("Applied Filters") }}:</strong><br>
                                    @if(request('employee_id'))
                            {{ __("Employee") }}: {{ $employees->where('id', request('employee_id'))->first()->name ?? 'N/A' }}<br>
                                    @endif
                            @if(request('company_id'))
                            {{ __("Company") }}: {{ $companies->where('id', request('company_id'))->first()->name ?? 'N/A' }}<br>
                                    @endif
                            @if(request('from_date'))
                            {{ __("From Date") }}: {{ request('from_date') }}<br>
                                    @endif
                            @if(request('to_date'))
                            {{ __("To Date") }}: {{ request('to_date') }}<br>
                                    @endif
                            </div>
@endif
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

                            // Style the Profit column to make it stand out
                            $(win.document.body).find('td:nth-child(4)')
                                .css({
                                    'font-weight': 'bold',
                                    'color': '#000'
                                });

                            // Add totals row at the bottom of the table
                            var tfoot = `
                            <tfoot>
                                <tr style="background-color: #f8f9fa; font-weight: bold; border-top: 3px double #007bff;">
                                    <td colspan="3" style="text-align: right; padding: 8px; border: 1px solid #ddd; font-family: 'Cairo', sans-serif;">
                                        {{ __("TOTALS:") }}
                            </td>
                            <td style="padding: 8px; border: 1px solid #ddd; font-family: 'Cairo', sans-serif; font-weight: bold; color: #007bff;">
${formatNumber(totalProfit)}
                                    </td>
                                    <td colspan="2" style="text-align: center; padding: 8px; border: 1px solid #ddd; font-family: 'Cairo', sans-serif;">
                                        {{ __("Summary") }}: {{ __("Total Records") }}: ${totalRecords} | {{ __("Total Profit") }}: ${formatNumber(totalProfit)}
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
                                    td:nth-child(4) {
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
                                    tfoot td:nth-child(4) {
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
                        text: '<i class="fa fa-file-excel"></i> {{ __("Excel") }}',
                        className: 'btn btn-success',
                        title: 'Profit_Report_{{ now()->format("Y_m_d") }}',
                        message: 'Filtered Results',
                        footer: true,
                        customize: function (xlsx) {
                            var sheet = xlsx.xl.worksheets['sheet1.xml'];

                            // Add totals to Excel export
                            var totalProfit = {{ $totalProfit }};
                            var totalRecords = {{ $employeeStages->count() }};

                            // Find the last row number
                            var rows = $('row', sheet);
                            var lastRowNum = rows.length;

                            // Add totals row
                            var totalsRow = '<row r="' + (lastRowNum + 1) + '">' +
                                '<c r="A' + (lastRowNum + 1) + '" t="inlineStr"><is><t>{{ __("TOTALS") }}</t></is></c>' +
                                '<c r="D' + (lastRowNum + 1) + '"><v>' + totalProfit + '</v></c>' +
                                '<c r="F' + (lastRowNum + 1) + '" t="inlineStr"><is><t>' + totalRecords + ' {{ __("records") }}</t></is></c>' +
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
                    // Add render function for profit column (index 3)
                    {
                        targets: [3], // Profit column (4th column, index 3)
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

                    // Calculate total profit (column 3, index 3)
                    var profitTotal = api
                        .column(3, { page: 'current' })
                        .data()
                        .reduce(function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0);

                    // Calculate total records on current page
                    var pageTotal = api.page.info().recordsDisplay;
                    var uniqueEmployees = new Set(api.column(2).data().toArray()).size;

                    // Update footer in the main table view
                    if ($(api.table().footer()).length === 0) {
                        $(api.table()).append('<tfoot><tr></tr></tfoot>');
                    }

                    var footerRow = $(api.table().footer()).find('tr');
                    footerRow.html(`
                    <td colspan="3" class="text-end" style="font-family: 'Cairo', sans-serif;">
                        <strong>{{ __("Page Totals:") }}</strong>
                    </td>
                    <td class="text-center" style="font-family: 'Cairo', sans-serif; font-weight: bold; color: #007bff;">
                        <strong>${formatNumber(profitTotal)}</strong>
                    </td>
                    <td colspan="2" class="text-center" style="font-family: 'Cairo', sans-serif;">
                        <small class="text-muted">{{ __("Page Records") }}: ${pageTotal} | {{ __("Page Profit") }}: ${formatNumber(profitTotal)} | {{ __("Unique Employees") }}: ${uniqueEmployees}</small>
                    </td>
                `);
                },
                drawCallback: function (settings) {
                    // Calculate global totals from original data
                    var globalTotalProfit = {{ $totalProfit }};
                    var globalTotalRecords = {{ $employeeStages->count() }};
                    var globalUniqueEmployees = {{ $employeeStages->unique('employee_id')->count() }};

                    var info = $(this).closest('.dataTables_wrapper').find('.dataTables_info');

                    // Remove any existing total info
                    var infoText = info.html();
                    if (infoText.includes('|')) {
                        info.html(infoText.split('|')[0]);
                    }

                    // Add global totals to the info text
                    info.html(info.html() +
                        ' <span class="text-primary">| {{ __("Total Profit") }}: <strong>' +
                        formatNumber(globalTotalProfit) + '</strong></span>' +
                        ' <span class="text-success">| {{ __("Total Records") }}: <strong>' +
                        globalTotalRecords + '</strong></span>' +
                        ' <span class="text-info">| {{ __("Unique Employees") }}: <strong>' +
                        globalUniqueEmployees + '</strong></span>');
                }
            });
        });
    </script>
</x-dashboard.main-layout>

<style>
    /* Add Cairo font import if not already present */
    @import url('https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700&display=swap');

    #profitTable thead th {
        text-align: center;
        vertical-align: middle;
        font-family: 'Cairo', sans-serif;
        background-color: #007bff;
        color: #ffffff;
    }

    #profitTable tbody td {
        font-family: 'Cairo', sans-serif;
    }

    #profitTable tfoot {
        background-color: #f8f9fa;
        font-weight: bold;
    }

    #profitTable tfoot td {
        border-top: 2px solid #007bff !important;
        text-align: center;
        vertical-align: middle;
        padding: 10px !important;
        font-family: 'Cairo', sans-serif;
    }

    #profitTable tfoot td:nth-child(4) {
        color: #007bff;
        font-size: 1.1em;
    }

    #profitTable tbody td:nth-child(4) {
        font-weight: bold;
        color: #007bff;
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
        #profitTable tfoot {
            background-color: #f8f9fa !important;
            -webkit-print-color-adjust: exact;
        }

        #profitTable tfoot td {
            border-top: 3px double #007bff !important;
            font-weight: bold !important;
        }

        #profitTable tfoot td:nth-child(4) {
            color: #007bff !important;
        }

        #profitTable tbody td:nth-child(4) {
            font-weight: bold !important;
            color: #000 !important;
        }
    }

    /* Filter form styling */
    .form-label {
        font-family: 'Cairo', sans-serif;
        font-weight: 600;
    }

    .form-select, .form-control {
        font-family: 'Cairo', sans-serif;
    }

    /* Card header styling */
    .card-header .badge {
        font-family: 'Cairo', sans-serif;
        font-size: 1rem;
        padding: 0.5em 1em;
    }
</style>
