<x-dashboard.main-layout>
    <div class="card-body">
        <form class="my-3" action="{{ route('admins.notifications.cashier') }}" method="post"
            enctype="application/x-www-form-urlencoded">
            @csrf

            <div class="form-group">
                <label for="title">{{ __('Title') }}</label>
                <input type="text" name="title" class="form-control" id="title" placeholder="{{ __('title') }}"
                    required value="{{ old('title') }}">
            </div>

            <div class="form-group">
                <label for="description">{{ __('Description') }}</label>
                <textarea name="description" class="form-control" id="description" rows="3" placeholder="{{ __('Description') }}"
                    required>{{ old('description') }}</textarea>
            </div>

            <div class="my-2 form-group">
                <label for="service-provider">{{ __('Service Provider') }}</label>
                <select name="service_provider_ids[]" class="form-control" id="service-provider" style="height:50px"
                    multiple></select>
                <small class="form-text text-muted">{{ __('Leave blank to notify all cashiers.') }}</small>
            </div>

            <button type="submit" class="btn btn-success btn-block mb_40">{{ __('Send') }}</button>
        </form>
    </div>
    <script>
        $(document).ready(function() {
            $('#service-provider').select2({
                placeholder: "{{ __('Search for a service provider') }}",
                ajax: {
                    url: "{{ route('admins.providers.search') }}",
                    dataType: 'json',
                    width: '100%',
                    delay: 250,
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
    </script>
</x-dashboard.main-layout>
