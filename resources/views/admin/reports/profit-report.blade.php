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
            $('#profitTable').DataTable({
                pageLength: 10,
                scrollX: true,
                dom: 'Blfrtip',
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
        });
    </script>
</x-dashboard.main-layout>
