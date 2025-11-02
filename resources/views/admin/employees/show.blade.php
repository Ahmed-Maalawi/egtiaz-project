<x-dashboard.main-layout>
    @php
        $rev_locale = app()->getLocale() == 'en' ? 'ar' : 'en';
    @endphp

    <h1 class="mb-3 text-gray-800 h3">{{ __('Provider') }} {{ $provider->name }} {{ __('Details') }}</h1>

    <div class="row">
        <div class="col-md-12">
            <div class="mb-4 shadow card">
                <div class="py-3 card-header">
                    <h6 class="m-0 mt-2 font-weight-bold text-primary"></h6>
                    <div class="float-right d-inline">
                        <a href="{{ route('admins.providers.index') }}" class="btn btn-primary btn-sm">
                            {{ __('Back') }}
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <tr>
                                <td>{{ __('Provider Name') }}</td>
                                <td>
                                    {{ $provider->name }} <br> {{ $provider->getTranslation('name', $rev_locale) }}
                                </td>
                            </tr>

                            <tr>
                                <td>{{ __('Image') }}</td>
                                <td>
                                    @if ($provider->image)
                                        <img src="{{ asset('storage/' . $provider->image) }}" class="w_100">
                                    @else
                                        <p>{{ __('No Image') }}</p>
                                    @endif
                                </td>
                            </tr>

                            <tr>
                                <td>{{ __('Status') }}</td>
                                <td>{{ $provider->status == 'active' ? __('Active') : __('Inactive') }}</td>
                            </tr>

                            <tr>
                                <td>{{ __('Category') }}</td>
                                <td>{{ $provider->category->name ?? __('Uncategorized') }} <br>
                                    {{ optional($provider->category)->getTranslation('name', $rev_locale) ?? '' }}</td>
                            </tr>

                            <tr>
                                <td>{{ __('Description') }}</td>
                                <td>{{ strip_tags($provider->description) }} <br>
                                    {{ strip_tags($provider->getTranslation('description', $rev_locale)) }}</td>
                            </tr>

                            <tr>
                                <td>{{ __('Total Provider Discount') }}</td>
                                <td>{{ $provider->discount_percent }} %</td>
                            </tr>

                            <tr>
                                <td>{{ __('Opens At') }}</td>
                                <td>
                                    {{ isset($provider->options['open_at'])
                                        ? \Carbon\Carbon::createFromFormat('H:i', $provider->options['open_at'])->format('h:i A')
                                        : 'N/A' }}
                                </td>
                            </tr>

                            <tr>
                                <td>{{ __('Closes In') }}</td>
                                <td>
                                    {{ isset($provider->options['close_at'])
                                        ? \Carbon\Carbon::createFromFormat('H:i', $provider->options['close_at'])->format('h:i A')
                                        : 'N/A' }}
                                </td>
                            </tr>

                            <tr>
                                <td>{{ __('Normal Provider Discount') }}</td>
                                <td>{{ $provider->normal_percent }} %</td>
                            </tr>

                            <tr>
                                <td>{{ __('Joined At') }}</td>
                                <td>{{ \Carbon\Carbon::parse($provider->created_at)->format('d M, Y h:i A') }}</td>
                            </tr>

                            <tr>
                                <td>{{ __('Branches Count') }}</td>
                                <td>{{ $provider->service_provider_branches_count }}</td>
                            </tr>
                            <tr>
                                <td>{{ __('Moderator Name') }}</td>
                                <td>{{ optional($provider->serviceProviderModerator)->name }}</td>
                            </tr>
                            <tr>
                                <td>{{ __('Branches Count') }}</td>
                                <td>{{ optional($provider->serviceProviderModerator)->email }}</td>
                            </tr>
                            <tr>
                                <td>{{ __('Branches Count') }}</td>
                                <td>{{ optional($provider->serviceProviderModerator)->status }}</td>
                            </tr>
                            <tr>
                                <td>{{ __('Branches Count') }}</td>
                                <td>
                                    @if ( optional($provider->serviceProviderModerator)->image)
                                        <img src="{{ asset('storage/' . optional($provider->serviceProviderModerator)->image) }}"
                                            class="w_100">
                                    @else
                                        <p>{{ __('No Image') }}</p>
                                    @endif
                                </td>
                            </tr>

                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-dashboard.main-layout>
