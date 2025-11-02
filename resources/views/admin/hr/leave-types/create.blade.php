<x-dashboard.main-layout>
    <div class="card-body" data-aos="fade-up">
        <form class="my-3" action="{{ route('admins.hr.leaveType.store') }}" method="post">
            @csrf

            <div class="form-group">
                <label for="name_ar">{{ __('name ar') }}</label>
                <input type="text" id="name_ar" name="name_ar" class="form-control" value="{{ old('name_ar') }}">
                @error('name_ar')
                    <span class="text-red d-block text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="name_en">{{ __('name en') }}</label>
                <input type="text" id="name_en" name="name_en" class="form-control" value="{{ old('name_en') }}">
                @error('name_en')
                <span class="text-red d-block text-sm">{{ $message }}</span>
                @enderror
            </div>



            <div class="form-group">
                <label for="description_ar">{{ __('description ar') }}</label>
                <input type="text" id="description_ar" name="description_ar" class="form-control" value="{{ old('description_ar') }}">
                @error('description_ar')
                <span class="text-red d-block text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="description_en">{{ __('description en') }}</label>
                <input type="text" id="description_en" name="description_en" class="form-control" value="{{ old('description_en') }}">
                @error('description_en')
                <span class="text-red d-block text-sm">{{ $message }}</span>
                @enderror
            </div>

            <button type="submit" class="btn btn-success">{{ __('Create') }}</button>
        </form>
    </div>

</x-dashboard.main-layout>
