<x-dashboard.main-layout>
    @php
        $rev_locale = app()->getLocale() == 'en' ? 'ar' : 'en';
    @endphp
    <h1 class="mb-3 text-gray-800 h3">{{ __('Companies') }}</h1>
    <div class="mb-4 shadow card">
        <div class="py-3 card-header">
            <h6 class="m-0 mt-2 font-weight-bold text-primary"></h6>
            <div class="float-right d-inline">
                <a href="{{ route('admins.companies.create') }}" class="btn btn-primary btn-sm"><i
                        class="fa fa-plus"></i>{{ __('Add New') }}</a>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable-ar" cellspacing="0">
                    <thead>
                        <tr>
                            <th>{{ __('Serial') }}</th>
                            <th>{{ __('Image') }}</th>
                            <th>{{ __('Banner Image') }}</th>
                            <th>{{ __('Name') }}</th>
                            <th>{{ __('Description') }}</th>
                            <th>{{ __('Balance') }}</th>
                            <th>{{ __('Status') }}</th>
                            <th>{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $i=0; @endphp
                        @foreach ($companies as $company)
                            <tr data-id="{{ $company->id }}">
                                <td>{{ ++$i }}</td>
                                @if (!is_null($company->image))
                                    <td>
                                        <img src="{{ asset('storage/' . $company->image) }}" alt=""
                                            class="" width="150">
                                    </td>
                                @else
                                    <td>
                                        <p>{{ __('No Image') }}</p>
                                    </td>
                                @endif

                                @if (!is_null($company->banner_image))
                                    <td>
                                        <img src="{{ asset('storage/' . $company->banner_image) }}" alt=""
                                            class="" width="150">
                                    </td>
                                @else
                                    <td>
                                        <p>{{ __('No Image') }}</p>
                                    </td>
                                @endif
                                <td>
                                    {{ $company->getTranslation('name',app()->getLocale()) }} <br> {{ $company->getTranslation('name', $rev_locale) }}
                                </td>
                                <td>
                                    {{ strip_tags($company->description) }} <br>
                                    {{ strip_tags($company->getTranslation('description', $rev_locale)) }}
                                </td>
                                <td>{{ $company->balance }} </td>
                                <td>
                                    <input type="checkbox" @if ($company->status == 'active') checked @endif
                                        data-toggle="toggle" data-on="{{ __('Active') }}"
                                        data-off="{{ __('Banned') }}" data-onstyle="success"
                                        data-id = "{{ $company->id }}" data-offstyle="danger">
                                </td>
                                <td class="d-flex justify-content-center">
                                    <a href="{{ route('admins.companies.edit', $company->id) }}"
                                        class="mx-1 btn btn-primary btn-sm"><i class="fas fa-edit"></i></a>
                                    <form id="delete-form-{{ $company->id }}"
                                        action="{{ route('admins.companies.destroy', $company->id) }}" method="POST"
                                        style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="mx-1 btn btn-danger btn-sm"
                                            onclick="confirmDelete({{ $company->id }}); event.preventDefault();">
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
        function confirmDelete(companyId) {
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
                    document.getElementById('delete-form-' + companyId).submit();
                }
            });
        }

        $(document).ready(function() {
            $('input[type="checkbox"]').on('change', function() {

                const checkbox = $(this);
                const id = checkbox.data('id');
                const url = @json(route('admins.companies.toggle', ['id' => ':id'])).replace(':id', id);

                $.ajax({
                    url: url,
                    type: 'PUT',
                    data: {
                        _token: '{{ csrf_token() }}',
                    },
                    success: function(response) {},
                    error: function(xhr) {},
                });
            });
        });
    </script>


</x-dashboard.main-layout>
