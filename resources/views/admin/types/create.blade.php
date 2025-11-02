<x-dashboard.main-layout>

    <div class="card-body">
        <form class="my-3" action="{{ route('admins.types.store') }}" method="post" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <label for="name">{{ __('Name In Arabic') }}</label>
                <input type="text" name="name_ar" class="form-control" id="name" placeholder="{{ __('Name') }}"
                    required value="{{ old('name_ar') }}">
            </div>

            <div class="form-group">
                <label for="name">{{ __('Name In English') }}</label>
                <input type="text" name="name_en" class="form-control" id="name"
                    placeholder="{{ __('Name') }}" required value="{{ old('name_en') }}">
            </div>

            <div class="form-group">
                <label for="description_ar">{{ __('Description In Arabic') }}</label>
                <textarea name="description_ar" class="form-control" id="description_ar" placeholder="{{ __('Description in Arabic') }}" required>{{ old('description_ar') }}</textarea>
            </div>

            <div class="form-group">
                <label for="description_en">{{ __('Description In English') }}</label>
                <textarea name="description_en" class="form-control" id="description_en" placeholder="{{ __('Description in English') }}"
                    required>{{ old('description_en') }}</textarea>
            </div>


            <button type="submit" class="btn btn-success btn-block mb_40">{{ __('Create') }}</button>
        </form>

    </div>

</x-dashboard.main-layout>
