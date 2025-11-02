<x-dashboard.main-layout>
    @php
        $rev_locale = app()->getLocale() == 'en' ? 'ar' : 'en';
    @endphp
    <div class="bottom-0 p-3 toast-container position-fixed end-0" style="z-index: 9999">
        <div id="successToast" data-bs-autohide="true" class="text-white border-0 toast align-items-center bg-success"
            role="alert">
            <div class="d-flex">
                <div class="toast-body">
                    {{ __('Status updated successfully') }}
                </div>
                {{-- <button type="button" class="m-auto btn-close btn-close-white me-2" data-bs-dismiss="toast"></button> --}}
            </div>
        </div>
    </div>
    <h1 class="mb-3 text-gray-800 h3">{{ __('Main Categories') }}</h1>
    <div class="mb-4 shadow card">
        <div class="py-3 card-header">
            <h6 class="m-0 mt-2 font-weight-bold text-primary"></h6>
            <div class="float-right d-inline">
                <a href="{{ route('admins.main-categories.create') }}" class="btn btn-primary btn-sm"><i
                        class="fa fa-plus"></i>{{ __('Add New') }}</a>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>{{ __('Serial') }}</th>
                            <th>{{ __('Name') }}</th>
                            <th>{{ __('Description') }}</th>
                            <th>{{ __('Image') }}</th>
                            <th>{{ __('Status') }}</th>
                            <th>{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $i=0; @endphp
                        @foreach ($categories as $category)
                            <tr data-aos="fade-up" data-id="{{ $category->id }}">
                                <td>{{ ++$i }}</td>
                                <td>{{ $category->name }} <br> {{ $category->getTranslation('name',$rev_locale) }}</td>
                                <td>{{ strip_tags($category->description) }} <br> {{ strip_tags($category->getTranslation('description',$rev_locale)) }} </td>
                                @if (!is_null($category->image))
                                    <td><img src="{{ asset('storage/' . $category->image) }}" alt=""
                                            class="w_200"></td>
                                @else
                                    <td>
                                        <p>{{ __('No image') }}</p>
                                    </td>
                                @endif
                                <td>
                                    <input type="checkbox" @if ($category->status == 'active') checked @endif
                                        data-toggle="toggle" data-on="{{ __('Active') }}"
                                        data-off="{{ __('Pending') }}" data-onstyle="success"
                                        data-id = "{{ $category->id }}" data-offstyle="danger">
                                </td>
                                <td class="d-flex justify-content-center">
                                    <a href="{{ route('admins.main-categories.edit', $category->id) }}"
                                        class="mx-1 btn btn-warning btn-sm"><i class="fas fa-edit"></i></a>
                                    <form id="delete-form-{{ $category->id }}" action="{{ route('admins.main-categories.destroy', $category->id) }}"
                                        method="post">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="mx-1 btn btn-danger btn-sm"
                                            onclick="confirmDelete({{ $category->id }}); event.preventDefault();"><i
                                                class="fas fa-trash-alt"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="py-2 d-flex justify-content-center">
            {{ $categories->links() }}
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('input[type="checkbox"]').on('change', function() {
                var id = $(this).data('id');

                let url = "{{ route('admins.main-categories.toggle', ':id') }}".replace(':id', id);

                $.ajax({
                    url: url,
                    type: 'PUT',
                    data: {
                        _token: '{{ csrf_token() }}',
                    },

                    success: function(response) {
                        const toastEl = document.getElementById('successToast');
                        const toast = new bootstrap.Toast(toastEl);
                        toast.show();
                    },
                    error: function(xhr, status, error) {
                        console.error('Error:', error);
                    }
                });
            });
        });
    </script>
    <script>
        function confirmDelete(cityId) {
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
                    document.getElementById('delete-form-' + cityId).submit();
                    document.querySelector('tr[data-id="' + cityId + '"]')?.remove();
                }
            });
        }
    </script>

</x-dashboard.main-layout>
