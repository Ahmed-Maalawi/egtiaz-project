<x-dashboard.main-layout>
    <div class="container-fluid py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 text-gray-800">{{ __('Permissions Management') }}</h1>
        </div>

        <!-- Permissions Grid -->
        <div class="row">
            @foreach($groupedPermissions as $group => $permissions)
                @if($permissions->count() > 0)
                    <div class="col-12 mb-4">
                        <div class="card shadow">
                            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">
                                    <i class="fas fa-folder text-primary mr-2"></i>
                                    {{ $group ?: 'General Permissions' }}
                                </h5>
                                <span class="badge badge-primary">{{ $permissions->count() }} permissions</span>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    @foreach($permissions as $permission)
                                        <div class="col-md-6 col-lg-4 mb-3">
                                            <div class="card border-left-info h-100">
                                                <div class="card-body py-3">
                                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                                        <h6 class="card-title text-dark mb-0">{{ $permission->name }}</h6>
                                                        @if($permission->description)
                                                            <i class="fas fa-info-circle text-muted ml-2"
                                                               data-toggle="tooltip"
                                                               title="{{ $permission->description }}"></i>
                                                        @endif
                                                    </div>
                                                    <div class="mt-3">
                                                        @foreach($roles as $role)
                                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                                <small class="text-muted">{{ $role->name }}</small>
                                                                <div class="custom-control custom-switch">
                                                                    <input
                                                                        type="checkbox"
                                                                        class="custom-control-input grid-permission-checkbox"
                                                                        id="grid_role_{{ $role->id }}_perm_{{ $permission->id }}"
                                                                        value="{{ $permission->id }}"
                                                                        data-role-id="{{ $role->id }}"
                                                                        {{ $role->hasPermissionTo($permission) ? 'checked' : '' }}
                                                                    >
                                                                    <label class="custom-control-label" for="grid_role_{{ $role->id }}_perm_{{ $permission->id }}"></label>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    </div>


    <script>
        $(document).ready(function() {
            // Initialize tooltips
            $('[data-toggle="tooltip"]').tooltip();

            // Grid permission checkbox handler
            $('.grid-permission-checkbox').change(function() {
                const roleId = $(this).data('role-id');
                const permissionId = $(this).val();
                const isChecked = $(this).is(':checked');

                // Get all checked permissions for this role
                const permissions = [];
                $(`.grid-permission-checkbox[data-role-id="${roleId}"]:checked`).each(function() {
                    permissions.push(parseInt($(this).val()));
                });

                console.log('All permissions for role:', permissions);

                // Show loading state
                const $checkbox = $(this);
                $checkbox.prop('disabled', true);

                // Send AJAX request
                $.ajax({
                    url: '{{ route("admins.roles.permissions.update") }}',
                    method: 'POST',
                    data: JSON.stringify({
                        role_id: roleId,
                        permissions: permissions
                    }),
                    contentType: 'application/json',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    success: function(response) {
                        console.log('Success response:', response);
                        if (response.success) {
                            showAlert('Permissions updated successfully', 'success');
                        } else {
                            showAlert('Error updating permissions: ' + (response.message || 'Unknown error'), 'danger');
                            // Revert checkbox state
                            $checkbox.prop('checked', !isChecked);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX Error:', error);
                        console.log('XHR response:', xhr.responseText);
                        showAlert('Network error occurred: ' + error, 'danger');
                        // Revert checkbox state
                        $checkbox.prop('checked', !isChecked);
                    },
                    complete: function() {
                        $checkbox.prop('disabled', false);
                    }
                });
            });

            function showAlert(message, type) {
                // Remove existing alerts
                $('.alert-dismissible').remove();

                // Create new alert
                const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
                const iconClass = type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle';

                const alertHtml = `
                    <div class="alert ${alertClass} alert-dismissible fade show mb-4" role="alert">
                        <i class="fas ${iconClass} mr-2"></i>
                        ${message}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                `;

                $('.container-fluid').prepend(alertHtml);

                // Auto dismiss after 5 seconds
                setTimeout(function() {
                    $('.alert-dismissible').alert('close');
                }, 5000);
            }
        });
    </script>
</x-dashboard.main-layout>
