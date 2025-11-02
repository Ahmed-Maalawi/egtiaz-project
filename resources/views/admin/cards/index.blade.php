<x-dashboard.main-layout>
    @php
        $rev_locale = app()->getLocale() == 'en' ? 'ar' : 'en';
    @endphp
    <h1 class="mb-3 text-gray-800 h3">{{ __('Cards') }}</h1>
    <div class="mb-4 shadow card">
        <div class="py-3 card-header">
            <h6 class="m-0 mt-2 font-weight-bold text-primary"></h6>
            <div class="float-right d-inline">
                <a href="{{ route('admins.cards.edit') }}" class="btn btn-primary btn-sm"><i
                        class="fa fa-edit"></i>{{ __('Edit') }}</a>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable-ar" cellspacing="0">
                    <thead>
                        <tr>
                            <th>{{ __('Serial') }}</th>
                            <th>{{ __('Card Name') }}</th>
                            <th>{{ __('Payment Limit From') }}</th>
                            <th>{{ __('Payment Limit To') }}</th>
                            <th>{{ __('Orders Count Limit From') }}</th>
                            <th>{{ __('Orders Count Limit To') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $i=0; @endphp
                        @foreach ($cards as $card)
                            <tr>
                                <td>{{ ++$i }}</td>
                                <td>{{ $card->name }} <br> {{ $card->getTranslation('name', $rev_locale) }} </td>
                                <td>{{ $card->payment_limit_from }} {{ __('EGP') }}</td>
                                <td>{{ $card->payment_limit_to == 100000000000000 ? __('Unlimited') : $card->payment_limit_to . ' ' . __('EGP') }}</td>
                                <td>{{ $card->orders_count_from }} {{ __('Orders') }}</td>
                                <td>{{ $card->orders_count_to == 100000000000000 ? __('Unlimited') : $card->orders_count_to . ' ' . __('Orders') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>


</x-dashboard.main-layout>
