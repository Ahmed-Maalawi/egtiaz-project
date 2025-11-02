<x-dashboard.main-layout>
    @php
        $rev_locale = app()->getLocale() == 'ar' ? 'en' : 'ar';
    @endphp
    <h1 class="mb-3 text-gray-800 h3">{{ __('Export Orders') }}</h1>

    <div class="card-body">
        <form action="{{ route('admins.orders.download') }}" method="POST">
            @csrf
            <div class="my-2 form-group">
                <label for="filter_type">{{ __('Filter By') }}</label>
                <select id="filter_type" class="form-control">
                    <option value="">{{ __('-- Select Filter Type --') }}</option>
                    <option value="category">{{ __('Category') }}</option>
                    <option value="service_provider">{{ __('Service Provider') }}</option>
                </select>
            </div>

            <div id="category_wrapper" class="my-2 form-group" style="display:none;">
                <label for="category">{{ __('Category') }}</label>
                <select name="category_ids[]" class="form-control" id="category" multiple></select>
            </div>

            <div id="service_provider_wrapper" class="my-2 form-group" style="display:none;">
                <label for="service-provider">{{ __('Service Provider') }}</label>
                <select name="service_provider_id" class="form-control" id="service-provider"></select>
            </div>

            <div id="branch_wrapper" class="my-2 form-group" style="display:none;">
                <label for="branch">{{ __('Branch') }}</label>
                <select name="branch_id" class="form-control" id="branch"></select>
            </div>

            <div class="row">
                <div class="my-2 form-group col-md-6">
                    <label for="from">{{ __('From') }}</label>
                    <input type="date" name="from" class="form-control" id="from"
                        value="{{ old('from') }}">
                </div>
                <div class="my-2 form-group col-md-6">
                    <label for="to">{{ __('To') }}</label>
                    <input type="date" name="to" class="form-control" id="to"
                        value="{{ old('to') }}">
                </div>
            </div>

            <button type="submit" class="my-5 btn btn-success btn-block">{{ __('Export') }}</button>
        </form>

    </div>

    <script>
        $(document).ready(function() {
            $('#category').select2({
                placeholder: "{{ __('Search for a category') }}",
                width: '100%',
                ajax: {
                    url: "{{ route('admins.categories.search') }}",
                    dataType: 'json',
                    delay: 500,
                    data: function(params) {
                        return {
                            q: params.term
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: data.map(category => ({
                                id: category.id,
                                text: category.name
                            }))
                        };
                    },
                    cache: true
                }
            });
        });
        $(document).ready(function() {
            $('#service-provider').select2({
                placeholder: "{{ __('Search for a service provider') }}",
                ajax: {
                    url: "{{ route('admins.providers.search') }}",
                    dataType: 'json',
                    width: '100%',
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
        });
        $(document).ready(function() {
            $('#filter_type').on('change', function() {
                let value = $(this).val();

                if (value === 'category') {
                    $('#category_wrapper').show();
                    $('#service_provider_wrapper, #branch_wrapper').hide();

                    $('#service-provider').val(null).trigger('change');
                    $('#branch').val(null).trigger('change');

                } else if (value === 'service_provider') {
                    $('#service_provider_wrapper').show();
                    $('#branch_wrapper').hide();
                    $('#category_wrapper').hide();

                    $('#category').val(null).trigger('change');

                } else {
                    $('#category_wrapper, #service_provider_wrapper, #branch_wrapper').hide();

                    $('#category').val(null).trigger('change');
                    $('#service-provider').val(null).trigger('change');
                    $('#branch').val(null).trigger('change');
                }
            });

            $('#category').select2({
                placeholder: "{{ __('Search for a category') }}",
                width: '100%',
                ajax: {
                    url: "{{ route('admins.categories.search') }}",
                    dataType: 'json',
                    delay: 500,
                    data: params => ({
                        q: params.term
                    }),
                    processResults: data => ({
                        results: data.map(c => ({
                            id: c.id,
                            text: c.text
                        }))
                    }),
                    cache: true
                }
            });

            $('#service-provider').select2({
                placeholder: "{{ __('Search for a service provider') }}",
                width: '100%',
                ajax: {
                    url: "{{ route('admins.providers.search') }}",
                    dataType: 'json',
                    delay: 500,
                    data: params => ({
                        q: params.term
                    }),
                    processResults: data => ({
                        results: data.map(p => ({
                            id: p.id,
                            text: p.name
                        }))
                    }),
                    cache: true
                }
            });

            // When service provider changes, load its branches
            $('#service-provider').on('change', function() {
                let providerId = $(this).val();
                if (providerId) {
                    $('#branch_wrapper').show();
                    $('#branch').select2({
                        placeholder: "{{ __('Search for a branch') }}",
                        width: '100%',
                        ajax: {
                            url: "{{ route('admins.branches.search') }}",
                            dataType: 'json',
                            delay: 500,
                            data: params => ({
                                q: params.term,
                                provider_id: providerId
                            }),
                            processResults: data => ({
                                results: data.map(b => ({
                                    id: b.id,
                                    text: b.address
                                }))
                            }),
                            cache: true
                        }
                    });
                } else {
                    $('#branch_wrapper').hide();
                }
            });
        });
    </script>

</x-dashboard.main-layout>
