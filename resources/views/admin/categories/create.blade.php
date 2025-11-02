<x-dashboard.main-layout>

    <div class="card-body" data-aos="fade-up">
        <form class="my-3" action="{{ route('admins.categories.store') }}" method="post" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <label for="">{{ __('Category Name In Arabic') }}</label>
                <input type="text" name="name_ar" class="form-control" value="{{ old('name_ar') }}">
            </div>

            <div class="form-group">
                <label for="">{{ __('Category Name In English') }}</label>
                <input type="text" name="name_en" class="form-control" value="{{ old('name_en') }}">
            </div>

            <div class="form-group">
                <label for="">{{ __('Description In Arabic') }}</label>
                <textarea name="description_ar" class="form-control editor" cols="30" rows="10">{{ old('description_ar') }}
                </textarea>
            </div>

            <div class="form-group">
                <label for="">{{ __('Description In English') }}</label>
                <textarea name="description_en" class="form-control editor" cols="30" rows="10">{{ old('description_en') }}
                </textarea>
            </div>

            <div class="form-group">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="">{{ __('Parent Category') }}</label>
                        <select name="parent_id" class="form-control">
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" @selected($category->id == old('parent_id'))>{{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="">{{ __('Change Photo') }}</label>
                <div>
                    <input type="file" name="image">
                </div>
            </div>

            <button type="submit" class="btn btn-success">{{ __('Create') }}</button>
        </form>
    </div>

</x-dashboard.main-layout>
