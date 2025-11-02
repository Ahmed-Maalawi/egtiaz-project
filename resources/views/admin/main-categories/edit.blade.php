<x-dashboard.main-layout>

    <div class="card-body" data-aos="fade-up">
        <form class="my-3" action="{{ route('admins.main-categories.update', $category->id) }}" method="post"
            enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="">{{ __('Category Name In Arabic') }}</label>
                <input type="text" name="name_ar" class="form-control" value="{{ old('name_ar') ?? $category->getTranslation('name','ar')}}">
            </div>
            
            <div class="form-group">
                <label for="">{{ __('Category Name In English') }}</label>
                <input type="text" name="name_en" class="form-control" value="{{ old('name_en') ?? $category->getTranslation('name','en')}}">
            </div>

            <div class="form-group">
                <label for="">{{ __('Description In Arabic') }}</label>
                <textarea name="description_ar" class="form-control editor" cols="30" rows="10">{{ old('description_ar') ?? $category->getTranslation('description','ar') }}
                </textarea>
            </div>
            
            <div class="form-group">
                <label for="">{{ __('Description In English') }}</label>
                <textarea name="description_en" class="form-control editor" cols="30" rows="10">{{ old('description_en') ?? $category->getTranslation('description','en') }}
                </textarea>
            </div>

            <div class="form-group">
                <label for="">{{ __('Existing Image') }}</label>
                <div>
                    @if ($category->image)
                        <img src="{{ asset('storage/' . $category->image) }}" class="w_200" alt="">
                    @else
                        <p>{{ __('No Image') }}</p>
                    @endif
                </div>
            </div>

            <div class="form-group">
                <label for="">{{ __('Change Photo') }}</label>
                <div>
                    <input type="file" name="image">
                </div>
            </div>


            <button type="submit" class="btn btn-success">{{ __('Update') }}</button>
        </form>
    </div>

</x-dashboard.main-layout>
