<x-dashboard.main-layout>

    <div class="card-body">
        <form class="my-3" action="{{ route('admins.companies.update', $company->id) }}" method="post"
            enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="name">{{ __('Name In Arabic') }}</label>
                <input type="text" name="name_ar" class="form-control" id="name" placeholder="{{ __('Name') }}"
                    required value="{{ old('name_ar') ?? $company->getTranslation('name', 'ar') }}">
            </div>

            <div class="form-group">
                <label for="name">{{ __('Name In English') }}</label>
                <input type="text" name="name_en" class="form-control" id="name"
                    placeholder="{{ __('Name') }}" required
                    value="{{ old('name_en') ?? $company->getTranslation('name', 'en') }}">
            </div>

            <div class="form-group">
                <label for="description_ar">{{ __('Description In Arabic') }}</label>
                <textarea name="description_ar" class="form-control" id="description_ar" placeholder="{{ __('Description in Arabic') }}"
                    required>{{ old('description_ar') ?? $company->getTranslation('description', 'ar') }}</textarea>
            </div>

            <div class="form-group">
                <label for="description_en">{{ __('Description In English') }}</label>
                <textarea name="description_en" class="form-control" id="description_en"
                    placeholder="{{ __('Description in English') }}" required>{{ old('description_en') ?? $company->getTranslation('description', 'en') }}</textarea>
            </div>

            <div class="form-group">
                <label for="status">{{ __('Status') }}</label>
                <select name="status" class="form-control" id="status" required>
                    <option value="active"
                        {{ old('status') == 'active' || $company->status == 'active' ? 'selected' : '' }}>
                        {{ __('Active') }}</option>
                    <option value="inactive"
                        {{ old('status') == 'inactive' || $company->status == 'inactive' ? 'selected' : '' }}>
                        {{ __('Inactive') }}</option>
                </select>
            </div>

            <div class="form-group">
                <label for="">{{ __('Existing Image') }}</label>
                <div>
                    @if ($company->image)
                        <img src="{{ asset('storage/' . $company->image) }}" width="150" alt="">
                    @else
                        <p>{{ __('No Image') }}</p>
                    @endif
                </div>
            </div>
            <div class="form-group">
                <label for="banner_image" class="for">{{ __('Banner Image') }}</label>
                <input type="file" name="banner_image" class="form-control" id="banner_image"
                    placeholder="{{ __('Banner Image') }}">
            </div>

            <div class="form-group">
                <label for="">{{ __('Existing Image') }}</label>
                <div>
                    @if ($company->banner_image)
                        <img src="{{ asset('storage/' . $company->banner_image) }}" width="150" alt="">
                    @else
                        <p>{{ __('No Banner Image') }}</p>
                    @endif
                </div>
            </div>
            <div class="form-group">
                <label for="image" class="for">{{ __('Image') }}</label>
                <input type="file" name="image" class="form-control" id="image"
                    placeholder="{{ __('Image') }}" >
            </div>

            <button type="submit" class="btn btn-success btn-block mb_40">{{ __('Update') }}</button>
        </form>

    </div>

</x-dashboard.main-layout>
