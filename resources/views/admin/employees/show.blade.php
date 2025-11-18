<style>
    .file-card {
        transition: transform 0.2s;
    }
    .file-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
    .nav-tabs .nav-link {
        color: #6c757d;
        font-weight: 500;
    }
    .nav-tabs .nav-link.active {
        color: #495057;
        font-weight: 600;
    }
    .table td {
        vertical-align: middle;
    }
</style>

<x-dashboard.main-layout>
    @php
        $rev_locale = app()->getLocale() == 'en' ? 'ar' : 'en';
    @endphp

    <h1 class="mb-3 text-gray-800 h3">{{ __('Employee Details') }}: {{ $employee->name }}</h1>

    <div class="row">
        <div class="col-md-12">
            <div class="mb-4 shadow card">
                <div class="py-3 card-header d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">{{ __('Employee Information') }}</h6>
                    <div class="d-inline">
                        <a href="{{ route('admins.employees.index') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-arrow-left"></i> {{ __('Back') }}
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Tabs Navigation -->
                    <ul class="nav nav-tabs" id="employeeTabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="personal-tab" data-toggle="tab" href="#personal" role="tab">
                                <i class="fas fa-user"></i> {{ __('Personal Information') }}
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="employment-tab" data-toggle="tab" href="#employment" role="tab">
                                <i class="fas fa-briefcase"></i> {{ __('Employment') }}
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="documents-tab" data-toggle="tab" href="#documents" role="tab">
                                <i class="fas fa-file-alt"></i> {{ __('Documents') }}
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="stages-tab" data-toggle="tab" href="#stages" role="tab">
                                <i class="fas fa-tasks"></i> {{ __('Stages') }}
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="financial-tab" data-toggle="tab" href="#financial" role="tab">
                                <i class="fas fa-chart-line"></i> {{ __('Financial') }}
                            </a>
                        </li>
                    </ul>

                    <!-- Tab Content -->
                    <div class="tab-content mt-4" id="employeeTabsContent">

                        <!-- Personal Information Tab -->
                        <div class="tab-pane fade show active" id="personal" role="tabpanel">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped">
                                            <tr>
                                                <td width="40%" class="font-weight-bold">{{ __('Full Name') }}</td>
                                                <td>{{ $employee->name }}</td>
                                            </tr>
                                            <tr>
                                                <td class="font-weight-bold">{{ __('Email') }}</td>
                                                <td>{{ $employee->email ?? __('N/A') }}</td>
                                            </tr>
                                            <tr>
                                                <td class="font-weight-bold">{{ __('Phone') }}</td>
                                                <td>{{ $employee->phone ?? __('N/A') }}</td>
                                            </tr>
                                            <tr>
                                                <td class="font-weight-bold">{{ __('Gender') }}</td>
                                                <td>{{ $employee->gender }}</td>
                                            </tr>
                                            <tr>
                                                <td class="font-weight-bold">{{ __('Address') }}</td>
                                                <td>{{ $employee->address ?? __('N/A') }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped">
                                            <tr>
                                                <td width="40%" class="font-weight-bold">{{ __('Passport Number') }}</td>
                                                <td>{{ $employee->passport_number ?? __('N/A') }}</td>
                                            </tr>
                                            <tr>
                                                <td class="font-weight-bold">{{ __('Passport Expiry') }}</td>
                                                <td>
                                                    @if ($employee->expired_date)
                                                        {{ \Carbon\Carbon::parse($employee->expired_date)->format('d M, Y') }}
                                                        @if ($employee->expired_date->isPast())
                                                            <span class="badge badge-danger ml-2">{{ __('Expired') }}</span>
                                                        @elseif ($employee->expired_date->diffInDays(now()) <= 30)
                                                            <span class="badge badge-warning ml-2">{{ __('Expiring Soon') }}</span>
                                                        @else
                                                            <span class="badge badge-success ml-2">{{ __('Valid') }}</span>
                                                        @endif
                                                    @else
                                                        {{ __('N/A') }}
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="font-weight-bold">{{ __('Joined Date') }}</td>
                                                <td>{{ \Carbon\Carbon::parse($employee->created_at)->format('d M, Y h:i A') }}</td>
                                            </tr>
                                            <tr>
                                                <td class="font-weight-bold">{{ __('Last Updated') }}</td>
                                                <td>{{ \Carbon\Carbon::parse($employee->updated_at)->format('d M, Y h:i A') }}</td>
                                            </tr>
                                            <tr>
                                                <td class="font-weight-bold">{{ __('Status') }}</td>
                                                <td>
                                                    @if($employee->status == 'active')
                                                        <span class="badge badge-success">{{ __('Active') }}</span>
                                                    @else
                                                        <span class="badge badge-danger">{{ __('Inactive') }}</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Employment Information Tab -->
                        <div class="tab-pane fade" id="employment" role="tabpanel">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped">
                                            <tr>
                                                <td width="40%" class="font-weight-bold">{{ __('Company') }}</td>
                                                <td>
                                                    {{ $employee->company->name ?? __('N/A') }}
                                                    @if($employee->company)
                                                        <a href="{{ route('admins.companies.show', $employee->company->id) }}"
                                                           class="btn btn-sm btn-outline-primary ml-2">
                                                            <i class="fas fa-external-link-alt"></i>
                                                        </a>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="font-weight-bold">{{ __('Iqama Type') }}</td>
                                                <td>{{ $employee->iqamaType->name ?? __('N/A') }}</td>
                                            </tr>
{{--                                            <tr>--}}
{{--                                                <td class="font-weight-bold">{{ __('Salary') }}</td>--}}
{{--                                                <td>--}}
{{--                                                    @if($employee->salary)--}}
{{--                                                        {{ number_format($employee->salary, 2) }} {{ __('SAR') }}--}}
{{--                                                        @if($employee->current_month_salary)--}}
{{--                                                            <span class="badge badge-success ml-2">{{ __('Paid this month') }}</span>--}}
{{--                                                        @else--}}
{{--                                                            <span class="badge badge-warning ml-2">{{ __('Pending payment') }}</span>--}}
{{--                                                        @endif--}}
{{--                                                    @else--}}
{{--                                                        {{ __('N/A') }}--}}
{{--                                                    @endif--}}
{{--                                                </td>--}}
{{--                                            </tr>--}}
                                        </table>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped">
                                            <tr>
                                                <td width="40%" class="font-weight-bold">{{ __('Total Files') }}</td>
                                                <td>
                                                    <span class="badge badge-info">{{ $employee->files->count() }}</span>
                                                    @if($employee->files->count() > 0)
                                                        <small class="text-muted ml-2">
                                                            ({{ $employee->getTotalFilesSize() }})
                                                        </small>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="font-weight-bold">{{ __('Total Stages') }}</td>
                                                <td>
                                                    <span class="badge badge-primary">{{ $employee->employeeStages->count() }}</span>
                                                    <small class="text-muted ml-2">
                                                        {{ $employee->completedStages()->count() }} {{ __('completed') }}
                                                    </small>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="font-weight-bold">{{ __('Current Stage') }}</td>
                                                <td>
                                                    @if($employee->upcomingStage)
                                                        <span class="font-weight-bold">{{ $employee->upcomingStage->stage->name ?? __('N/A') }}</span>
                                                        <span class="badge badge-warning ml-2">{{ __('Pending') }}</span>
                                                    @elseif($employee->checkAllPapersCompleted())
                                                        <span class="badge badge-success">{{ __('All Stages Completed') }}</span>
                                                    @else
                                                        <span class="badge badge-secondary">{{ __('No Active Stage') }}</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Documents Tab -->
                        <div class="tab-pane fade" id="documents" role="tabpanel">
                            <div class="row">
                                <div class="col-md-6">
                                    <h6 class="font-weight-bold mb-3">{{ __('Profile Image') }}</h6>
                                    <div class="text-center">
                                        @if ($employee->image)
                                            <img src="{{ asset('storage/' . $employee->image) }}"
                                                 class="img-fluid rounded shadow"
                                                 style="max-height: 300px; max-width: 100%;">
                                            <div class="mt-2">
                                                <a href="{{ asset('storage/' . $employee->image) }}"
                                                   target="_blank"
                                                   class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-external-link-alt"></i> {{ __('View Full Size') }}
                                                </a>
                                            </div>
                                        @else
                                            <div class="text-muted py-5 border rounded">
                                                <i class="fas fa-user-slash fa-3x mb-3"></i>
                                                <p>{{ __('No Profile Image') }}</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <h6 class="font-weight-bold mb-3">{{ __('Passport Image') }}</h6>
                                    <div class="text-center">
                                        @if ($employee->passport_image)
                                            <img src="{{ asset('storage/' . $employee->passport_image) }}"
                                                 class="img-fluid rounded shadow"
                                                 style="max-height: 300px; max-width: 100%;">
                                            <div class="mt-2">
                                                <a href="{{ asset('storage/' . $employee->passport_image) }}"
                                                   target="_blank"
                                                   class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-external-link-alt"></i> {{ __('View Full Size') }}
                                                </a>
                                            </div>
                                        @else
                                            <div class="text-muted py-5 border rounded">
                                                <i class="fas fa-passport fa-3x mb-3"></i>
                                                <p>{{ __('No Passport Image') }}</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            @if($employee->files->count() > 0)
                                <div class="mt-5">
                                    <h6 class="font-weight-bold mb-3">{{ __('Additional Files') }}</h6>
                                    <div class="row">
                                        @foreach($employee->files as $file)
                                            <div class="col-md-4 mb-3">
                                                <div class="card file-card">
                                                    <div class="card-body text-center">
                                                        <i class="fas fa-file fa-2x text-primary mb-2"></i>
                                                        <h6 class="card-title">{{ basename($file->path) }}</h6>
                                                        <p class="text-muted small">{{ $file->getFormattedSize() }}</p>
                                                        <a href="{{ $file->file_url }}"
                                                           target="_blank"
                                                           class="btn btn-sm btn-outline-primary">
                                                            <i class="fas fa-download"></i> {{ __('Download') }}
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Stages Tab -->
                        <div class="tab-pane fade" id="stages" role="tabpanel">
                            @if($employee->employeeStages->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped">
                                        <thead class="thead-dark">
                                        <tr>
                                            <th>{{ __('Stage Name') }}</th>
                                            <th>{{ __('Status') }}</th>
{{--                                            <th>{{ __('Payment Status') }}</th>--}}
                                            <th>{{ __('Amount Paid') }}</th>
                                            <th>{{ __('Cost') }}</th>
                                            <th>{{ __('Profit') }}</th>
                                            <th>{{ __('Completed At') }}</th>
                                            <th>{{ __('Actions') }}</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($employee->employeeStages->sortBy('stage.order') as $employeeStage)
                                            <tr>
                                                <td class="font-weight-bold">{{ $employeeStage->stage->name ?? __('N/A') }}</td>
                                                <td>
                                                    @if($employeeStage->status == 'completed')
                                                        <span class="badge badge-success">{{ __('Completed') }}</span>
                                                    @else
                                                        <span class="badge badge-warning">{{ __('Pending') }}</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($employeeStage->amount_paid)
                                                        {{ number_format($employeeStage->amount_paid, 2) . ' ' . __('SAR')}}
                                                    @else
                                                        {{ __('N/A') }}
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($employeeStage->amount_cost)
                                                        {{ number_format($employeeStage->amount_cost, 2) }} SAR
                                                    @else
                                                        {{ __('N/A') }}
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($employeeStage->amount_paid && $employeeStage->amount_cost)
                                                        <span class="{{ $employeeStage->profit >= 0 ? 'text-success' : 'text-danger' }}">
                                                                {{ number_format($employeeStage->profit, 2) }} SAR
                                                            </span>
                                                    @else
                                                        {{ __('N/A') }}
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($employeeStage->completed_at)
                                                        {{ \Carbon\Carbon::parse($employeeStage->completed_at)->format('d M, Y') }}
                                                    @else
                                                        {{ __('N/A') }}
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($employeeStage->files->count() > 0)
                                                        <button class="btn btn-sm btn-info"
                                                                data-toggle="tooltip"
                                                                title="{{ __('View Files') }}">
                                                            <i class="fas fa-file"></i> ({{ $employeeStage->files->count() }})
                                                        </button>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-center py-4 text-muted">
                                    <i class="fas fa-tasks fa-3x mb-3"></i>
                                    <p>{{ __('No stages assigned to this employee yet.') }}</p>
                                </div>
                            @endif
                        </div>

                        <!-- Financial Tab -->
                        <div class="tab-pane fade" id="financial" role="tabpanel">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="card bg-light">
                                        <div class="card-header">
                                            <h6 class="mb-0 font-weight-bold">{{ __('Stage Financial Summary') }}</h6>
                                        </div>
                                        <div class="card-body">
                                            <table class="table table-sm">
                                                <tr>
                                                    <td class="font-weight-bold">{{ __('Total Cost') }}</td>
                                                    <td class="text-danger font-weight-bold">
                                                        {{ number_format($employee->total_cost, 2) . __('SAR') }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="font-weight-bold">{{ __('Total Price') }}</td>
                                                    <td class="text-success font-weight-bold">
                                                        {{ number_format($employee->total_price, 2) . __('SAR') }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="font-weight-bold">{{ __('Total Profit') }}</td>
                                                    <td class="{{ $employee->total_profit >= 0 ? 'text-success' : 'text-danger' }} font-weight-bold">
                                                        {{ number_format($employee->total_profit, 2) . __('SAR')}}
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="mt-4 pt-3 border-top">
                        <div class="btn-group" role="group">
                            <a href="{{ route('admins.employees.edit', $employee->id) }}" class="btn btn-warning">
                                <i class="fas fa-edit"></i> {{ __('Edit Employee') }}
                            </a>

                            @if($employee->files->count() > 0)
                                <a href="{{ route('admins.employees.files', $employee->id) }}" class="btn btn-info ml-2">
                                    <i class="fas fa-folder-open"></i> {{ __('View Files') }} ({{ $employee->files->count() }})
                                </a>
                            @endif

                            <a type="button" class="btn btn-success ml-2" href="{{ route('admins.employees.download-pdf', $employee->id) }}">
                                <i class="fas fa-print"></i> {{ __('Print') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-dashboard.main-layout>

<script>
    // Initialize tooltips
    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    })

    // Tab persistence
    $('#employeeTabs a').on('click', function (e) {
        e.preventDefault()
        $(this).tab('show')
    })
</script>
