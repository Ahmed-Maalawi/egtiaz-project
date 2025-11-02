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
                        <th>{{ __('Expired Date') }}</th>
                        <th>{{ __('Status') }}</th>
                        <th>{{ __('Leaves') }}</th>
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
                            <td>{{ $employee->leaves()->count() }}</td>
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
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <script>
        $(function () {
            // Initialize DataTable
            const table = $('#employeesTable').DataTable({
                dom: 'Bfrtip',
                pageLength: 25, // default perPage
                lengthChange: false, // we’ll use our custom dropdown
                buttons: [
                    {
                        extend: 'print',
                        text: '<i class="fa fa-print"></i> {{ __("Print") }}',
                        className: 'btn btn-primary'
                    },
                    {
                        extend: 'excelHtml5',
                        text: '<i class="fa fa-file-excel"></i> {{ __("Excel") }}',
                        className: 'btn btn-success'
                    }
                ]
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
