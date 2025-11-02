<x-dashboard.main-layout>
    @php
        $rev_locale = app()->getLocale() == 'en' ? 'ar' : 'en';
    @endphp
    <h1 class="mb-3 text-gray-800 h3">{{ __('Leave Type') }}</h1>
    <div class="mb-4 shadow card">
        <div class="py-3 card-header">
            <h6 class="m-0 mt-2 font-weight-bold text-primary"></h6>
            <div class="float-right d-inline">
                <a  href="{{ route('admins.hr.leaveType.create') }}"
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
                        <th>{{ __('name') }}</th>
                        <th>{{ __('description') }}</th>
                        <th>{{ __('Status') }}</th>
                        <th>{{ __('Actions') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @php $i=0; @endphp
                    @foreach ($leaveTypes as $leave)
                        <tr data-id="{{ $leave->id }}">
                            <td>{{ ++$i }}</td>
                            <td>{{ $leave->getTranslation('name', app()->getLocale()) ?? __('Not found') }}</td>
                            <td>{{ $leave->getTranslation('description', app()->getLocale()) ?? __('Not found')  }}</td>
                            <td>
                                @if($leave->active)
                                        <span class="badge bg-success text-white">{{ __('active') }}</span>
                                @else
                                        <span class="badge bg-danger text-white">{{ __('unactive') }}</span>
                                @endif
                            </td>

                            <td class="d-flex justify-content-center">
                                <a href="{{ route('admins.hr.leaveType.show', $leave->id) }}"
                                   class="mx-1 btn btn-success btn-sm"><i class="fas fa-eye"></i></a>
                                <a href="{{ route('admins.hr.leaveType.edit', $leave->id) }}"
                                   class="mx-1 btn btn-primary btn-sm"><i class="fas fa-edit"></i></a>
                                <form id="delete-form-{{ $leave->id }}"
                                      action="{{ route('admins.hr.leaveType.destroy', $leave->id) }}" method="post">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="mx-1 btn btn-danger btn-sm"
                                            onclick="confirmDelete({{ $leave->id }}); event.preventDefault();">
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
