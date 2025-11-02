<x-dashboard.main-layout>
    @php
        $rev_locale = app()->getLocale() == 'en' ? 'ar' : 'en';
    @endphp
    <h1 class="mb-3 text-gray-800 h3">{{ __('Iqama Types') }}</h1>
    <div class="mb-4 shadow card">
        <div class="py-3 card-header">
            <h6 class="m-0 mt-2 font-weight-bold text-primary"></h6>
            <div class="float-right d-inline">
                <a href="{{ route('admins.types.create') }}" class="btn btn-primary btn-sm"><i
                        class="fa fa-plus"></i>{{ __('Add New') }}</a>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable-ar" cellspacing="0">
                    <thead>
                        <tr>
                            <th>{{ __('Serial') }}</th>
                            <th>{{ __('Name') }}</th>
                            <th>{{ __('Description') }}</th>
                            <th>{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $i=0; @endphp
                        @foreach ($types as $type)
                            <tr data-id="{{ $type->id }}">
                                <td>{{ ++$i }}</td>
                                <td>
                                    {{ $type->getTranslation('name', app()->getLocale()) }} <br> {{ $type->getTranslation('name', $rev_locale) }}
                                </td>
                                <td>
                                    {{ strip_tags($type->description) }} <br>
                                    {{ strip_tags($type->getTranslation('description', $rev_locale)) }}
                                </td>
                                <td class="d-flex justify-content-center">
                                    <a href="{{ route('admins.types.edit', $type->id) }}"
                                        class="mx-1 btn btn-primary btn-sm"><i class="fas fa-edit"></i></a>
                                    <form id="delete-form-{{ $type->id }}"
                                        action="{{ route('admins.types.destroy', $type->id) }}" method="POST"
                                        style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="mx-1 btn btn-danger btn-sm"
                                            onclick="confirmDelete({{ $type->id }}); event.preventDefault();">
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
    </script>


</x-dashboard.main-layout>
