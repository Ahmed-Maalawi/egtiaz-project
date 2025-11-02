<x-dashboard.main-layout>

    <div class="card-body">
        <form class="my-3" action="{{ route('admins.users.update', $user->id) }}" method="post"
            enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="name">{{ __('Name') }}</label>
                <input type="text" name="name" class="form-control" id="name" placeholder="{{ __('Name') }}"
                    required value="{{ old('name') ?? $user->name }}">
            </div>

            <div class="form-group">
                <label for="email">{{ __('Email') }}</label>
                <input type="email" name="email" class="form-control" id="email"
                    placeholder="{{ __('Email') }}" required value="{{ old('email') ?? $user->email }}">
            </div>

            <div class="form-group">
                <label for="email_verified">{{ __('Email Verified') }}</label>
                <select name="email_verified" class="form-control" id="email_verified" required>
                    <option value="1" @selected(old('email_verified', $user->email_verified_at ? '1' : '0') == '1')>{{ __('Yes') }}</option>
                    <option value="0" @selected(old('email_verified', $user->email_verified_at ? '1' : '0') == '0')>{{ __('No') }}</option>
                </select>
            </div>


            <div class="form-group">
                <label for="role">{{ __('Country Code') }}</label>
                <input type="text" name="country_code" class="form-control" id="country_code"
                    placeholder="{{ __('Country Code') }}" required
                    value="{{ old('country_code') ?? $user->country_code }}">
            </div>

            <div class="form-group">
                <label for="role">{{ __('Phone') }}</label>
                <input type="text" name="phone_number" class="form-control" id="phone"
                    placeholder="{{ __('Phone') }}" required
                    value="{{ old('phone_number') ?? $user->phone_number }}">
            </div>

            <div class="form-group">
                <label for="phone_verified">{{ __('Phone Verified') }}</label>
                <select name="phone_verified" class="form-control" id="phone_verified" required>
                    <option value="1" @selected(old('phone_verified', $user->phone_verified_at ? '1' : '0') == '1')>{{ __('Yes') }}</option>
                    <option value="0" @selected(old('phone_verified', $user->phone_verified_at ? '1' : '0') == '0')>{{ __('No') }}</option>
                </select>
            </div>


            <div class="form-group">
                <label for="status">{{ __('Status') }}</label>
                <select name="status" class="form-control" id="status" required>
                    <option value="active" @selected((old('status') ?? $user->status) == 'active')>{{ __('Active') }}</option>
                    <option value="inactive" @selected((old('status') ?? $user->status) == 'inactive')>{{ __('Banned') }}</option>
                </select>
            </div>

            <div class="form-group">
                <label for="">{{ __('Existing Featured Photo') }}</label>
                <div>
                    <img src="{{ asset('storage/' . $user->image) }}" class="w_200" alt="">
                </div>
            </div>
            <div class="form-group">
                <label for="image" class="for">{{ __('Image') }}</label>
                <input type="file" name="image" class="form-control" id="image"
                    placeholder="{{ __('Image') }}">
            </div>

            <button type="submit" class="btn btn-success btn-block mb_40">{{ __('Update') }}</button>
        </form>


</x-dashboard.main-layout>
