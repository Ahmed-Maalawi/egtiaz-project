<x-dashboard.main-layout>

    <div class="card-body">
        <form class="my-3" action="{{ route('admins.notifications.user') }}" method="post"
            enctype="application/x-www-form-urlencoded">
            @csrf

            <div class="form-group">
                <label for="title">{{ __('Title') }}</label>
                <input type="text" name="title" class="form-control" id="title" placeholder="{{ __('title') }}"
                    required value="{{ old('title') }}">
            </div>

            <div class="form-group">
                <label for="description">{{ __('Description') }}</label>
                <textarea name="description" class="form-control" id="description" rows="3" placeholder="{{ __('Description') }}"
                    required>{{ old('description') }}</textarea>
            </div>

            <button type="submit" class="btn btn-success btn-block mb_40">{{ __('Send') }}</button>
        </form>
    </div>
</x-dashboard.main-layout>
