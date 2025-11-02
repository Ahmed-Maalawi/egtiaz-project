<x-dashboard.main-layout>
    @php
        $rev_locale = app()->getLocale() == 'en' ? 'ar' : 'en';
    @endphp
    <h1 class="mb-3 text-gray-800 h3">{{ __('Employees') }}</h1>
    <div class="mb-4 shadow card">
        <div class="py-3 card-header">
            <h6 class="m-0 mt-2 font-weight-bold text-primary"></h6>
            <div class="float-right d-inline">
                <a  href="{{ route('admins.employees.create') }}"
                    class="btn btn-primary btn-sm">
                    <i class="fa fa-plus"></i>{{ __('Add New') }}
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable-ar" cellspacing="0">
                    <thead>
                        <tr>
                            <th>{{ __('Serial') }}</th>
                            <th>{{ __('Image') }}</th>
                            <th>{{ __('Passport Image') }}</th>
                            <th>{{ __('Employee Name') }}</th>
                            <th>{{ __('Company Name') }}</th>
                            <th>{{ __('Iqama Type') }}</th>
                            <th>{{ __('Phone') }}</th>
                            <th>{{ __('Address') }}</th>
                            <th>{{ __('Email') }}</th>
                            <th>{{ __('salary') }}</th>
                            <th>{{ __('Passport Number')}}</th>
                            <th>{{ __('Expiration Date')}}</th>
                            <th>{{ __('Gender') }}</th>
                            <th>{{ __('Status') }}</th>
                            <th>{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $i=0; @endphp
                        @foreach ($employees as $employee)
                            <tr data-id="{{ $employee->id }}">
                                <td>{{ ++$i }}</td>
                                @if (!is_null($employee->image))
                                    <td>
                                        <img src="{{ asset('storage/' . $employee->image) }}" width="100px"
                                            alt="" class="">
                                    </td>
                                @else
                                    <td>
                                        <p>{{ __('No Image') }}</p>
                                    </td>
                                @endif
                                @if (!is_null($employee->passport_image))
                                    <td>
                                        <img src="{{ asset('storage/' . $employee->passport_image) }}" width="100px"
                                            alt="" class="">
                                    </td>
                                @else
                                    <td>
                                        <p>{{ __('No Passport Image') }}</p>
                                    </td>
                                @endif
                                <td>{{ $employee->name }}</td>
                                <td>{{ $employee->company->getTranslation('name',app()->getLocale()) }} <br>
                                    {{ $employee->company->getTranslation('name', $rev_locale)}}
                                </td>
                                <td>{{ $employee->iqamaType->getTranslation('name',app()->getLocale()) }} <br>
                                    {{ $employee->iqamaType->getTranslation('name', $rev_locale)}}
                                </td>
                                <td>{{ $employee->phone ?? __('No Phone')}}</td>
                                <td>{{ $employee->address ?? __('No Address')}}</td>
                                <td>{{ $employee->email ?? __('No Email')}}</td>
                                <td>{{ $employee->salary ?? __('No Salary')}}</td>
                                <td>{{ $employee->passport_number ?? __('No Passport Number')}}</td>
                                <td>{{ $employee->expired_date?->format('F j, Y') ?? __('No Expiration Date')}}</td>
                                <td>{{ $employee->gender }}</td>
                                <td>
                                    <input type="checkbox" {{ $employee->status == 'active' ? 'checked' : '' }}
                                        data-id="{{ $employee->id }}"
                                        data-toggle="toggle" data-on="{{ __('Active') }}"
                                        data-off="{{ __('Banned') }}" data-onstyle="success" data-offstyle="danger">
                                </td>

                                <td class="d-flex justify-content-center">
                                    <a href="{{ route('admins.employees.show', $employee->id) }}"
                                        class="mx-1 btn btn-success btn-sm"><i class="fas fa-eye"></i></a>
                                    <a href="{{ route('admins.employees.edit', $employee->id) }}"
                                        class="mx-1 btn btn-primary btn-sm"><i class="fas fa-edit"></i></a>
                                    <form id="delete-form-{{ $employee->id }}"
                                        action="{{ route('admins.employees.destroy', $employee->id) }}" method="post">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="mx-1 btn btn-danger btn-sm"
                                            onclick="confirmDelete({{ $employee->id }}); event.preventDefault();">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>

                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {

            $('input[type="checkbox"]').on('change', function() {
                const checkbox = $(this);
                const id = checkbox.data('id');
                const url = @json(route('admins.employees.toggle', ['id' => ':id'])).replace(':id', id);

                $.ajax({
                    url: url,
                    type: 'PUT',
                    data: {
                        _token: '{{ csrf_token() }}',
                    },
                    success: function(response) {
                        console.log(response.message);
                    },
                    error: function(xhr) {
                        alert(xhr.responseJSON.message || "{{ __('An error occurred while toggling the status.') }}");
                        isManualToggle = true;
                        checkbox.bootstrapToggle('toggle');
                        isManualToggle = false;
                    },
                });
            });
        });

        function confirmDelete(providerId) {
            Swal.fire({
                title: "{{ __('Are you sure?') }}",
                text: "{{ __('You will not be able to revert this!') }}",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "{{ __('Yes, delete it!') }}"
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + providerId).submit();
                }
            });
        }
    </script>


</x-dashboard.main-layout>
