<x-dashboard.main-layout>

    <div class="card-body">
        <form class="my-3" action="{{ route('admins.cashiers.store') }}" method="post" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <label for="name">{{ __('Cashier Name') }}</label>
                <input type="text" name="name" class="form-control" id="name" placeholder="{{ __('Name') }}"
                    required value="{{ old('name') }}">
            </div>
            
            <div class="form-group">
                <label for="email">{{ __('Cashier Email') }}</label>
                <input type="email" name="email" class="form-control" id="email" placeholder="{{ __('Email') }}"
                     value="{{ old('email') }}">
            </div>

            <div class="form-group">
                <label for="role">{{ __('Country Code') }}</label>
                <input type="text" name="country_code" class="form-control" id="country_code"
                    placeholder="{{ __('Country Code') }}"  value="{{ old('country_code') ?? '+20' }}">
            </div>

            <div class="form-group">
                <label for="role">{{ __('Phone Number') }}</label>
                <input type="text" name="phone_number" class="form-control" id="phone"
                    placeholder="{{ __('Phone Number') }}"  value="{{ old('phone_number') }}">
            </div>

            <div class="form-group">
                <label for="status">{{ __('Status') }}</label>
                <select name="status" class="form-control" id="status" >
                    <option value="active" @selected(old('status') == 'active')>{{ __('Active') }}</option>
                    <option value="inactive" @selected(old('status') == 'inactive')>{{ __('Banned') }}</option>
                </select>
            </div>

            <div class="form-group">
                <label for="gender">{{ __('Gender') }}</label>
                <select name="gender" class="form-control" id="gender" required>
                    <option value="m" @selected(old('gender') == 'm')>{{ __('Male') }}</option>
                    <option value="f" @selected(old('gender') == 'f')>{{ __('Female') }}</option>
                </select>
            </div>

            <div class="form-group">
                <label for="service_provider_id">{{ __('Service Provider') }}</label>
                <select class="form-control service-provider-select" name="provider_cashier_id" style="width:100%"
                    id="service_provider_id"></select>
            </div>

            <div class="form-group d-none" id="branch-address-wrapper">
                <label for="service_provider_branch_id">{{ __('Branch Address') }}</label>
                <select class="form-control branch-address-select" name="service_provider_branch_id" style="width: 100%"
                    id="service_provider_branch_id"></select>
            </div>

            <div class="form-group">
                <label for="password">{{ __('Password') }}</label>
                <input type="password" name="password" class="form-control" id="password"
                    placeholder="{{ __('Password') }}" required>
            </div>

            <div class="form-group">
                <label for="re_password">{{ __('Retype Password') }}</label>
                <input type="password" name="re_password" class="form-control" id="re_password"
                    placeholder="{{ __('Retype Password') }}" required>
            </div>

            <div class="form-group">
                <label for="image" class="for">{{ __('Image') }}</label>
                <input type="file" name="image" class="form-control" id="image"
                    placeholder="{{ __('Image') }}">
            </div>

            <button type="submit" class="btn btn-success btn-block mb_40">{{ __('Create') }}</button>
        </form>

    </div>

    <script>
        $(document).ready(function() {
            let providerId = null;

            // Initialize service provider Select2
            $('#service_provider_id').select2({
                placeholder: "{{ __('Search for a service provider') }}",
                ajax: {
                    url: "{{ route('admins.providers.search') }}",
                    dataType: 'json',
                    delay: 500,
                    data: function(params) {
                        return {
                            q: params.term
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: data.map(provider => ({
                                id: provider.id,
                                text: provider.name
                            }))
                        };
                    },
                    cache: true
                }
            });

            // On provider selection, show branch field and initialize it
            $('#service_provider_id').on('change', function() {
                providerId = $(this).val();

                if (providerId) {
                    $('#branch-address-wrapper').removeClass('d-none');

                    // Initialize or re-initialize branch Select2
                    $('#service_provider_branch_id').select2({
                        placeholder: "{{ __('Search for branch address') }}",
                        ajax: {
                            url: "{{ route('admins.branches.search') }}",
                            dataType: 'json',
                            delay: 500,
                            data: function(params) {
                                return {
                                    q: params.term,
                                    provider_id: providerId // Send selected provider
                                };
                            },
                            processResults: function(data) {
                                return {
                                    results: data.map(branch => ({
                                        id: branch.id,
                                        text: branch.address // or address if different
                                    }))
                                };
                            },
                            cache: true
                        }
                    });

                } else {
                    $('#branch-address-wrapper').addClass('d-none');
                    $('#service_provider_branch_id').val(null).trigger('change');
                }
            });
        });
    </script>


</x-dashboard.main-layout>
