<x-dashboard.main-layout>
    <div class="container py-4">
        <h1 class="mb-4">{{ __('Manage Roles & Users') }}</h1>

        {{-- Create new role --}}
        <div class="card mb-4">
            <div class="card-header">{{ __('Create Role') }}</div>
            <div class="card-body">
                <form action="{{ route('admins.roles.store') }}" method="POST">
                    @csrf
                    <div class="d-flex">
                        <input type="text" name="name" class="form-control me-2" placeholder="{{ __('Role name') }}" required>
                        <button type="submit" class="btn btn-primary">{{ __('Add') }}</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- List roles --}}
        <div class="card mb-4">
            <div class="card-header">{{ __('Available Roles') }}</div>
            <div class="card-body">
                <ul class="list-group">
                    @foreach($roles as $role)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            {{ $role->name }}
                            @if($role->name !== 'super-admin')
                                <form action="{{ route('admins.roles.destroy', $role->id) }}" method="POST" onsubmit="return confirm('{{ __('Delete this role?') }}')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger btn-sm">{{ __('Delete') }}</button>
                                </form>
                            @endif

                        </li>
                    @endforeach
                </ul>
            </div>
        </div>

        {{-- Assign role to user --}}
        <div class="card mb-4">
            <div class="card-header">{{ __('Assign Role to User') }}</div>
            <div class="card-body">
                <form action="{{ route('admins.roles.assign') }}" method="POST">
                    @csrf
                    <div class="row g-2">
                        <div class="col-md-5">
                            <select name="user_id" class="form-select" required>
                                <option value="">{{ __('Select User') }}</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-5">
                            <select name="role_id" class="form-select" required>
                                <option value="">{{ __('Select Role') }}</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-success w-100">{{ __('Assign') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{-- Users with roles --}}
        <div class="card">
            <div class="card-header">{{ __('Users and Their Roles') }}</div>
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th>{{ __('User') }}</th>
                        <th>{{ __('Email') }}</th>
                        <th>{{ __('Roles') }}</th>
                        <th>{{ __('Actions') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($users as $user)
                        <tr>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                @forelse($user->roles as $role)
                                    <span class="text-white badge bg-primary">{{ $role->name }}</span>
                                @empty
                                    <span class="text-muted">{{ __('No roles') }}</span>
                                @endforelse
                            </td>
                            <td>
                                @foreach($user->roles as $role)
                                    <form action="{{ route('admins.roles.remove', [$user->id, $role->id]) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-warning">{{ __('Remove :role', ['role' => $role->name]) }}</button>
                                    </form>
                                @endforeach
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-dashboard.main-layout>
