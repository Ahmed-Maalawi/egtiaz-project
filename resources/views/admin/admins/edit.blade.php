<x-dashboard.main-layout>
    @php
        $permissions_tr = [];
        foreach ($permissions as $value) {
            $permissions_tr[] = [
                'id' => $value->id,
                'name' => __(ucfirst($value->name)),
            ];
        }
    @endphp

    <div class="card-body">
        <form class="my-3" action="{{ route('admins.admins.update', $admin->id) }}" method="post"
            enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="name">{{ __('Name') }}</label>
                <input type="text" name="name" class="form-control" id="name" placeholder="{{ __('Name') }}"
                    required value="{{ old('name') ?? $admin->name }}">
            </div>

            <div class="form-group">
                <label for="email">{{ __('Email') }}</label>
                <input type="email" name="email" class="form-control" id="email"
                    placeholder="{{ __('Email') }}" required value="{{ old('email') ?? $admin->email }}">
            </div>

            <div class="form-group">
                <label for="password">{{ __('Password') }}</label>
                <input type="password" name="password" class="form-control" id="password"
                    placeholder="{{ __('Password') }}" value="{{ old('password') }}">
                <small
                    class="form-text text-danger">{{ __('Leave blank if you do not want to change the password') }}</small>
            </div>

            <div class="form-group">
                <label for="password_confirmation">{{ __('Confirm Password') }}</label>
                <input type="password" name="password_confirmation" class="form-control" id="password_confirmation"
                    placeholder="{{ __('Confirm Password') }}" value="{{ old('password_confirmation') }}">
                <small
                    class="form-text text-danger">{{ __('Leave blank if you do not want to change the password') }}</small>
            </div>

            <div class="form-group">
                <label for="status">{{ __('Status') }}</label>
                <select name="status" class="form-control" id="status" required>
                    <option value="active" @selected((old('status') ?? $admin->status) == 'active')>{{ __('Active') }}</option>
                    <option value="inactive" @selected((old('status') ?? $admin->status) == 'inactive')>{{ __('Banned') }}</option>
                </select>
            </div>

            <div class="form-group">
                <label for="permission">{{ __('Permissions') }}</label>
                <select name="permissions[]" class="form-control select2" id="permissions" required multiple>
                    @foreach ($permissions_tr as $permission)
                        <option dir="rtl" value="{{ $permission['id'] }}" @selected(in_array($permission['id'], old('permissions', $admin->permissions->pluck('id')->toArray())))>
                            {{ $permission['name'] }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="paymentAccounts">{{ __('Payment Accounts') }}</label>
                <select name="paymentAccounts[]" class="form-control select2" id="paymentAccounts" multiple>
                    @foreach ($paymentAccounts as $account)
                        <option dir="rtl" value="{{ $account->id }}" @selected(in_array($account->id, old('paymentAccounts', $admin->paymentAccounts->pluck('id')->toArray())))>
                            {{ $account->getTranslation('name', app()->getLocale()) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="">{{ __('Existing Featured Photo') }}</label>
                <div>
                    <img src="{{ asset('storage/' . $admin->image) }}" class="" width="100px" alt="">
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
