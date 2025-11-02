<x-dashboard.main-layout>

    <div class="card-body">
        <form class="my-3" action="{{ route('admins.stages.update', $stage->id) }}" method="post"
            enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="type_id">{{ __('Iqama Type') }}</label>
                <select name="type_id" id="type_id" class="form-control" disabled>
                    @foreach ($types as $type)
                        <option value="{{ $type->id }}" @selected($type->id == $stage->type_id)>
                            {{ $type->getTranslation('name', app()->getLocale()) }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="order">{{ __('Order') }}</label>
                <input type="number" name="order" class="form-control" disabled id="order"
                    placeholder="{{ __('Order') }}" required value="{{ old('order') ?? $stage->order }}">
            </div>
            <div class="form-group">
                <label for="name_ar">{{ __('Name In Arabic') }}</label>
                <input type="text" name="name_ar" class="form-control" id="name_ar"
                    placeholder="{{ __('Name In Arabic') }}" required
                    value="{{ old('name_ar') ?? $stage->getTranslation('name', app()->getLocale()) }}">
            </div>

            <div class="form-group">
                <label for="name_en">{{ __('Name In English') }}</label>
                <input type="text" name="name_en" class="form-control" id="name_en"
                    placeholder="{{ __('Name In English') }}" required
                    value="{{ old('name_en') ?? $stage->getTranslation('name', app()->getLocale()) }}">
            </div>
            <div class="form-group">
                <label for="description_ar">{{ __('Description In Arabic') }}</label>
                <textarea name="description_ar" class="form-control" id="description_ar" rows="3"
                    placeholder="{{ __('Description In Arabic') }}" required>{{ old('description_ar') ?? $stage->getTranslation('description', app()->getLocale()) }}</textarea>
            </div>
            <div class="form-group">
                <label for="description_en">{{ __('Description In English') }}</label>
                <textarea name="description_en" class="form-control" id="description_en" rows="3"
                    placeholder="{{ __('Description In English') }}" required>{{ old('description_en') ?? $stage->getTranslation('description', app()->getLocale()) }}</textarea>
            </div>

            <div class="form-group">
                <label for="price">{{ __('Price') }}</label>
                <input type="number" step="0.1" name="price" class="form-control" id="price"
                    placeholder="{{ __('Price') }}" value="{{ old('price') ?? $stage->price }}">
            </div>

            <div class="form-group">
                <label for="price">{{ __('Cost') }}</label>
                <input type="number" step="0.1" name="cost" class="form-control" id="cost"
                       placeholder="{{ __('Cost') }}" value="{{ old('cost') ?? $stage->cost }}">
            </div>

            <div class="form-group">
                <label for="estimated_days">{{ __('Estimated Days') }}</label>
                <input type="number" step="1" name="estimated_days" class="form-control" id="estimated_days"
                    placeholder="{{ __('estimated_days') }}"
                    value="{{ old('estimated_days') ?? $stage->estimated_time_in_days }}">
            </div>

            <div class="form-group">
                <label for="">{{ __('Existing Image') }}</label>
                <div>
                    @if ($stage->image)
                        <img src="{{ asset('storage/' . $stage->image) }}" class="w_200" alt="">
                    @else
                        <p>{{ __('No Image') }}</p>
                    @endif
                </div>
            </div>
            <div class="form-group">
                <label for="image" class="for">{{ __('Image') }}</label>
                <input type="file" name="image" class="form-control" id="image"
                    placeholder="{{ __('Image') }}">
            </div>


            <div class="form-group">
                <label for="">{{ __('Existing File') }}</label>
                <div>
                    @if ($stage->file)
                        <a href="{{ asset('storage/' . $stage->file) }}" class="btn btn-primary">{{ __('Download File') }}</a>
                    @else
                        <p>{{ __('No File') }}</p>
                    @endif
                </div>
            </div>

            <div class="form-group">
                <label for="file" class="for">{{ __('File') }}</label>
                <input type="file" name="file" class="form-control" id="file"
                    placeholder="{{ __('File') }}">
            </div>

            <button type="submit" class="btn btn-success btn-block mb_40">{{ __('Update') }}</button>
        </form>

    </div>
</x-dashboard.main-layout>
