<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

<x-dashboard.main-layout>
    <div class="container-fluid py-4">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <span>{{ __('End of Service List') }}</span>
                <div>
                    {{-- Active Filters Badge --}}
                    @php
                        $activeFilterCount = 0;
                        $filterFields = ['employee_id', 'year_from', 'year_to', 'service_years', 'net_pay_min', 'net_pay_max', 'date_range'];
                        foreach ($filterFields as $field) {
                            if (request()->filled($field)) {
                                $activeFilterCount++;
                            }
                        }
                    @endphp

                    @if($activeFilterCount > 0)
                        <span class="badge bg-warning me-2" id="activeFiltersCount">
                        {{ $activeFilterCount }} {{ __('Active Filters') }}
                    </span>
                    @endif

                    <button class="btn btn-light btn-sm" type="button" data-bs-toggle="modal" data-bs-target="#filterModal">
                        <i class="fa fa-filter"></i> {{ __('Filters') }}
                    </button>
                </div>
            </div>

            {{-- Results Summary --}}
            <div class="card-body py-2 border-bottom">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h6 class="mb-0 text-primary">
                            <i class="fa fa-chart-bar"></i>
                            {{ __('Results') }}:
                            <strong>{{ $eosRecords->count() }}</strong>
                            {{ __('records found') }}
                        </h6>
                    </div>
                    <div class="col-md-6 text-end">
                        @if($eosRecords->count() > 0)
                            <small class="text-muted">
                                {{ __('Net Pay Total') }}:
                                <strong>AED {{ number_format($eosRecords->sum('net_pay'), 2) }}</strong>
                            </small>
                        @endif
                    </div>
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

                        @if(request('employee_id'))
                            @php
                                $employeeName = $employees->where('id', request('employee_id'))->first()->name ?? 'N/A';
                            @endphp
                            <span class="badge bg-primary me-1 mb-1 text-white">
                            {{ __('Employee') }}: {{ $employeeName }}
                            <a href="{{ removeQueryParams('employee_id') }}" class="text-white ms-1" style="text-decoration: none;">×</a>
                        </span>
                        @endif

                        @if(request('year_from'))
                            <span class="badge bg-info  me-1 mb-1 text-white">
                            {{ __('Year From') }}: {{ request('year_from') }}
                            <a href="{{ removeQueryParams('year_from') }}" class=" ms-1" style="text-decoration: none;">×</a>
                        </span>
                        @endif

                        @if(request('year_to'))
                            <span class="badge bg-info  me-1 mb-1 text-white">
                            {{ __('Year To') }}: {{ request('year_to') }}
                            <a href="{{ removeQueryParams('year_to') }}" class=" ms-1" style="text-decoration: none;">×</a>
                        </span>
                        @endif

                        @if(request('service_years'))
                            <span class="badge bg-warning  me-1 mb-1 text-white">
                            {{ __('Service Years') }}: {{ request('service_years') }}+
                            <a href="{{ removeQueryParams('service_years') }}" class=" ms-1" style="text-decoration: none;">×</a>
                        </span>
                        @endif

                        @if(request('net_pay_min') || request('net_pay_max'))
                            <span class="badge bg-success me-1 mb-1 text-white">
                            {{ __('Net Pay') }}:
                            {{ request('net_pay_min', 0) }} - {{ request('net_pay_max', '∞') }}
                            <a href="{{ removeQueryParams(['net_pay_min', 'net_pay_max']) }}" class="text-white ms-1" style="text-decoration: none;">×</a>
                        </span>
                        @endif

                        @if(request('date_range'))
                            <span class="badge bg-secondary me-1 mb-1 text-white">
                            {{ __('Date Range') }}: {{ request('date_range') }}
                            <a href="{{ removeQueryParams('date_range') }}" class="text-white ms-1" style="text-decoration: none;">×</a>
                        </span>
                        @endif

                        <a href="{{ url()->current() }}" class="btn btn-outline-danger btn-sm ms-2">
                            <i class="fa fa-times"></i> {{ __('Clear All') }}
                        </a>
                    </div>
                </div>
            @endif

            {{-- Table Section --}}
            <div class="card-body table-responsive">
                <table id="eosTable" class="table table-hover align-middle text-center w-100">
                    <thead class="table-light text-center">
                    <tr>
                        <th>#</th>
                        <th>{{ __('Employee Name') }}</th>
                        <th>{{ __('Joining Date') }}</th>
                        <th>{{ __('Leaving Date') }}</th>
                        <th>{{ __('Years of Service') }}</th>
                        <th>{{ __('Salary') }}</th>
                        <th>{{ __('Leaves') }}</th>
                        <th>{{ __('Net Pay (AED)') }}</th>
                    </tr>
                    </thead>
                    <tbody class="text-center">
                    @foreach($eosRecords as $eo)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $eo->employee->name ?? __('N/A') }}</td>
                            <td>{{ \Carbon\Carbon::parse($eo->joining_date)->format('Y-m-d') }}</td>
                            <td>{{ \Carbon\Carbon::parse($eo->leaving_date)->format('Y-m-d') }}</td>
                            <td>{{ number_format(\Carbon\Carbon::parse($eo->joining_date)->diffInYears(\Carbon\Carbon::parse($eo->leaving_date)), 2) }}</td>
                            <td>{{ number_format($eo->employee->salary, 2) }}</td>
                            <td>{{ $eo->employee->leaves()->count() }}</td>
                            <td><strong>{{ number_format($eo->net_pay, 2) }}</strong></td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Filter Modal --}}
    <div class="modal fade" id="filterModal" tabindex="-1" aria-labelledby="filterModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="filterModalLabel">
                        <i class="fa fa-filter"></i> {{ __('Filter End of Service Records') }}
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="filterForm" method="GET" action="{{ url()->current() }}">
                        <div class="row g-3">
                            {{-- Employee Filter --}}
                            <div class="col-md-6">
                                <label for="employee_id" class="form-label">{{ __('Employee Name') }}</label>
                                <select id="employee_id" name="employee_id" class="form-select select2-filter">
                                    <option value="">{{ __('All Employees') }}</option>
                                    @foreach($employees as $employee)
                                        <option value="{{ $employee->id }}"
                                            {{ request('employee_id') == $employee->id ? 'selected' : '' }}>
                                            {{ $employee->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Date Range Filter --}}
                            <div class="col-md-6">
                                <label for="date_range" class="form-label">{{ __('Leaving Date Range') }}</label>
                                <input type="text" id="date_range" name="date_range" class="form-control date-range-picker"
                                       placeholder="{{ __('Select date range') }}"
                                       value="{{ request('date_range') }}">
                            </div>

                            {{-- Year Range Filters --}}
                            <div class="col-md-6">
                                <label for="year_from" class="form-label">{{ __('Joining Year From') }}</label>
                                <input type="number" id="year_from" name="year_from" class="form-control"
                                       placeholder="{{ __('Start year') }}" min="{{ $filterData['min_year'] ?? 2000 }}"
                                       max="{{ $filterData['max_year'] ?? date('Y') }}" value="{{ request('year_from') }}">
                            </div>

                            <div class="col-md-6">
                                <label for="year_to" class="form-label">{{ __('Leaving Year To') }}</label>
                                <input type="number" id="year_to" name="year_to" class="form-control"
                                       placeholder="{{ __('End year') }}" min="{{ $filterData['min_year'] ?? 2000 }}"
                                       max="{{ $filterData['max_year'] ?? date('Y') }}" value="{{ request('year_to') }}">
                            </div>

                            {{-- Minimum Service Years Filter --}}
                            <div class="col-md-6">
                                <label for="service_years" class="form-label">{{ __('Minimum Service Years') }}</label>
                                <select id="service_years" name="service_years" class="form-select">
                                    <option value="">{{ __('Any Service Years') }}</option>
                                    <option value="1" {{ request('service_years') == '1' ? 'selected' : '' }}>1+ {{ __('Years') }}</option>
                                    <option value="2" {{ request('service_years') == '2' ? 'selected' : '' }}>2+ {{ __('Years') }}</option>
                                    <option value="3" {{ request('service_years') == '3' ? 'selected' : '' }}>3+ {{ __('Years') }}</option>
                                    <option value="5" {{ request('service_years') == '5' ? 'selected' : '' }}>5+ {{ __('Years') }}</option>
                                    <option value="10" {{ request('service_years') == '10' ? 'selected' : '' }}>10+ {{ __('Years') }}</option>
                                    <option value="15" {{ request('service_years') == '15' ? 'selected' : '' }}>15+ {{ __('Years') }}</option>
                                    <option value="20" {{ request('service_years') == '20' ? 'selected' : '' }}>20+ {{ __('Years') }}</option>
                                </select>
                                <div class="form-text">{{ __('Show employees with at least this many years of service') }}</div>
                            </div>

                            {{-- Net Pay Range Filter --}}
                            <div class="col-md-6">
                                <label class="form-label">{{ __('Net Pay Range (AED)') }}</label>
                                <div class="row g-2">
                                    <div class="col">
                                        <input type="number" id="net_pay_min" name="net_pay_min" class="form-control"
                                               placeholder="{{ __('Minimum') }}" value="{{ request('net_pay_min') }}"
                                               min="0" step="1000">
                                    </div>
                                    <div class="col">
                                        <input type="number" id="net_pay_max" name="net_pay_max" class="form-control"
                                               placeholder="{{ __('Maximum') }}" value="{{ request('net_pay_max') }}"
                                               min="0" step="1000">
                                    </div>
                                </div>
                                <div class="form-text">{{ __('Leave empty for no limit') }}</div>
                            </div>

                            {{-- Quick Filters --}}
                            <div class="col-12">
                                <label class="form-label">{{ __('Quick Date Filters') }}</label>
                                <div class="d-flex flex-wrap gap-2">
                                    <button type="button" class="btn btn-outline-primary btn-sm quick-filter" data-days="30">
                                        {{ __('Last 30 Days') }}
                                    </button>
                                    <button type="button" class="btn btn-outline-primary btn-sm quick-filter" data-days="90">
                                        {{ __('Last 3 Months') }}
                                    </button>
                                    <button type="button" class="btn btn-outline-primary btn-sm quick-filter" data-days="180">
                                        {{ __('Last 6 Months') }}
                                    </button>
                                    <button type="button" class="btn btn-outline-primary btn-sm quick-filter" data-days="365">
                                        {{ __('Last Year') }}
                                    </button>
                                </div>
                            </div>

                            {{-- Statistics --}}
                            <div class="col-12">
                                <div class="card bg-light">
                                    <div class="card-body py-2">
                                        <div class="row text-center">
                                            <div class="col-md-3">
                                                <small class="text-muted">{{ __('Total Records') }}</small>
                                                <div class="fw-bold text-primary">{{ $eosRecords->count() }}</div>
                                            </div>
                                            <div class="col-md-3">
                                                <small class="text-muted">{{ __('Min Service Years') }}</small>
                                                <div class="fw-bold text-info">
                                                    @if($eosRecords->count() > 0)
                                                        {{ number_format($eosRecords->min(function($eo) {
                                                            return \Carbon\Carbon::parse($eo->joining_date)->diffInYears(\Carbon\Carbon::parse($eo->leaving_date));
                                                        }), 1) }}
                                                    @else
                                                        0
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <small class="text-muted">{{ __('Max Service Years') }}</small>
                                                <div class="fw-bold text-info">
                                                    @if($eosRecords->count() > 0)
                                                        {{ number_format($eosRecords->max(function($eo) {
                                                            return \Carbon\Carbon::parse($eo->joining_date)->diffInYears(\Carbon\Carbon::parse($eo->leaving_date));
                                                        }), 1) }}
                                                    @else
                                                        0
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <small class="text-muted">{{ __('Total Net Pay') }}</small>
                                                <div class="fw-bold text-success">AED {{ number_format($eosRecords->sum('net_pay'), 2) }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
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

<!-- Load jQuery FIRST -->
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

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

{{-- Google Font for Arabic --}}
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700&display=swap" rel="stylesheet">

<script>
    $(document).ready(function () {
        // Initialize Select2
        $('.select2-filter').select2({
            placeholder: "{{ __('Select Employee') }}",
            allowClear: true,
            dropdownParent: $('#filterModal')
        });

        // Initialize Date Range Picker
        flatpickr("#date_range", {
            mode: "range",
            dateFormat: "Y-m-d",
            locale: "ar",
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

        // Auto-close modal after applying filters
        $('#filterForm').on('submit', function() {
            $('#filterModal').modal('hide');
        });

        // Initialize DataTables
        var table = $('#eosTable').DataTable({
            pageLength: 10,
            lengthMenu: [5, 10, 25, 50, 100],
            dom: 'Blfrtip',
            buttons: [
                {
                    extend: 'print',
                    text: '<i class="fa fa-print"></i> {{ __("Print") }}',
                    className: 'btn btn-primary',
                    title: '',
                    customize: function (win) {
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
                                <p style="margin:0; font-family: 'Cairo', sans-serif; font-size: 14pt; font-weight: bold;">{{ __("End of Service Report") }}</p>
                                <hr style="border-top:2px solid #007bff; width:80%; margin:10px auto;">
                                <p style="font-size:11pt; margin:5px 0;">{{ __("Generated on") }}: ${new Date().toLocaleDateString()}</p>
                                <p style="font-size:10pt; margin:5px 0; color: #666;">{{ __("Total Records") }}: {{ $eosRecords->count() }}</p>
                                <p style="font-size:10pt; margin:5px 0; color: #666;">{{ __("Total Net Pay") }}: AED {{ number_format($eosRecords->sum('net_pay'), 2) }}</p>
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

                        // Style the Net Pay column to make it stand out
                        $(win.document.body).find('td:last-child')
                            .css({
                                'font-weight': 'bold',
                                'color': '#000'
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
                                    td:last-child {
                                        font-weight: bold !important;
                                        color: #000 !important;
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
                    className: 'btn btn-success',
                    title: 'End_of_Service_Report_{{ now()->format("Y_m_d") }}',
                    message: 'Filtered Results'
                }
            ],
            columnDefs: [
                {
                    className: "text-center",
                    targets: "_all"
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
            }
        });
    });
</script>

<style>
    #eosTable thead th {
        text-align: center;
        vertical-align: middle;
        font-family: 'Cairo', sans-serif;
        background-color: #007bff;
        color: #ffffff;
    }

    #eosTable tbody td {
        font-family: 'Cairo', sans-serif;
    }

    #eosTable tbody td:last-child {
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

    /* Modal Styling */
    .modal-lg {
        max-width: 800px;
    }

    .form-label {
        font-weight: 600;
        margin-bottom: 0.5rem;
        font-size: 0.9rem;
    }

    .select2-container--default .select2-selection--single {
        border: 1px solid #ced4da;
        height: 38px;
        padding: 5px;
        border-radius: 0.375rem;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 36px;
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

    .quick-filter {
        margin-right: 5px;
        margin-bottom: 5px;
    }

    .flatpickr-input {
        background-color: white;
    }

    /* Statistics Card */
    .card.bg-light .card-body {
        padding: 0.75rem;
    }

    /* Ensure DataTables elements use Cairo font */
    .dataTables_wrapper .dataTables_length,
    .dataTables_wrapper .dataTables_filter,
    .dataTables_wrapper .dataTables_info,
    .dataTables_wrapper .dataTables_paginate {
        font-family: 'Cairo', sans-serif;
    }
</style>
