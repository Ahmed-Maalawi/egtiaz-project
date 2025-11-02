<x-dashboard.main-layout>
    @php
        $rev_locale = app()->getLocale() == 'en' ? 'ar' : 'en';
    @endphp
    <h1 class="mb-3 text-gray-800 h3">{{ __('Branches') }}</h1>
    <div class="mb-4 shadow card">
        <div class="py-3 card-header">
            <h6 class="m-0 mt-2 font-weight-bold text-primary"></h6>
            <div class="float-right d-inline">
                <a href="{{ route('admins.branches.create') }}" class="btn btn-primary btn-sm">
                    <i class="fa fa-plus"></i>{{ __('Add New') }}
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable-ar" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>{{ __('Serial') }}</th>
                            <th>{{ __('Address') }}</th>
                            <th>{{ __('Location') }}</th>
                            <th>{{ __('Service Provider') }}</th>
                            <th>{{ __('City Name') }}</th>
                            <th>{{ __('Phone') }}</th>
                            <th>{{ __('Alternative Phone') }}</th>
                            <th>{{ __('Logo') }}</th>
                            <th>{{ __('Cashiers Count') }}</th>
                            <th>{{ __('Failed Order This Month') }}</th>
                            <th>{{ __('Failed Order') }}</th>
                            <th>{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $i=0; @endphp
                        @foreach ($branches as $branch)
                            <tr data-id="{{ $branch->id }}">
                                <td>{{ ++$i }}</td>
                                <td>{{ $branch->getTranslation('address', app()->getLocale()) }} <br>
                                    {{ $branch->getTranslation('address', $rev_locale) }}</td>
                                <td>
                                    <a href="https://maps.google.com/?latitude={{ $branch->latitude }}&longitude={{ $branch->longitude }}"
                                        target="_blank">{{ __('View on Map') }}
                                    </a>
                                </td>
                                <td>{{ $branch->serviceProvider->getTranslation('name', app()->getLocale()) }}
                                    <br>
                                    {{ $branch->serviceProvider->getTranslation('name', $rev_locale) }}
                                </td>

                                <td>{{ $branch->city->getTranslation('name', app()->getLocale()) }}
                                    <br>
                                    {{ $branch->city->getTranslation('name', $rev_locale) }}
                                </td>

                                <td>{{ $branch->phone }}</td>
                                <td>{{ $branch->phone_alt }}</td>

                                @if (!is_null($branch->serviceProvider->image))
                                    <td><img src="{{ asset('storage/' . $branch->serviceProvider->image) }}" width="70"
                                            alt="" class=""></td>
                                @else
                                    <td>
                                        <p>{{ __('No image') }}</p>
                                    </td>
                                @endif
                                <td>{{ $branch->users_count }}</td>
                                <td>{{ $branch->failed_orders_this_month_count }}</td>
                                <td>{{ $branch->failed_orders_count }}</td>
                                <td class="d-flex justify-content-center">
                                    <a href="{{ route('admins.branches.duplicate', ['id' => $branch->id]) }}"
                                        class="mx-1 btn btn-warning btn-sm">
                                        <i class="fas fa-copy"></i>
                                    </a>
                                    <a href="{{ route('admins.branches.edit', $branch->id) }}"
                                        class="mx-1 btn btn-warning btn-sm">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form id="delete-form-{{ $branch->id }}"
                                        action="{{ route('admins.branches.destroy', $branch->id) }}" method="post">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="mx-1 btn btn-danger btn-sm"
                                            onclick="confirmDelete({{ $branch->id }}); event.preventDefault(); ">
                                            <i class="fas fa-trash-alt"></i></button>
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
        function confirmDelete(branchId) {
            Swal.fire({
                title: "{{ __('Are you sure?') }}",
                text: "{{ __('You will not be able to revert this! ,Branch Cashiers Will Also Deleted') }}",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "{{ __('Yes, delete it!') }}"
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + branchId).submit();
                    // document.querySelector('tr[data-id="' + branchId + '"]')?.remove();
                }
            });
        }
    </script>

</x-dashboard.main-layout>
