<div>
    @php
        use Carbon\Carbon;
        $rev_locale = app()->getLocale() == 'ar' ? 'en' : 'ar';
    @endphp

    <div class="row">
        <div class="m-3 form-group w-100" wire:ignore>
            <label for="select_company" class="d-block nt-semibold d-mb-2 ">{{ __('Select Company') }}</label>
            <select id="select_company" class="w-full p-2 border rounded">
            </select>
        </div>

        <div class="m-3 form-group w-100" wire:ignore>
            <label for="select_employee" class="d-block nt-semibold d-mb-2 ">{{ __('Select Employee') }}</label>
            <select id="select_employee" wire:ignore class="w-full p-2 border rounded">
            </select>
        </div>
    </div>

    @if ($employeeStages)
        <div class="row">
            <div class="mb-3 col-md-3">
                <div class="py-2 shadow-sm card border-left-primary h-100">
                    <div class="card-body">
                        <div class="mb-1 text-xs font-weight-bold text-primary text-uppercase">
                            {{ __('Employee Name') }}
                        </div>
                        <div class="mb-0 text-gray-800 h5 font-weight-bold">
                            {{ $employeeStages[0]->employee->name ?? __('Not Selected') }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="mb-3 col-md-3">
                <div class="py-2 shadow-sm card border-left-primary h-100">
                    <div class="card-body">
                        <div class="mb-1 text-xs font-weight-bold text-primary text-uppercase">
                            {{ __('Employee Status') }}
                        </div>
                        <div class="mb-0 text-gray-800 h5 font-weight-bold">
                            {{ $employeeStages[0]->employee->status ?? __('Not Selected') }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="mb-3 col-md-3">
                <div class="py-2 shadow-sm card border-left-info h-100">
                    <div class="card-body">
                        <div class="mb-1 text-xs font-weight-bold text-info text-uppercase">
                            {{ __('Employee Email') }}
                        </div>
                        <div class="mb-0 text-gray-800 h5 font-weight-bold">
                            {{ $employeeStages[0]->employee->email ?? __('N/A') }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="mb-3 col-md-3">
                <div class="py-2 shadow-sm card border-left-success h-100">
                    <div class="card-body">
                        <div class="mb-1 text-xs font-weight-bold text-success text-uppercase">
                            {{ __('Company Name') }}
                        </div>
                        <div class="mb-0 text-gray-800 h5 font-weight-bold">
                            {{ $employeeStages[0]->employee->company->getTranslation('name', app()->getLocale()) ?? __('Not Selected') }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="mb-3 col-md-3">
                <div class="py-2 shadow-sm card border-left-success h-100">
                    <div class="card-body">
                        <div class="mb-1 text-xs font-weight-bold text-success text-uppercase">
                            {{ __('Company Status') }}
                        </div>
                        <div class="mb-0 text-gray-800 h5 font-weight-bold">
                            {{ $employeeStages[0]->employee->company->status ?? __('Not Selected') }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="mb-3 col-md-3">
                <div class="py-2 shadow-sm card border-left-warning h-100">
                    <div class="card-body">
                        <div class="mb-1 text-xs font-weight-bold text-warning text-uppercase">
                            {{ __('Company Balance') }}
                        </div>
                        <div class="mb-0 text-gray-800 h5 font-weight-bold">
                            {{ $employeeStages[0]->employee->company->balance }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- <div class="m-2 card-body">
            <div class="table-responsive"> --}}
        <table class="table table-bordered" width="100%" cellspacing="0">
            <thead>
            <tr>
                <th>{{ __('Order') }}</th>
                <th>{{ __('Stage Name') }}</th>
                <th>{{ __('Stage Description') }}</th>
                <th>{{ __('Stage Price') }}</th>
                <th>{{ __('Estimated Days') }}</th>
                <th>{{ __('Completed At') }}</th>
                <th>{{ __('Registered By') }}</th>
                <th>{{ __('Expired At') }}</th>
                <th>{{ __('Status') }}</th>
                <th>{{ __('Actions') }}</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($employeeStages as $employeeStage)
                <tr
                    class="
                            @if ($employeeStage->status === 'pending') status-pending
                            @elseif($employeeStage->status === 'on_running') status-running
                            @elseif($employeeStage->status === 'completed') status-completed @endif
                        ">
                    <td>{{ $employeeStage->stage->order }}</td>
                    <td>
                        {{ $employeeStage->stage->getTranslation('name', app()->getLocale()) }}<br>
                        {{ $employeeStage->stage->getTranslation('name', $rev_locale) }}
                    </td>
                    <td>
                        {{ $employeeStage->stage->getTranslation('description', app()->getLocale()) }}
                        <br>
                        {{ $employeeStage->stage->getTranslation('description', $rev_locale) }}
                    </td>
                    <td>{{ $employeeStage->stage->price }}</td>
                    @if ($employeeStage->stage->estimated_time_in_days)
                        <td>{{ $employeeStage->stage->estimated_time_in_days . ' ' . __('Days') }}</td>
                    @else
                        <td>{{ __('Not Specified') }}</td>
                    @endif
                    <td>
                        @if ($employeeStage->completed_at)
                            {{ Carbon::createFromTimestamp($employeeStage->completed_at)->format('Y-m-d') }}
                        @else
                            {{ __('Not Completed Yet') }}
                        @endif
                    </td>
                    <td>
                        @if ($employeeStage->done_by)
                            {{ $employeeStage->doneBy->name }}
                        @else
                            {{ __('Not Completed Yet') }}
                        @endif
                    </td>
                    <td>
                        @if ($employeeStage->expired_at)
                            {{ $employeeStage->expired_at->format('Y-m-d') }}
                        @else
                            {{ __('Not Specified Or Not Completed') }}
                        @endif
                    </td>
                    <td>
                        @if ($employeeStage->status == 'pending')
                            <span class="px-2 py-1 text-white rounded bg-warning">
                                    {{ __('Pending') }}
                            </span>
                        @elseif ($employeeStage->status == 'in_progress')
                            <span class="px-2 py-1 text-white rounded bg-info">
                                    {{ __('In Progress') }}
                            </span>
                        @elseif ($employeeStage->status == 'completed')
                            <span class="px-2 py-1 text-white rounded bg-success">
                                    {{ __('Completed') }}
                            </span>
                        @endif
                    </td>
                    <td>
                        <div class="d-flex">
{{--                            <button type="button" class="btn btn-warning">{{ __('Update') }}</button>--}}
                            @if($employeeStage && $employeeStage->status !== 'completed')
                                <a type="button" class="btn btn-primary text-white mx-1"
                                   href="{{ route('admins.employee-stages.get-pay-page', ['id' => $employeeStage->id]) }}">
                                    {{ __('Pay') }}
                                </a>
                            @endif
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        {{-- </div>
        </div> --}}
        <div class="py-2 d-flex justify-content-center">
            {{--             {{ $employeeStages->links() }}--}}
        </div>
    @else
        <div class="alert alert-info">
            {{ __('Select An Employee.') }}
        </div>
    @endif
    <style>
        .status-pending {
            background-color: #fff3cd; /* Light yellow */
            color: #856404;
        }

        .status-running {
            background-color: #d1ecf1; /* Light blue */
            color: #0c5460;
        }

        .status-completed {
            background-color: #d4edda; /* Light green */
            color: #155724;
        }

    </style>

    <script>
        $(document).ready(function () {
            selectCompany();
            selectEmployee();
        });


        function selectEmployee() {
            $('#select_employee').select2({
                placeholder: "{{ __('Type A Employee Name, Email or Phone') }}",
                width: "100%",
                ajax: {
                    url: "{{ route('admins.employees.search') }}",
                    dataType: 'json',
                    delay: 500,
                    data: function (params) {
                        return {
                            q: params.term,
                            company_id: $('#select_company').val(),
                        };
                    },
                    processResults: function (data) {
                        return {
                            results: data.map(employee => ({
                                id: employee.id,
                                text: employee.name,
                            }))
                        };
                    },
                    cache: true,
                },
                allowClear: true

            })
        }

        function selectCompany() {
            $('#select_company').select2({
                placeholder: "{{ __('Type A Company Name') }}",
                width: "100%",
                ajax: {
                    url: "{{ route('admins.companies.search') }}",
                    dataType: 'json',
                    delay: 500,
                    data: function (params) {
                        return {
                            q: params.term
                        };
                    },
                    processResults: function (data) {
                        return {
                            results: data.map(company => ({
                                id: company.id,
                                text: company.name,
                            }))
                        };
                    },
                    cache: true,
                }
            })
        }

        $('#select_employee').on('change', function (e) {
        @this.set('selectedEmployee', $(this).val())
            ;
        @this.call('loadEmployeeStages')
            ;
        });
    </script>

</div>
