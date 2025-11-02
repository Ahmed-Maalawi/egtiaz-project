<x-dashboard.main-layout>
    <h1 class="mb-3 text-gray-800 h3">{{ __('Moderators') }}</h1>
    <div class="mb-4 shadow card">
        <div class="py-3 card-header">
            <h6 class="m-0 mt-2 font-weight-bold text-primary"></h6>
            <div class="float-right d-inline">
                <a href="{{ route('admins.moderators.create') }}" class="btn btn-primary btn-sm"><i
                        class="fa fa-plus"></i> {{ __('Add Moderator') }}</a>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable-ar" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>{{ __('Serial') }}</th>
                            <th>{{ __('Image') }}</th>
                            <th>{{ __('Name') }}</th>
                            <th>{{ __('Email') }}</th>
                            <th>{{ __('Company Name') }}</th>
                            <th>{{ __('Company Image') }}</th>
                            <th>{{ __('Status') }}</th>
                            <th>{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $i=0; @endphp
                        @foreach ($moderators as $moderator)
                            <tr>
                                <td>{{ ++$i }}</td>
                                <td>
                                    @if ($moderator->image)
                                        <img src="{{ asset('storage/' . $moderator->image) }}" alt="{{ $moderator->name }}"
                                            width="70">
                                    @else
                                    <p>{{ __('No Image') }}</p>
                                    @endif
                                </td>
                                <td>{{ $moderator->name }}</td>
                                <td>{{ $moderator->email }}</td>
                                <td>{{ $moderator->companyOfModeration?->getTranslation('name', app()->getLocale())}}</td>
                                <td>
                                    @if ($moderator->companyOfModeration?->image)
                                        <img src="{{ asset('storage/' . $moderator->companyOfModeration->image) }}" alt="{{ $moderator->companyOfModeration?->getTranslation('name', app()->getLocale()) }}"
                                            width="70">
                                    @else
                                    <p>{{ __('No Image') }}</p>
                                    @endif
                                </td>
                                <td>
                                    <input type="checkbox" @if ($moderator->status == 'active') checked @endif
                                        data-toggle="toggle" data-on="{{ __('Active') }}" data-off="{{ __('Banned') }}"
                                        data-onstyle="success" data-id = "{{ $moderator->id }}"
                                        data-offstyle="danger">
                                </td>
                                <td>
                                    <div class="d-flex">
                                        {{-- <a href="{{ route('admins.moderators.show', $moderator->id) }}"
                                            class="mx-1 btn btn-success btn-sm"><i class="fas fa-eye"></i></a> --}}
                                        <a href="{{ route('admins.moderators.edit', $moderator->id) }}"
                                            class="mx-1 btn btn-warning btn-sm"><i class="fas fa-edit"></i></a>
                                        <form action="{{ route('admins.moderators.destroy', $moderator->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="mx-1 btn btn-danger btn-sm"
                                                onclick="return confirm('{{ __('Are you sure?') }}')">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="py-2 d-flex justify-content-center">
            {{-- {{ $moderators->links() }} --}}
        </div>
    </div>

    <script>
        $(document).ready(function() {

            $('input[type="checkbox"]').on('change', function() {

                const checkbox = $(this);
                const id = checkbox.data('id');
                const url = @json(route('admins.users.toggle', ['id' => ':id'])).replace(':id', id);

                $.ajax({
                    url: url,
                    type: 'PUT',
                    data: {
                        _token: '{{ csrf_token() }}',
                    },
                    success: function(response) {
                    },
                    error: function(xhr) {
                    },
                });
            });
        });
    </script>

</x-dashboard.main-layout>
