<x-dashboard.main-layout>
    @php
        $rev_locale = app()->getLocale() == 'en' ? 'ar' : 'en';
    @endphp
    <h1 class="mb-3 text-gray-800 h3">{{ __('Upcoming Employee Stages') }}</h1>
    <div class="mb-4 shadow card">
        <div class="py-3 card-header">
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <div class="m-2 card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable-ar" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>{{ __('Employee Name') }}</th>
                                    <th>{{ __('Employee Status') }}</th>
                                    <th>{{ __('Company Name') }}</th>
                                    <th>{{ __('Company Status') }}</th>
                                    <th>{{ __('Company Balance') }}</th>
                                    <th>{{ __('Iqama Type') }}</th>
                                    <th>{{ __('Stage Order') }}</th>
                                    <th>{{ __('Stage Name') }}</th>
                                    <th>{{ __('Stage Description') }}</th>
                                    <th>{{ __('Stage Price') }}</th>
                                    <th>{{ __('Stage Cost') }}</th>
                                    <th>{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($employees as $employee)
                                    <tr>
                                        <td>{{ $employee->name }}</td>
                                        <td>{{ $employee->status }}</td>
                                        <td>{{ $employee->company->getTranslation('name', app()->getLocale()) }}
                                            <br>
                                            {{ $employee->company->getTranslation('name', $rev_locale) }}
                                        </td>
                                        <td>{{ $employee->company->status }}</td>
                                        <td>{{ $employee->company->balance }}</td>
                                        <td>{{ $employee->iqamaType->getTranslation('name', app()->getLocale()) }}
                                            <br>
                                            {{ $employee->iqamaType->getTranslation('name', $rev_locale) }}
                                        </td>
                                        <td>{{ $employee?->upcomingStage?->stage->order }}</td>
                                        <td>{{ $employee?->upcomingStage?->stage->getTranslation('name', app()->getLocale()) }}
                                            <br>
                                            {{ $employee?->upcomingStage?->stage->getTranslation('name', $rev_locale) }}
                                        </td>
                                        <td>
                                            {{ $employee?->upcomingStage?->stage->getTranslation('description', app()->getLocale()) }}<br>
                                            {{ $employee?->upcomingStage?->stage->getTranslation('description', $rev_locale) }}
                                        </td>
                                        <td>{{ $employee?->upcomingStage?->stage->price }}</td>
                                        <td>{{ $employee?->upcomingStage?->stage->cost }}</td>
                                        <per>
{{--                                            @php--}}
{{--                                                dump($employee->upcomingStage);--}}
{{--                                                // Or if it's a collection--}}
{{--                                                // dump($employees->first());--}}
{{--                                            @endphp--}}
                                        </per>
                                        <td>
                                           @can('payStages')
                                                <div class="d-flex">
                                                    <a href="{{ route('admins.employee-stages.get-pay-page', $employee->upcomingStage['id']) }}" class="btn btn-warning">{{ __('Pay') }}</a>
                                                </div>
                                           @endcan
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-dashboard.main-layout>
