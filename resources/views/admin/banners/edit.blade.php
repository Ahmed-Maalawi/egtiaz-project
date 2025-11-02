<x-dashboard.main-layout>

    <div class="card-body" data-aos="fade-up">
        <form class="my-3" action="{{ route('admins.banners.update', $banner->id) }}" method="post"
            enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="">{{ __('Banner Title In Arabic') }}</label>
                <input type="text" name="title_ar" class="form-control" value="{{ old('title_ar') ?? $banner->getTranslation('title','ar')}}">
            </div>
            
            <div class="form-group">
                <label for="">{{ __('Banner Title In English') }}</label>
                <input type="text" name="title_en" class="form-control" value="{{ old('title_en') ?? $banner->getTranslation('title','en')}}">
            </div>

            <div class="form-group">
                <label for="">{{ __('Existing Image') }}</label>
                <div>
                    @if ($banner->image)
                        <img src="{{ asset('storage/' . $banner->image) }}" class="w_200" alt="">
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
