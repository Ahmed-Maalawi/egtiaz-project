<x-dashboard.main-layout>

    <div class="card-body" data-aos="fade-up">
        <form class="my-3" action="{{ route('admins.banners.store') }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="">{{ __('start leave date') }}</label>
                <input type="date" name="start_date" class="form-control" value="{{ old('start_date') }}">
            </div>

            <div class="form-group">
                <label for="">{{ __('end leave date') }}</label>
                <input type="date" name="end_date" class="form-control" value="{{ old('end_date') }}">
            </div>

            <div class="form-group">
                <label for="">{{ __('Add Photo') }}</label>
                <div class="form-control">
                    <input type="file" name="image">
                </div>
            </div>

            <button type="submit" class="btn btn-success">{{ __('Create') }}</button>
        </form>
    </div>

</x-dashboard.main-layout>
