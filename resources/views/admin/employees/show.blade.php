<x-dashboard.main-layout>
    @php
        $rev_locale = app()->getLocale() == 'en' ? 'ar' : 'en';
    @endphp

    <h1 class="mb-3 text-gray-800 h3">{{ __('Employee') }} {{ $employee->name }} {{ __('Details') }}</h1>

    <div class="row">
        <div class="col-md-12">
            <div class="mb-4 shadow card">
                <div class="py-3 card-header">
                    <h6 class="m-0 mt-2 font-weight-bold text-primary">{{ __('Employee Information') }}</h6>
                    <div class="float-right d-inline">
                        <a href="{{ route('admins.employees.index') }}" class="btn btn-primary btn-sm">
                            {{ __('Back') }}
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <!-- Personal Information -->
                            <tr>
                                <td width="30%">{{ __('Employee Name') }}</td>
                                <td>{{ $employee->name }}</td>
                            </tr>

                            <tr>
                                <td>{{ __('Email') }}</td>
                                <td>{{ $employee->email ?? __('N/A') }}</td>
                            </tr>

                            <tr>
                                <td>{{ __('Phone') }}</td>
                                <td>{{ $employee->phone ?? __('N/A') }}</td>
                            </tr>

                            <tr>
                                <td>{{ __('Salary') }}</td>
                                <td>{{ $employee->salary ? number_format($employee->salary, 2) . ' ' . __('SAR') : __('N/A') }}</td>
                            </tr>

                            <tr>
                                <td>{{ __('Address') }}</td>
                                <td>{{ $employee->address ?? __('N/A') }}</td>
                            </tr>

                            <tr>
                                <td>{{ __('Gender') }}</td>
                                <td>{{ $employee->gender }}</td>
                            </tr>

                            <!-- Images -->
                            <tr>
                                <td>{{ __('Profile Image') }}</td>
                                <td>
                                    @if ($employee->image)
                                        <img src="{{ asset('storage/' . $employee->image) }}" class="w_100" style="max-width: 200px;">
                                    @else
                                        <p>{{ __('No Image') }}</p>
                                    @endif
                                </td>
                            </tr>

                            <tr>
                                <td>{{ __('Passport Image') }}</td>
                                <td>
                                    @if ($employee->passport_image)
                                        <img src="{{ asset('storage/' . $employee->passport_image) }}" class="w_100" style="max-width: 200px;">
                                    @else
                                        <p>{{ __('No Passport Image') }}</p>
                                    @endif
                                </td>
                            </tr>

                            <!-- Passport Information -->
                            <tr>
                                <td>{{ __('Passport Number') }}</td>
                                <td>{{ $employee->passport_number ?? __('N/A') }}</td>
                            </tr>

                            <tr>
                                <td>{{ __('Expired Date') }}</td>
                                <td>
                                    @if ($employee->expired_date)
                                        {{ \Carbon\Carbon::parse($employee->expired_date)->format('d M, Y') }}
                                        @if ($employee->expired_date->isPast())
                                            <span class="badge badge-danger ml-2">{{ __('Expired') }}</span>
                                        @elseif ($employee->expired_date->diffInDays(now()) <= 30)
                                            <span class="badge badge-warning ml-2">{{ __('Expiring Soon') }}</span>
                                        @endif
                                    @else
                                        {{ __('N/A') }}
                                    @endif
                                </td>
                            </tr>

                            <!-- Employment Information -->
                            <tr>
                                <td>{{ __('Status') }}</td>
                                <td>
                                    @if($employee->status == 'active')
                                        <span class="badge badge-success">{{ __('Active') }}</span>
                                    @else
                                        <span class="badge badge-danger">{{ __('Inactive') }}</span>
                                    @endif
                                </td>
                            </tr>

                            <tr>
                                <td>{{ __('Company') }}</td>
                                <td>{{ $employee->company->name ?? __('N/A') }}</td>
                            </tr>

                            <tr>
                                <td>{{ __('Iqama Type') }}</td>
                                <td>{{ $employee->iqamaType->name ?? __('N/A') }}</td>
                            </tr>

                            <!-- Stage Information -->
                            <tr>
                                <td>{{ __('Current Stage') }}</td>
                                <td>
                                    @if($employee->upcomingStage)
                                        {{ $employee->upcomingStage->stage->name ?? __('N/A') }}
                                        <span class="badge badge-info ml-2">{{ __('Pending') }}</span>
                                    @else
                                        <span class="badge badge-success">{{ __('Completed') }}</span>
                                    @endif
                                </td>
                            </tr>

                            <!-- Additional Information -->
                            <tr>
                                <td>{{ __('Files Count') }}</td>
                                <td>{{ $employee->files->count() }}</td>
                            </tr>

                            <tr>
                                <td>{{ __('Total Stages') }}</td>
                                <td>{{ $employee->employeeStages->count() }}</td>
                            </tr>

                            <tr>
                                <td>{{ __('Joined At') }}</td>
                                <td>{{ \Carbon\Carbon::parse($employee->created_at)->format('d M, Y h:i A') }}</td>
                            </tr>

                            <tr>
                                <td>{{ __('Last Updated') }}</td>
                                <td>{{ \Carbon\Carbon::parse($employee->updated_at)->format('d M, Y h:i A') }}</td>
                            </tr>
                        </table>
                    </div>

                    <!-- Action Buttons -->
                    <div class="mt-4">
                        <a href="{{ route('admins.employees.edit', $employee->id) }}" class="btn btn-warning btn-sm">
                            {{ __('Edit Employee') }}
                        </a>

                        @if($employee->files->count() > 0)
                            <a href="{{ route('admins.employees.files', $employee->id) }}" class="btn btn-info btn-sm ml-2">
                                {{ __('View Files') }} ({{ $employee->files->count() }})
                            </a>
                        @endif

{{--                        @if($employee->employeeStages->count() > 0)--}}
{{--                            <a href="{{ route('admins.stages.stages', $employee->id) }}" class="btn btn-secondary btn-sm ml-2">--}}
{{--                                {{ __('View Stages') }}--}}
{{--                            </a>--}}
{{--                        @endif--}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-dashboard.main-layout>
