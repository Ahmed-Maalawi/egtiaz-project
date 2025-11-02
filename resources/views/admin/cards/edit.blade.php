<x-dashboard.main-layout>

    <div class="card-body">
        <form class="my-3" action="{{ route('admins.cards.update') }}" method="post"
            enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="mb-4 shadow card t-left">
                <div class="card-body">
                    <div class="row">
                        <div class="col-3">
                            <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist"
                                aria-orientation="vertical">
                                <a class="nav-link active" id="p1_tab" data-toggle="pill" href="#p1"
                                    role="tab" aria-controls="p1" aria-selected="true">{{ __('Golden Card') }}</a>
                                <a class="nav-link" id="p2_tab" data-toggle="pill" href="#p2" role="tab"
                                    aria-controls="p2" aria-selected="false">{{ __('Silver Card') }}</a>
                                <a class="nav-link" id="p3_tab" data-toggle="pill" href="#p3" role="tab"
                                    aria-controls="p3" aria-selected="false">{{ __('Normal Card') }}</a>
                            </div>
                        </div>
                        <div class="col-9">
                            <div class="tab-content" id="v-pills-tabContent">
                                <div class="tab-pane fade show active" id="p1" role="tabpanel"
                                    aria-labelledby="p1_tab">
                                    <h4 class="heading-in-tab">{{ __('Golden Card') }}</h4>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="">{{ __('Payment Limit Start') }}</label>
                                                <input type="number" required name="payment_limit_from_golden"
                                                    class="form-control"
                                                    value="{{ old('payment_limit_from_golden') ?? $cards[0]['payment_limit_from'] }}"
                                                    autofocus>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="">{{ __('Payment Limit End') }}</label>
                                                <input type="number" name="payment_limit_to_golden" class="form-control"
                                                    value="">
                                                <small
                                                    class="form-text text-muted">{{ __('Leave blank for no limit') }}</small>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="">{{ __('Orders Count Start') }}</label>
                                                <input type="number" name="orders_count_from_golden" class="form-control"
                                                    value="{{ old('orders_count_from_golden') ?? $cards[0]['orders_count_from'] }}">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="">{{ __('Orders Count End') }}</label>
                                                <input type="number" name="orders_count_to_golden" class="form-control"
                                                    value="">
                                                <small
                                                    class="form-text text-muted">{{ __('Leave blank for no limit') }}</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- // Tab 1 -->

                                <!-- Tab 2 -->
                                <div class="tab-pane fade" id="p2" role="tabpanel" aria-labelledby="p2_tab">
                                    <h4 class="heading-in-tab">{{ __('Silver Card') }}</h4>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="">{{ __('Payment Limit Start') }}</label>
                                                <input type="number" required name="payment_limit_from_silver"
                                                    class="form-control"
                                                    value="{{ old('payment_limit_from_silver') ?? $cards[1]['payment_limit_from'] }}"
                                                    autofocus>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="">{{ __('Payment Limit End') }}</label>
                                                <input type="number" name="payment_limit_to_silver" class="form-control"
                                                    value="{{ old('payment_limit_to_silver') ?? $cards[1]['payment_limit_to'] }}">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="">{{ __('Orders Count Start') }}</label>
                                                <input type="number" name="orders_count_from_silver" class="form-control"
                                                    value="{{ old('orders_count_from_silver') ?? $cards[1]['orders_count_from'] }}">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="">{{ __('Orders Count End') }}</label>
                                                <input type="number" name="orders_count_to_silver" class="form-control"
                                                    value="{{ old('orders_count_to_silver') ?? $cards[1]['orders_count_to'] }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- // Tab 2 -->

                                <!-- Tab 3 -->
                                <div class="tab-pane fade" id="p3" role="tabpanel" aria-labelledby="p3_tab">
                                    <h4 class="heading-in-tab">{{ __('Normal Card') }}</h4>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="">{{ __('Payment Limit Start') }}</label>
                                                <input type="number" required name="payment_limit_from_normal"
                                                    class="form-control"
                                                    value="0"
                                                    autofocus>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="">{{ __('Payment Limit End') }}</label>
                                                <input type="number" name="payment_limit_to_normal" class="form-control"
                                                    value="{{ old('payment_limit_to_normal') ?? $cards[2]['payment_limit_to'] }}">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="">{{ __('Orders Count Start') }}</label>
                                                <input type="number" name="orders_count_from_normal" class="form-control"
                                                    value="0">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="">{{ __('Orders Count End') }}</label>
                                                <input type="number" name="orders_count_to_normal" class="form-control"
                                                    value="{{ old('orders_count_to_normal') ?? $cards[2]['orders_count_to'] }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-success btn-block mb_40">{{ __('Update') }}</button>
        </form>
    </div>

</x-dashboard.main-layout>
