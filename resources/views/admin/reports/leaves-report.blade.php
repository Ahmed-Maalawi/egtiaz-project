<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

<x-dashboard.main-layout>
    <div class="container-fluid py-4">

        {{-- Success Message --}}
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <span>{{ __('Leaves Report') }}</span>
                <div>
                    {{-- Active Filters Badge --}}
                    @php
                        $activeFilterCount = 0;
                        $filterFields = ['user_id', 'leave_type', 'status', 'date_range', 'days_min', 'days_max', 'year_from', 'year_to'];
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
                            <strong>{{ $leaves->count() }}</strong>
                            {{ __('records found') }}
                        </h6>
                    </div>
                    <div class="col-md-6 text-end">
                        @if($leaves->count() > 0)
                            <small class="text-muted">
                                {{ __('Total Leave Days') }}:
                                <strong>{{ $leaves->sum('days_taken') }}</strong>
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
                            function removeQueryParamsLeaves($paramsToRemove) {
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

                        @if(request('user_id'))
                            @php
                                $userName = $users->where('id', request('user_id'))->first()->name ?? 'N/A';
                            @endphp
                            <span class="badge bg-primary me-1 mb-1 text-white">
                            {{ __('User') }}: {{ $userName }}
                            <a href="{{ removeQueryParamsLeaves('user_id') }}" class="text-white ms-1" style="text-decoration: none;">×</a>
                        </span>
                        @endif

                        @if(request('leave_type'))
                            <span class="badge bg-info text-white me-1 mb-1">
                            {{ __('Type') }}: {{ request('leave_type') }}
                            <a href="{{ removeQueryParamsLeaves('leave_type') }}" class="text-white ms-1" style="text-decoration: none;">×</a>
                        </span>
                        @endif

                        @if(request('status'))
                            <span class="badge bg-warning text-white me-1 mb-1">
                            {{ __('Status') }}: {{ ucfirst(request('status')) }}
                            <a href="{{ removeQueryParamsLeaves('status') }}" class="ms-1" style="text-decoration: none;">×</a>
                        </span>
                        @endif

                        @if(request('days_min') || request('days_max'))
                            <span class="badge bg-success me-1 mb-1 text-white">
                            {{ __('Days') }}:
                            {{ request('days_min', 1) }} - {{ request('days_max', '∞') }}
                            <a href="{{ removeQueryParamsLeaves(['days_min', 'days_max']) }}" class="text-white ms-1" style="text-decoration: none;">×</a>
                        </span>
                        @endif

                        @if(request('year_from'))
                            <span class="badge bg-secondary me-1 mb-1 text-white">
                            {{ __('Year From') }}: {{ request('year_from') }}
                            <a href="{{ removeQueryParamsLeaves('year_from') }}" class="text-white ms-1" style="text-decoration: none;">×</a>
                        </span>
                        @endif

                        @if(request('year_to'))
                            <span class="badge bg-secondary me-1 mb-1 text-white">
                            {{ __('Year To') }}: {{ request('year_to') }}
                            <a href="{{ removeQueryParamsLeaves('year_to') }}" class="text-white ms-1" style="text-decoration: none;">×</a>
                        </span>
                        @endif

                        @if(request('date_range'))
                            <span class="badge bg-dark me-1 mb-1 text-white">
                            {{ __('Date Range') }}: {{ request('date_range') }}
                            <a href="{{ removeQueryParamsLeaves('date_range') }}" class="text-white ms-1" style="text-decoration: none;">×</a>
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
                <table id="leavesTable" class="table table-hover align-middle text-center w-100">
                    <thead class="table-light text-center">
                    <tr>
                        <th>#</th>
                        <th>{{ __('User Name') }}</th>
                        <th>{{ __('Leaves Type') }}</th>
                        <th>{{ __('Leaves Days Number') }}</th>
                        <th>{{ __('Reason') }}</th>
                        <th>{{ __('Leaves From') }}</th>
                        <th>{{ __('Leaves To') }}</th>
                        <th>{{ __('Approved Leaves') }}</th>
                        <th>{{ __('Leave Date') }}</th>
                        <th>{{ __('Status') }}</th>
                    </tr>
                    </thead>

                    <tbody class="text-center">
                    @foreach($leaves as $leave)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $leave->user->name ?? __('N/A') }}</td>
                            <td>{{ __($leave->type) ?? __('N/A') }}</td>
                            <td>{{ $leave->days_taken ?? __('N/A') }}</td>
                            <td>{{ $leave->reason ?? __('N/A') }}</td>
                            <td>{{ \Carbon\Carbon::parse($leave->start_date)->format("Y-m-d") }}</td>
                            <td>{{ \Carbon\Carbon::parse($leave->end_date)->format("Y-m-d") }}</td>
                            <td>{{ $leave->approver->name ?? __('N/A') }}</td>
                            <td>{{ \Carbon\Carbon::parse($leave->created_at)->format("Y-m-d H:i") }}</td>
                            <td>
                                @php
                                    $statusClass = match($leave->status) {
                                        'approved' => 'success',
                                        'pending'  => 'warning',
                                        'rejected' => 'danger',
                                        default    => 'secondary'
                                    };
                                @endphp
                                <span class="badge bg-{{ $statusClass }} text-uppercase px-3 py-2 text-white">
                                    {{ __($leave->status) }}
                                </span>
                            </td>
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
                        <i class="fa fa-filter"></i> {{ __('Filter Leaves Records') }}
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="filterForm" method="GET" action="{{ url()->current() }}">
                        <div class="row g-3">
                            {{-- User Filter --}}
                            <div class="col-md-6">
                                <label for="user_id" class="form-label">{{ __('User Name') }}</label>
                                <select id="user_id" name="user_id" class="form-select select2-filter">
                                    <option value="">{{ __('All Users') }}</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}"
                                            {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                            {{ $user->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Leave Type Filter --}}
                            <div class="col-md-6">
                                <label for="leave_type" class="form-label">{{ __('Leave Type') }}</label>
                                <select id="leave_type" name="leave_type" class="form-select">
                                    <option value="">{{ __('All Types') }}</option>
                                    <option value="sick" {{ request('leave_type') == 'sick' ? 'selected' : '' }}>{{ __('Sick Leave') }}</option>
                                    <option value="annual" {{ request('leave_type') == 'annual' ? 'selected' : '' }}>{{ __('Annual Leave') }}</option>
                                    <option value="emergency" {{ request('leave_type') == 'emergency' ? 'selected' : '' }}>{{ __('Emergency Leave') }}</option>
                                    <option value="maternity" {{ request('leave_type') == 'maternity' ? 'selected' : '' }}>{{ __('Maternity Leave') }}</option>
                                    <option value="paternity" {{ request('leave_type') == 'paternity' ? 'selected' : '' }}>{{ __('Paternity Leave') }}</option>
                                    <option value="unpaid" {{ request('leave_type') == 'unpaid' ? 'selected' : '' }}>{{ __('Unpaid Leave') }}</option>
                                </select>
                            </div>

                            {{-- Status Filter --}}
                            <div class="col-md-4">
                                <label for="status" class="form-label">{{ __('Status') }}</label>
                                <select id="status" name="status" class="form-select">
                                    <option value="">{{ __('All Statuses') }}</option>
                                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>{{ __('Pending') }}</option>
                                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>{{ __('Approved') }}</option>
                                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>{{ __('Rejected') }}</option>
                                </select>
                            </div>

                            {{-- Days Range Filter --}}
                            <div class="col-md-4">
                                <label class="form-label">{{ __('Days Range') }}</label>
                                <div class="row g-2">
                                    <div class="col">
                                        <input type="number" id="days_min" name="days_min" class="form-control"
                                               placeholder="{{ __('Min') }}" value="{{ request('days_min') }}"
                                               min="1" max="365">
                                    </div>
                                    <div class="col">
                                        <input type="number" id="days_max" name="days_max" class="form-control"
                                               placeholder="{{ __('Max') }}" value="{{ request('days_max') }}"
                                               min="1" max="365">
                                    </div>
                                </div>
                                <div class="form-text">{{ __('Leave empty for no limit') }}</div>
                            </div>

                            {{-- Date Range Filter --}}
                            <div class="col-md-4">
                                <label for="date_range" class="form-label">{{ __('Leave Date Range') }}</label>
                                <input type="text" id="date_range" name="date_range" class="form-control date-range-picker"
                                       placeholder="{{ __('Select date range') }}"
                                       value="{{ request('date_range') }}">
                            </div>

                            {{-- Year Range Filters --}}
                            <div class="col-md-6">
                                <label for="year_from" class="form-label">{{ __('Start Year From') }}</label>
                                <input type="number" id="year_from" name="year_from" class="form-control"
                                       placeholder="{{ __('Start year') }}" min="2000"
                                       max="{{ date('Y') }}" value="{{ request('year_from') }}">
                            </div>

                            <div class="col-md-6">
                                <label for="year_to" class="form-label">{{ __('End Year To') }}</label>
                                <input type="number" id="year_to" name="year_to" class="form-control"
                                       placeholder="{{ __('End year') }}" min="2000"
                                       max="{{ date('Y') + 1 }}" value="{{ request('year_to') }}">
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
                                                <div class="fw-bold text-primary">{{ $leaves->count() }}</div>
                                            </div>
                                            <div class="col-md-3">
                                                <small class="text-muted">{{ __('Pending') }}</small>
                                                <div class="fw-bold text-warning">{{ $leaves->where('status', 'pending')->count() }}</div>
                                            </div>
                                            <div class="col-md-3">
                                                <small class="text-muted">{{ __('Approved') }}</small>
                                                <div class="fw-bold text-success">{{ $leaves->where('status', 'approved')->count() }}</div>
                                            </div>
                                            <div class="col-md-3">
                                                <small class="text-muted">{{ __('Total Days') }}</small>
                                                <div class="fw-bold text-info">{{ $leaves->sum('days_taken') }}</div>
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
        // Helper function to format numbers
        function formatNumber(number) {
            if (number === null || number === undefined || isNaN(number)) {
                return '0';
            }
            // If it's already a formatted string with commas, remove them first
            if (typeof number === 'string') {
                number = number.replace(/[^0-9.-]/g, '');
            }
            return parseFloat(number).toLocaleString('en-US', {
                minimumFractionDigits: 0,
                maximumFractionDigits: 0
            });
        }

        // Initialize Select2
        $('.select2-filter').select2({
            placeholder: "{{ __('Select User') }}",
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
        var table = $('#leavesTable').DataTable({
            pageLength: 10,
            lengthMenu: [5, 10, 25, 50, 100],
            dom: 'Blfrtip',
            buttons: [
                {
                    extend: 'print',
                    text: '<i class="fa fa-print"></i> {{ __("Print") }}',
                    className: 'btn btn-primary',
                    title: '',
                    footer: true,
                    customize: function (win) {
                        // Apply Cairo font
                        $(win.document.body)
                            .css('font-family', '"Cairo", sans-serif')
                            .css('font-size', '12pt')
                            .css('color', '#000')
                            .css('direction', 'ltr');

                        // Calculate totals for print
                        var totalLeaves = {{ $leaves->count() }};
                        var totalDays = {{ $leaves->sum('days_taken') }};
                        var pendingCount = {{ $leaves->where('status', 'pending')->count() }};
                        var approvedCount = {{ $leaves->where('status', 'approved')->count() }};
                        var rejectedCount = {{ $leaves->where('status', 'rejected')->count() }};

                        // Add custom header
                        $(win.document.body).prepend(`
                            <div style="text-align:center; margin-bottom:25px; font-family: 'Cairo', sans-serif;">
                                <h2 style="margin:0; font-family: 'Cairo', sans-serif; color: #007bff;">{{ config('app.name') }}</h2>
                                <p style="margin:0; font-family: 'Cairo', sans-serif; font-size: 14pt; font-weight: bold;">{{ __("Leaves Report") }}</p>
                                <hr style="border-top:2px solid #007bff; width:80%; margin:10px auto;">
                                <p style="font-size:11pt; margin:5px 0;">{{ __("Generated on") }}: ${new Date().toLocaleDateString()}</p>
                                <div style="background: #f8f9fa; padding: 10px; border-radius: 5px; margin: 10px 0; font-size: 10pt;">
                                    <div style="display: inline-block; margin: 0 15px;">
                                        <strong>{{ __("Total Records") }}:</strong> ${totalLeaves}
                                    </div>
                                    <div style="display: inline-block; margin: 0 15px;">
                                        <strong>{{ __("Total Days") }}:</strong> ${formatNumber(totalDays)}
                                    </div>
                                    <div style="display: inline-block; margin: 0 15px;">
                                        <strong>{{ __("Pending") }}:</strong> ${pendingCount}
                                    </div>
                                    <div style="display: inline-block; margin: 0 15px;">
                                        <strong>{{ __("Approved") }}:</strong> ${approvedCount}
                                    </div>
                                    <div style="display: inline-block; margin: 0 15px;">
                                        <strong>{{ __("Rejected") }}:</strong> ${rejectedCount}
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

                        // Add totals row at the bottom of the table
                        var tfoot = `
                            <tfoot>
                                <tr style="background-color: #f8f9fa; font-weight: bold; border-top: 3px double #007bff;">
                                    <td colspan="3" style="text-align: right; padding: 8px; border: 1px solid #ddd; font-family: 'Cairo', sans-serif;">
                                        {{ __("TOTALS:") }}
                        </td>
                        <td style="padding: 8px; border: 1px solid #ddd; font-family: 'Cairo', sans-serif; font-weight: bold; color: #007bff;">
${formatNumber(totalDays)}
                                    </td>
                                    <td colspan="6" style="text-align: center; padding: 8px; border: 1px solid #ddd; font-family: 'Cairo', sans-serif;">
                                        {{ __("Summary") }}: {{ __("Total Records") }}: ${totalLeaves} | {{ __("Total Days") }}: ${formatNumber(totalDays)}
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
                    text: '<i class="fa fa-file-excel"></i> {{ __("Export Excel") }}',
                    className: 'btn btn-success',
                    title: 'Leaves_Report_{{ now()->format("Y_m_d") }}',
                    messageTop: function() {
                        var totalLeaves = {{ $leaves->count() }};
                        var totalDays = {{ $leaves->sum('days_taken') }};
                        var pendingCount = {{ $leaves->where('status', 'pending')->count() }};
                        var approvedCount = {{ $leaves->where('status', 'approved')->count() }};
                        var rejectedCount = {{ $leaves->where('status', 'rejected')->count() }};

                        return 'Report Generated: ' + new Date().toLocaleDateString() + '\n' +
                            'Total Records: ' + totalLeaves + ' | ' +
                            'Total Days: ' + totalDays + ' | ' +
                            'Pending: ' + pendingCount + ' | ' +
                            'Approved: ' + approvedCount + ' | ' +
                            'Rejected: ' + rejectedCount;
                    },
                    footer: true,
                    exportOptions: {
                        columns: ':visible',
                        format: {
                            body: function(data, row, column, node) {
                                // Remove HTML tags and badges
                                return data.replace(/<[^>]*>/g, '').trim();
                            }
                        }
                    },
                    customize: function(xlsx) {
                        var sheet = xlsx.xl.worksheets['sheet1.xml'];
                        var sSh = xlsx.xl['styles.xml'];

                        // Get all rows
                        var rows = $('row', sheet);
                        var lastRowNum = rows.length + 1;

                        // Calculate totals from visible data
                        var totalDays = {{ $leaves->sum('days_taken') }};
                        var totalRecords = {{ $leaves->count() }};

                        // Add empty row
                        var emptyRow = '<row r="' + lastRowNum + '"></row>';
                        $('sheetData', sheet).append(emptyRow);
                        lastRowNum++;

                        // Add totals row with merged cells and styling
                        var totalsRow = '<row r="' + lastRowNum + '">' +
                            '<c r="A' + lastRowNum + '" t="inlineStr" s="2"><is><t>TOTALS</t></is></c>' +
                            '<c r="B' + lastRowNum + '" s="2"></c>' +
                            '<c r="C' + lastRowNum + '" s="2"></c>' +
                            '<c r="D' + lastRowNum + '" s="2"><v>' + totalDays + '</v></c>' +
                            '<c r="E' + lastRowNum + '" s="2"></c>' +
                            '<c r="F' + lastRowNum + '" s="2"></c>' +
                            '<c r="G' + lastRowNum + '" s="2"></c>' +
                            '<c r="H' + lastRowNum + '" s="2"></c>' +
                            '<c r="I' + lastRowNum + '" s="2"></c>' +
                            '<c r="J' + lastRowNum + '" t="inlineStr" s="2"><is><t>Total Records: ' + totalRecords + '</t></is></c>' +
                            '</row>';

                        $('sheetData', sheet).append(totalsRow);

                        // Style the totals row (bold and background color)
                        var numFmts = $('numFmts', sSh);
                        var cellXfs = $('cellXfs', sSh);

                        // Add bold style for totals row
                        var boldStyle = '<xf numFmtId="0" fontId="2" fillId="5" borderId="1" xfId="0" applyFont="1" applyFill="1" applyBorder="1"/>';
                        cellXfs.append(boldStyle);

                        // Merge cells for "TOTALS" label (A to C)
                        if (!$('mergeCells', sheet).length) {
                            $('sheetData', sheet).after('<mergeCells count="1"><mergeCell ref="A' + lastRowNum + ':C' + lastRowNum + '"/></mergeCells>');
                        } else {
                            var mergeCount = $('mergeCells', sheet).attr('count');
                            $('mergeCells', sheet).attr('count', parseInt(mergeCount) + 1);
                            $('mergeCells', sheet).append('<mergeCell ref="A' + lastRowNum + ':C' + lastRowNum + '"/>');
                        }

                        // Auto-fit columns
                        var colWidth = [
                            { wch: 5 },   // #
                            { wch: 20 },  // User Name
                            { wch: 15 },  // Leave Type
                            { wch: 12 },  // Days
                            { wch: 30 },  // Reason
                            { wch: 12 },  // From
                            { wch: 12 },  // To
                            { wch: 20 },  // Approver
                            { wch: 15 },  // Date
                            { wch: 12 }   // Status
                        ];

                        var cols = $('cols', sheet);
                        if (cols.length === 0) {
                            cols = $('<cols/>');
                            $('sheetData', sheet).before(cols);
                        }

                        for (var i = 0; i < colWidth.length; i++) {
                            cols.append('<col min="' + (i + 1) + '" max="' + (i + 1) + '" width="' + colWidth[i].wch + '" customWidth="1"/>');
                        }
                    }
                }
            ],
            columnDefs: [
                {
                    className: "text-center",
                    targets: "_all"
                },
                // Add render function for days_taken column (index 3)
                {
                    targets: [3], // Days Taken column
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

                // Helper function to parse values
                var intVal = function (i) {
                    return typeof i === 'string' ?
                        i.replace(/[^\d.-]/g, '') * 1 :
                        typeof i === 'number' ?
                            i : 0;
                };

                // Calculate total days taken (column 3, index 3)
                var daysTotal = api
                    .column(3, { page: 'current' })
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
                    <td colspan="3" class="text-end" style="font-family: 'Cairo', sans-serif;">
                        <strong>{{ __("Page Totals:") }}</strong>
                    </td>
                    <td class="text-center" style="font-family: 'Cairo', sans-serif; font-weight: bold; color: #007bff;">
                        <strong>${formatNumber(daysTotal)}</strong>
                    </td>
                    <td colspan="6" class="text-center" style="font-family: 'Cairo', sans-serif;">
                        <small class="text-muted">{{ __("Page Records") }}: ${pageTotal} | {{ __("Total Days") }}: ${formatNumber(daysTotal)}</small>
                    </td>
                `);
            },
            drawCallback: function (settings) {
                // Calculate global totals from original data
                var globalTotalDays = {{ $leaves->sum('days_taken') }};
                var globalTotalRecords = {{ $leaves->count() }};
                var info = $(this).closest('.dataTables_wrapper').find('.dataTables_info');

                // Remove any existing total info
                var infoText = info.html();
                if (infoText.includes('|')) {
                    info.html(infoText.split('|')[0]);
                }

                // Add global totals to the info text
                info.html(info.html() +
                    ' <span class="text-primary">| {{ __("Total Days") }}: <strong>' +
                    formatNumber(globalTotalDays) + '</strong></span>' +
                    ' <span class="text-success">| {{ __("Total Records") }}: <strong>' +
                    formatNumber(globalTotalRecords) + '</strong></span>');
            }
        });
    });
</script>

<style>
    #leavesTable thead th {
        text-align: center;
        vertical-align: middle;
        font-family: 'Cairo', sans-serif;
        background-color: #007bff;
        color: #ffffff;
    }

    #leavesTable tbody td {
        font-family: 'Cairo', sans-serif;
    }

    #leavesTable tfoot {
        background-color: #f8f9fa;
        font-weight: bold;
    }

    #leavesTable tfoot td {
        border-top: 2px solid #007bff !important;
        text-align: center;
        vertical-align: middle;
        padding: 10px !important;
        font-family: 'Cairo', sans-serif;
    }

    #leavesTable tfoot td:nth-child(4) {
        color: #007bff;
        font-size: 1.1em;
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

    /* Print-specific styles for footer */
    @media print {
        #leavesTable tfoot {
            background-color: #f8f9fa !important;
            -webkit-print-color-adjust: exact;
        }

        #leavesTable tfoot td {
            border-top: 3px double #007bff !important;
            font-weight: bold !important;
        }

        #leavesTable tfoot td:nth-child(4) {
            color: #007bff !important;
        }
    }

    #leavesTable thead th {
        text-align: center;
        vertical-align: middle;
        font-family: 'Cairo', sans-serif;
        background-color: #007bff;
        color: #ffffff;
    }

    #leavesTable tbody td {
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
