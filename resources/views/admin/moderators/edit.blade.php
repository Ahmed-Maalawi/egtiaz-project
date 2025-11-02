<x-dashboard.main-layout>

    <div class="card-body">
        <form class="my-3" action="{{ route('admins.moderators.update', $moderator->id) }}" method="post"
            enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="name">{{ __('Name') }}</label>
                <input type="text" name="name" class="form-control" id="name" placeholder="{{ __('Name') }}"
                    required value="{{ old('name') ?? $moderator->name }}">
            </div>

            <div class="form-group">
                <label for="email">{{ __('Email') }}</label>
                <input type="email" name="email" class="form-control" id="email"
                    placeholder="{{ __('Email') }}" required value="{{ old('email') ?? $moderator->email }}">
            </div>

            <div class="form-group">
                <label for="password">{{ __('Password') }}</label>
                <input type="password" name="password" class="form-control" id="password"
                    placeholder="{{ __('Password') }}" value="{{ old('password') }}">
                <small
                    class="form-text text-muted">{{ __('Leave blank if you don\'t want to change the password') }}</small>
            </div>

            <div class="form-group">
                <label for="password_confirmation">{{ __('Confirm Password') }}</label>
                <input type="password" name="password_confirmation" class="form-control" id="password_confirmation"
                    placeholder="{{ __('Confirm Password') }}" value="{{ old('password_confirmation') }}">
                <small
                    class="form-text text-muted">{{ __('Leave blank if you don\'t want to change the password') }}</small>
            </div>

            <div class="form-group">
                <label for="status">{{ __('Status') }}</label>
                <select name="status" class="form-control select2" id="status" required>
                    <option value="active" @selected(old('status') == 'active')>{{ __('Active') }}</option>
                    <option value="inactive" @selected(old('status') == 'inactive')>{{ __('Banned') }}</option>
                </select>
            </div>

            <div class="form-group">
                <label for="company_id">{{ __('Company') }}</label>
                <select name="company_id" class="form-control select2" id="select_company" required>
                    <option value="{{ old('company_id') ?? $moderator->moderator_company_id }}">
                        {{ $moderator->companyOfModeration->getTranslation('name', app()->getLocale()) }}
                    </option>
                </select>
            </div>


            <div class="form-group">
                <label for="">{{ __('Existing Photo') }}</label>
                <div>
                    <img src="{{ asset('storage/' . $moderator->image) }}" class="" width="150"
                        alt="">
                </div>
            </div>
            <div class="form-group">
                <label for="image" class="for">{{ __('Image') }}</label>
                <input type="file" name="image" class="form-control" id="image"
                    placeholder="{{ __('Image') }}">
            </div>

            <button type="submit" class="btn btn-success btn-block mb_40">{{ __('Update') }}</button>
        </form>

        <script>
            $(document).ready(function() {
                $('#select_company').select2({
                    placeholder: "{{ __('Type A Company Name') }}",
                    ajax: {
                        url: "{{ route('admins.companies.search') }}",
                        dataType: 'json',
                        delay: 500,
                        data: function(params) {
                            return {
                                q: params.term
                            };
                        },
                        processResults: function(data) {
                            return {
                                results: data.map(company => ({
                                    id: company.id,
                                    text: company.name,
                                }))
                            };
                        },
                        cache: true,
                    }
                });
            });
        </script>

</x-dashboard.main-layout>
