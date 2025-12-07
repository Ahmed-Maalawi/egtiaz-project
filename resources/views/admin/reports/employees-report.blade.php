<x-dashboard.main-layout>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

    <div class="container-fluid py-4">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fa fa-users"></i> {{ __('Employees Report') }}</h5>
                <div class="d-flex align-items-center gap-2">
                    <label for="perPage" class="me-1 mb-0">{{ __('Show') }}</label>
                    <select id="perPage" class="form-select form-select-sm w-auto mx-2">
                        <option value="10" selected>10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>

                    <span class="ms-1 mx-2">{{ __('entries') }}</span>

                    <button class="btn btn-light btn-sm ms-3" data-bs-toggle="modal" data-bs-target="#filterModal">
                        <i class="fa fa-filter"></i> {{ __('Filters') }}
                    </button>
                </div>
            </div>


            <div class="card-body table-responsive">
                <table id="employeesTable" class="table table-bordered table-striped text-center align-middle">
                    <thead class="table-primary">
                    <tr>
                        <th>#</th>
                        <th>{{ __('Name') }}</th>
                        <th>{{ __('Company') }}</th>
                        <th>{{ __('Iqama Type') }}</th>
                        <th>{{ __('Upcoming Stage') }}</th>
                        <th>{{ __('Salary') }}</th>
                        <th>{{ __('Expired Date') }}</th>
                        <th>{{ __('Status') }}</th>
{{--                        <th>{{ __('Leaves') }}</th>--}}
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($employees as $employee)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $employee->name }}</td>
                            <td>{{ $employee->company?->name ?? '-' }}</td>
                            <td>{{ $employee->iqamaType?->name ?? '-' }}</td>
                            <td>{{ $employee->upcomingStage?->stage?->name ?? __('Completed') }}</td>
                            <td>{{ $employee->salary ?? __('N/A') }}</td>
                            <td>
                                @if($employee->expired_date)
                                    @if($employee->expired_date->isPast())
                                        <span class="text-white badge bg-danger">
                                                {{ $employee->expired_date->format('Y-m-d') }}
                                            </span>
                                    @else
                                        <span class="text-white badge bg-success">
                                                {{ $employee->expired_date->format('Y-m-d') }}
                                            </span>
                                    @endif
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                    <span class="text-white badge bg-{{ $employee->status == 'active' ? 'success' : 'secondary' }}">
                                        {{ ucfirst($employee->status) }}
                                    </span>
                            </td>
{{--                            <td>{{ $employee->leaves()->count() }}</td>--}}
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Filter Modal --}}
    <div class="modal fade" id="filterModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <form method="GET" action="{{ route('admins.reports.employees.report') }}" class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">{{ __('Filter Employees') }}</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body row g-3">
                    <div class="col-md-6">
                        <label>{{ __('Company') }}</label>
                        <select name="company_id" class="form-select">
                            <option value="">{{ __('All Companies') }}</option>
                            @foreach($companies as $company)
                                <option value="{{ $company->id }}" {{ request('company_id') == $company->id ? 'selected' : '' }}>
                                    {{ $company->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label>{{ __('Iqama Type') }}</label>
                        <select name="iqama_type_id" class="form-select">
                            <option value="">{{ __('All Types') }}</option>
                            @foreach($iqamaTypes as $type)
                                <option value="{{ $type->id }}" {{ request('iqama_type_id') == $type->id ? 'selected' : '' }}>
                                    {{ $type->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label>{{ __('Status') }}</label>
                        <select name="status" class="form-select">
                            <option value="">{{ __('All') }}</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>
                                {{ __('Active') }}
                            </option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>
                                {{ __('Inactive') }}
                            </option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label>{{ __('Expired Date Range') }}</label>
                        <input type="text" name="date_range" id="date_range" class="form-control"
                               value="{{ request('date_range') }}" placeholder="{{ __('Select Range') }}">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="reset" class="btn btn-warning">{{ __('Reset') }}</button>
                    <button type="submit" class="btn btn-primary">{{ __('Apply') }}</button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

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
            const table = $('#employeesTable').DataTable({
                dom: 'Bfrtip',
                pageLength: 25,
                lengthChange: false,
                buttons: [
                    {
                        extend: 'print',
                        text: '<i class="fa fa-print"></i> {{ __("Print") }}',
                        className: 'btn btn-primary',
                        title: '',
                        footer: true,
                        customize: function (win) {
                            // Calculate totals for print
                            var totalEmployees = {{ $employees->count() }};
                            var totalSalary = {{ $employees->sum('salary') }};
                            var activeEmployees = {{ $employees->where('status', 'active')->count() }};
                            var inactiveEmployees = {{ $employees->where('status', 'inactive')->count() }};

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
                                <p style="margin:0; font-family: 'Cairo', sans-serif; font-size: 14pt; font-weight: bold;">{{ __("Employees Report") }}</p>
                                <hr style="border-top:2px solid #007bff; width:80%; margin:10px auto;">
                                <p style="font-size:11pt; margin:5px 0;">{{ __("Generated on") }}: ${new Date().toLocaleDateString()}</p>
                                <div style="background: #f8f9fa; padding: 10px; border-radius: 5px; margin: 10px 0; font-size: 10pt;">
                                    <div style="display: inline-block; margin: 0 15px;">
                                        <strong>{{ __("Total Employees") }}:</strong> ${totalEmployees}
                                    </div>
                                    <div style="display: inline-block; margin: 0 15px;">
                                        <strong>{{ __("Active") }}:</strong> ${activeEmployees}
                                    </div>
                                    <div style="display: inline-block; margin: 0 15px;">
                                        <strong>{{ __("Inactive") }}:</strong> ${inactiveEmployees}
                                    </div>
                                    <div style="display: inline-block; margin: 0 15px;">
                                        <strong>{{ __("Total Salary") }}:</strong> AED ${formatNumber(totalSalary)}
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

                            // Style the Salary column to make it stand out
                            $(win.document.body).find('td:nth-child(6)')
                                .css({
                                    'font-weight': 'bold',
                                    'color': '#000'
                                });

                            // Add totals row at the bottom of the table
                            var tfoot = `
                            <tfoot>
                                <tr style="background-color: #f8f9fa; font-weight: bold; border-top: 3px double #007bff;">
                                    <td colspan="5" style="text-align: right; padding: 8px; border: 1px solid #ddd; font-family: 'Cairo', sans-serif;">
                                        {{ __("TOTALS:") }}
                            </td>
                            <td style="padding: 8px; border: 1px solid #ddd; font-family: 'Cairo', sans-serif; font-weight: bold; color: #007bff;">
                                AED ${formatNumber(totalSalary)}
                                    </td>
                                    <td colspan="2" style="text-align: center; padding: 8px; border: 1px solid #ddd; font-family: 'Cairo', sans-serif;">
                                        {{ __("Summary") }}: {{ __("Total Employees") }}: ${totalEmployees} | {{ __("Active") }}: ${activeEmployees} | {{ __("Inactive") }}: ${inactiveEmployees}
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
                                    td:nth-child(6) {
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
                                    tfoot td:nth-child(6) {
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
                        title: 'Employees_Report_{{ now()->format("Y_m_d") }}',
                        messageTop: function() {
                            var totalEmployees = {{ $employees->count() }};
                            var totalSalary = {{ $employees->sum('salary') }};
                            var activeEmployees = {{ $employees->where('status', 'active')->count() }};
                            var inactiveEmployees = {{ $employees->where('status', 'inactive')->count() }};

                            return 'Report Generated: ' + new Date().toLocaleDateString() + '\n' +
                                'Total Employees: ' + totalEmployees + '\n' +
                                'Active: ' + activeEmployees + ' | Inactive: ' + inactiveEmployees + '\n' +
                                'Total Salary: AED ' + formatNumber(totalSalary);
                        },
                        footer: true,
                        exportOptions: {
                            columns: ':visible',
                            format: {
                                body: function(data, row, column, node) {
                                    // Remove HTML tags and badges
                                    var cleanData = data.replace(/<[^>]*>/g, '').trim();
                                    // Remove extra spaces
                                    cleanData = cleanData.replace(/\s+/g, ' ');
                                    return cleanData;
                                }
                            }
                        },
                        customize: function (xlsx) {
                            var sheet = xlsx.xl.worksheets['sheet1.xml'];
                            var rows = $('row', sheet);

                            var totalEmployees = {{ $employees->count() }};
                            var totalSalary = {{ $employees->sum('salary') }};
                            var activeEmployees = {{ $employees->where('status', 'active')->count() }};
                            var inactiveEmployees = {{ $employees->where('status', 'inactive')->count() }};

                            var lastRowNum = rows.length + 1;

                            // Add empty row
                            $('sheetData', sheet).append('<row r="' + lastRowNum + '"></row>');
                            lastRowNum++;

                            // Add totals row
                            // Columns: # | Name | Company | Iqama Type | Upcoming Stage | Salary | Expired Date | Status
                            var totalsRow = '<row r="' + lastRowNum + '">' +
                                '<c r="A' + lastRowNum + '" t="inlineStr"><is><t><b>TOTALS</b></t></is></c>' +
                                '<c r="B' + lastRowNum + '"></c>' +
                                '<c r="C' + lastRowNum + '"></c>' +
                                '<c r="D' + lastRowNum + '"></c>' +
                                '<c r="E' + lastRowNum + '"></c>' +
                                '<c r="F' + lastRowNum + '"><v>' + totalSalary + '</v></c>' +
                                '<c r="G' + lastRowNum + '"></c>' +
                                '<c r="H' + lastRowNum + '" t="inlineStr"><is><t><b>Total: ' + totalEmployees + ' | Active: ' + activeEmployees + ' | Inactive: ' + inactiveEmployees + '</b></t></is></c>' +
                                '</row>';

                            $('sheetData', sheet).append(totalsRow);

                            // Set column widths for better readability
                            var colWidths = [
                                { wch: 5 },   // # (A)
                                { wch: 25 },  // Name (B)
                                { wch: 20 },  // Company (C)
                                { wch: 15 },  // Iqama Type (D)
                                { wch: 20 },  // Upcoming Stage (E)
                                { wch: 15 },  // Salary (F)
                                { wch: 15 },  // Expired Date (G)
                                { wch: 12 }   // Status (H)
                            ];

                            // Add column width definitions
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
                    // Add render function for salary column (index 5)
                    {
                        targets: [5], // Salary column (6th column, index 5)
                        render: function(data, type, row) {
                            if (type === 'display' || type === 'filter') {
                                if (data === 'N/A' || !data) return 'N/A';
                                return formatNumber(data);
                            }
                            if (data === 'N/A' || !data) return 0;
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

                    // Calculate total salary (column 5, index 5)
                    var salaryTotal = api
                        .column(5, { page: 'current' })
                        .data()
                        .reduce(function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0);

                    // Calculate total records on current page
                    var pageTotal = api.page.info().recordsDisplay;
                    var activeCount = api.column(7).data().toArray().filter(status => status === 'active').length;
                    var inactiveCount = api.column(7).data().toArray().filter(status => status === 'inactive').length;

                    // Update footer in the main table view
                    if ($(api.table().footer()).length === 0) {
                        $(api.table()).append('<tfoot><tr></tr></tfoot>');
                    }

                    var footerRow = $(api.table().footer()).find('tr');
                    footerRow.html(`
                    <td colspan="5" class="text-end" style="font-family: 'Cairo', sans-serif;">
                        <strong>{{ __("Page Totals:") }}</strong>
                    </td>
                    <td class="text-center" style="font-family: 'Cairo', sans-serif; font-weight: bold; color: #007bff;">
                        <strong>AED ${formatNumber(salaryTotal)}</strong>
                    </td>
                    <td colspan="2" class="text-center" style="font-family: 'Cairo', sans-serif;">
                        <small class="text-muted">{{ __("Page Records") }}: ${pageTotal} | {{ __("Active") }}: ${activeCount} | {{ __("Inactive") }}: ${inactiveCount}</small>
                    </td>
                `);
                },
                drawCallback: function (settings) {
                    // Calculate global totals from original data
                    var globalTotalSalary = {{ $employees->sum('salary') }};
                    var globalTotalRecords = {{ $employees->count() }};
                    var globalActiveRecords = {{ $employees->where('status', 'active')->count() }};
                    var globalInactiveRecords = {{ $employees->where('status', 'inactive')->count() }};

                    var info = $(this).closest('.dataTables_wrapper').find('.dataTables_info');

                    // Remove any existing total info
                    var infoText = info.html();
                    if (infoText.includes('|')) {
                        info.html(infoText.split('|')[0]);
                    }

                    // Add global totals to the info text
                    info.html(info.html() +
                        ' <span class="text-primary">| {{ __("Total Salary") }}: <strong>AED ' +
                        formatNumber(globalTotalSalary) + '</strong></span>' +
                        ' <span class="text-success">| {{ __("Active") }}: <strong>' +
                        globalActiveRecords + '</strong></span>' +
                        ' <span class="text-secondary">| {{ __("Inactive") }}: <strong>' +
                        globalInactiveRecords + '</strong></span>');
                }
            });

            // Handle perPage dropdown change
            $('#perPage').on('change', function () {
                const perPage = parseInt($(this).val());
                table.page.len(perPage).draw();
            });

            // Initialize Flatpickr for date range
            const fpDateRange = flatpickr("#date_range", {
                mode: "range",
                dateFormat: "Y-m-d",
                allowInput: true
            });

            // Reset filters to defaults
            $('#filterModal').on('reset', 'form', function (e) {
                e.preventDefault();
                const $form = $(this);
                $form.find('input, select, textarea').each(function () {
                    const $field = $(this);
                    const def = $field.data('default') ?? '';
                    if ($field.is('select')) {
                        $field.val(def).trigger('change');
                    } else if ($field.attr('id') === 'date_range') {
                        fpDateRange.clear();
                        if (def) fpDateRange.setDate(def.split(' to '));
                    } else {
                        $field.val(def);
                    }
                });
            });
        });
    </script>

</x-dashboard.main-layout>
<style>
    /* Add Cairo font import if not already present */
    @import url('https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700&display=swap');

    #employeesTable thead th {
        text-align: center;
        vertical-align: middle;
        font-family: 'Cairo', sans-serif;
        background-color: #007bff;
        color: #ffffff;
    }

    #employeesTable tbody td {
        font-family: 'Cairo', sans-serif;
    }

    #employeesTable tfoot {
        background-color: #f8f9fa;
        font-weight: bold;
    }

    #employeesTable tfoot td {
        border-top: 2px solid #007bff !important;
        text-align: center;
        vertical-align: middle;
        padding: 10px !important;
        font-family: 'Cairo', sans-serif;
    }

    #employeesTable tfoot td:nth-child(6) {
        color: #007bff;
        font-size: 1.1em;
    }

    #employeesTable tbody td:nth-child(6) {
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
        #employeesTable tfoot {
            background-color: #f8f9fa !important;
            -webkit-print-color-adjust: exact;
        }

        #employeesTable tfoot td {
            border-top: 3px double #007bff !important;
            font-weight: bold !important;
        }

        #employeesTable tfoot td:nth-child(6) {
            color: #007bff !important;
        }

        #employeesTable tbody td:nth-child(6) {
            font-weight: bold !important;
            color: #000 !important;
        }
    }
</style>
