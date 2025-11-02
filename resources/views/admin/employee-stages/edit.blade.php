<x-dashboard.main-layout>
    @php
        $locale = app()->getLocale();
    @endphp

    <div class="card-body">
        <form class="my-3" action="{{ route('admins.cashiers.update', $cashier->id) }}" method="post"
            enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="name">{{ __('Cashier Name') }}</label>
                <input type="text" name="name" class="form-control" id="name" placeholder="{{ __('Name') }}"
                    required value="{{ old('name') ?? $cashier->name }}">
            </div>

            <div class="form-group">
                <label for="email">{{ __('Cashier Email') }}</label>
                <input type="email" name="email" class="form-control" id="email"
                    placeholder="{{ __('Email') }}" value="{{ old('email') ?? $cashier->email }}">
            </div>

            <div class="form-group">
                <label for="role">{{ __('Country Code') }}</label>
                <input type="text" name="country_code" class="form-control" id="country_code"
                    placeholder="{{ __('Country Code') }}" 
                    value="{{ old('country_code') ?? $cashier->country_code }}">
            </div>

            <div class="form-group">
                <label for="role">{{ __('Phone Number') }}</label>
                <input type="text" name="phone_number" class="form-control" id="phone"
                    placeholder="{{ __('Phone Number') }}" 
                    value="{{ old('phone_number') ?? $cashier->phone_number }}">
            </div>

            <div class="form-group">
                <label for="status">{{ __('Status') }}</label>
                <select name="status" class="form-control" id="status" required>
                    <option value="active" @selected(old('status', $cashier->status) == 'active')>{{ __('Active') }}</option>
                    <option value="inactive" @selected(old('status', $cashier->status) == 'inactive')>{{ __('Banned') }}</option>
                </select>
            </div>

            <div class="form-group">
                <label for="gender">{{ __('Gender') }}</label>
                <select name="gender" class="form-control" id="gender" required>
                    <option value="m" @selected(old('gender', $cashier->gender) == 'm')>{{ __('Male') }}</option>
                    <option value="f" @selected(old('gender', $cashier->gender) == 'f')>{{ __('Female') }}</option>
                </select>
            </div>

            <div class="form-group">
                <label for="service_provider_id">{{ __('Service Provider') }}</label>
                <select class="form-control service-provider-select" name="provider_cashier_id" style="width:100%"
                    id="service_provider_id">
                    <option value="{{ $cashier->serviceProviderBranch->service_provider_id }}">
                        {{ $cashier->serviceProviderBranch->serviceProvider->getTranslation('name',app()->getLocale()) }}
                    </option>
                </select>
            </div>

            <div class="form-group" id="branch-address-wrapper">
                <label for="service_provider_branch_id">{{ __('Branch Address') }}</label>
                <select class="form-control branch-address-select" name="service_provider_branch_id" style="width: 100%"
                    id="service_provider_branch_id">
                    <option value="{{ $cashier->service_provider_branch_id }}">
                        {{ $cashier->serviceProviderBranch->getTranslation('address', $locale) }}</option>
                </select>
            </div>

            <div class="form-group">
                <label for="password">{{ __('Password') }}</label>
                <input type="password" name="password" class="form-control" id="password"
                    placeholder="{{ __('Password') }}">
                <small class="form-text text-danger">{{ __('Leave blank if you don\'t want to change it') }}</small>
            </div>

            <div class="form-group">
                <label for="re_password">{{ __('Retype Password') }}</label>
                <input type="password" name="re_password" class="form-control" id="re_password"
                    placeholder="{{ __('Retype Password') }}">
                <small class="form-text text-danger">{{ __('Leave blank if you don\'t want to change it') }}</small>
            </div>

            <div class="form-group">
                <label for="">{{ __('Existing Image') }}</label>
                <div>
                    @if ($cashier->image)
                        <img src="{{ asset('storage/' . $cashier->image) }}" id="existing-image" class="" style="width: 100px"
                            alt="">
                    @else
                        <p>{{ __('No Image') }}</p>
                    @endif
                </div>
                <small
                    class="form-text text-muted">{{ __('If you want to change the image, upload a new one.') }}</small>
                <button id="remove-image" class="btn btn-danger btn-sm">{{ __('Remove Image') }}</button>
            </div>

            <div class="form-group">
                <label for="image" class="for">{{ __('Image') }}</label>
                <input type="file" name="image" class="form-control" id="image"
                    placeholder="{{ __('Image') }}">
            </div>

            <button type="submit" class="btn btn-success btn-block mb_40">{{ __('Update') }}</button>
        </form>

    </div>

    <script>
        $(document).ready(function() {
            $('.service-provider-select').select2({
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
            $('#remove-image').on('click', function() {
                $('#existing-image').remove();
                $(this).remove();
                $('<input>').attr({
                    type: 'hidden',
                    name: 'remove_image',
                    value: '1'
                }).appendTo('form');
            });
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
                                        text: branch
                                            .address // or address if different
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
