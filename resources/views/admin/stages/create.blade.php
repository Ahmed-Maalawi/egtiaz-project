<x-dashboard.main-layout>

    <div class="card-body">
        <form class="my-3" action="{{ route('admins.stages.store') }}" method="post" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <label for="type_id">{{ __('Iqama Type') }}</label>
                <select name="type_id" id="type_id" class="form-control" required>
                    <option value="" disabled selected>{{ __('Select Iqama Type') }}</option>
                    @foreach ($types as $type)
                        <option value="{{ $type->id }}" @selected($type->id == old('type_id'))>{{ $type->getTranslation('name', app()->getLocale()) }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="order">{{ __('Order') }}</label>
                <input type="number" name="order" class="form-control" disabled id="order" placeholder="{{ __('Order') }}"
                    required value="{{ old('order') }}">
            </div>
            <div class="form-group">
                <label for="name_ar">{{ __('Name In Arabic') }}</label>
                <input type="text" name="name_ar" class="form-control" id="name_ar" placeholder="{{ __('Name In Arabic') }}"
                    required value="{{ old('name_ar') }}">
            </div>

            <div class="form-group">
                <label for="name_en">{{ __('Name In English') }}</label>
                <input type="text" name="name_en" class="form-control" id="name_en" placeholder="{{ __('Name In English') }}"
                    required value="{{ old('name_en') }}">
            </div>
            <div class="form-group">
                <label for="description_ar">{{ __('Description In Arabic') }}</label>
                <textarea name="description_ar" class="form-control" id="description_ar" rows="3"
                    placeholder="{{ __('Description In Arabic') }}" required>{{ old('description_ar') }}</textarea>
            </div>
            <div class="form-group">
                <label for="description_en">{{ __('Description In English') }}</label>
                <textarea name="description_en" class="form-control" id="description_en" rows="3"
                    placeholder="{{ __('Description In English') }}" required>{{ old('description_en') }}</textarea>
            </div>

            <div class="form-group">
                <label for="price">{{ __('Price') }}</label>
                <input type="number" step="0.1" name="price" class="form-control" id="price"
                    placeholder="{{ __('Price') }}" value="{{ old('price') }}">
            </div>

            <div class="form-group">
                <label for="price">{{ __('Cost') }}</label>
                <input type="number" step="0.1" name="cost" class="form-control" id="cost"
                       placeholder="{{ __('Cost') }}" value="{{ old('cost') }}">
            </div>

            <div class="form-group">
                <label for="estimated_days">{{ __('Estimated Days') }}</label>
                <input type="number" step="1" name="estimated_days" class="form-control" id="estimated_days"
                    placeholder="{{ __('estimated_days') }}" value="{{ old('estimated_days') }}">
            </div>

            <div class="form-group">
                <label for="image" class="for">{{ __('Image') }}</label>
                <input type="file" name="image" class="form-control" id="image"
                    placeholder="{{ __('Image') }}">
            </div>
            <div class="form-group">
                <label for="file" class="for">{{ __('File') }}</label>
                <input type="file" name="file" class="form-control" id="file"
                    placeholder="{{ __('File') }}">
            </div>

            <button type="submit" class="btn btn-success btn-block mb_40">{{ __('Create') }}</button>
        </form>

    </div>
    <script>
        $(document).ready(function() {
            $('#type_id').on('change', function() {
                var typeId = $(this).val();
                $.ajax({
                    url: "{{ route('admins.stages.getOrder') }}",
                    type: "GET",
                    data: {
                        id: typeId
                    },
                    success: function(data) {
                        $('#order').val(data);
                    }
                });
            });
        });
    </script>
</x-dashboard.main-layout>
