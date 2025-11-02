<x-dashboard.main-layout>

    <div class="card-body" data-aos="fade-up">
        <form class="my-3" action="{{ route('admins.branches.store') }}" method="post"
            enctype="application/x-www-form-urlencoded">
            @csrf
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="">{{ __('Address In Arabic') }}</label>
                        <input type="text" name="address_ar" class="form-control" value="{{ old('address_ar')}}">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="">{{ __('Address In English') }}</label>
                        <input type="text" name="address_en" class="form-control" value="{{ old('address_en') }}">
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="">{{ __('City') }}</label>
                        <select name="city_id"  class="form-control select2 city-select" required>
                        </select>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="">{{ __('Service Provider') }}</label>
                        <select name="service_provider_id" class="form-control select2 service-provider-select" required>
                        </select>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="">{{ __('Phone') }}</label>
                        <input type="text" name="phone" class="form-control" value="{{ old('phone') }}" required>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="">{{ __('Alternate Phone') }}</label>
                        <input type="text" name="phone_alt" class="form-control" value="{{ old('phone_alt') }}">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="">{{ __('Latitude') }}</label>
                        <input type="text" name="latitude" class="form-control" value="{{ old('latitude') }}">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="">{{ __('Longitude') }}</label>
                        <input type="text" name="longitude" class="form-control" value="{{ old('longitude') }}">
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-success">{{ __('Create') }}</button>
        </form>
    </div>

    <script>
    $(document).ready(function () {
        $('.service-provider-select').select2({
            placeholder: "{{ __('Search for a service provider') }}",
            ajax: {
                url: "{{ route('admins.providers.search') }}",
                dataType: 'json',
                delay: 500,
                data: function (params) {
                    return {
                        q: params.term
                    };
                },
                processResults: function (data) {
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
        $('.city-select').select2({
            placeholder: "{{ __('Search for a city') }}",
            ajax: {
                url: "{{ route('admins.cities.search') }}",
                dataType: 'json',
                delay: 500,
                data: function (params) {
                    return {
                        q: params.term
                    };
                },
                processResults: function (data) {
                    return {
                        results: data.map(city => ({
                            id: city.id,
                            text: city.name
                        }))
                    };
                },
                cache: true
            }
        });
    });
    </script>

</x-dashboard.main-layout>
