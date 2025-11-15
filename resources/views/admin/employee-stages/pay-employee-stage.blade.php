<x-dashboard.main-layout>
    @php
        $locale = app()->getLocale();
        $stagePrice = $employeeStage->stage->price ?? 0;
    @endphp

    <div class="card-body">
        <a href="{{ route('admins.employee-stages.getSingleEmployee') }}" class="btn btn-warning rounded">{{ __('Back') }}</a>
        <form
            class="my-3"
            action="{{ route('admins.employee-stages.pay') }}"
            method="post"
            enctype="multipart/form-data"
        >
            @csrf
            @method('POST')

            <input type="hidden" name="employee_stage_id" value="{{ $employeeStage->id }}">
            <div class="form-group">

                <label for="payment_account">{{ __('Payment Account') }}</label>
                <select name="payment_account_id" class="form-control" id="payment_account" required>
                    <option value="">{{ __('Choose payment account') }}</option>
                    @foreach($paymentAccounts as $account)
                        <option value="{{ $account->id }}"
                                data-balance="{{ $account->balance ?? 0 }}">
                            {{ $account->getTranslation('name', $locale) }}
                        </option>
                    @endforeach
                </select>
            </div>


            <div class="form-group">

                <label for="payment_account">{{ __('Description') }}</label>
                <textarea name="description" id="description" rows="5" class="form-control"></textarea>
            </div>

            {{-- Balance info --}}
            <div class="row my-3">
                <div class="col-md-6">
                    <div class="alert alert-info mb-0">
                        <strong>{{ __('Current Balance:') }}</strong>
                        <span id="currentBalance">--</span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="alert alert-success mb-0">
                        <strong>{{ __('Balance After Payment:') }}</strong>
                        <span id="afterBalance">--</span>
                    </div>
                </div>
            </div>

            <hr>

            <div class="container my-5">
                <div class="card shadow-lg">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">{{ __('Stage Details') }}</h4>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered table-striped">
                            <tbody>
                            <tr>
                                <th>{{ __('ID') }}</th>
                                <td>{{ $employeeStage->id }}</td>
                            </tr>
                            <tr>
                                <th>{{ __('Employee') }}</th>
                                <td>{{ $employeeStage?->employee->name ?? __('unknown') }}</td>
                            </tr>
                            <tr>
                                <th>{{ __('Stage') }}</th>
                                <td>{{ $employeeStage?->stage->name ?? __('unknown') }}</td>
                            </tr>
                            <tr>
                                <th>{{ __('Status') }}</th>
                                <td>
                                    @switch($employeeStage->status)
                                        @case('pending')
                                            <span class="badge badge-warning">{{ __('Pending') }}</span>
                                            @break
                                        @case('in_progress')
                                            <span class="badge badge-primary text-white">{{ __('In progress') }}</span>
                                            @break
                                        @case('completed')
                                            <span class="badge badge-success text-white">{{ __('Completed') }}</span>
                                            @break
                                        @default
                                            {{ __('unknown') }}
                                    @endswitch
                                </td>
                            </tr>
                            <tr>
                                <th>{{ __('Price') }}</th>
                                <td>
                                    <input
                                        type="number"
                                        name="stage_price"
                                        id="stage_price"
                                        value="{{ $stagePrice }}"
                                        min="{{ $employeeStage->stage->cost }}"
                                        step="0.01"
                                        required
                                    >
                                    <small class="text-muted">
                                        {{ __('Minimum price is') }} {{ $employeeStage->stage->cost }}
                                    </small>

                                    <div id="priceWarning" class="text-danger small mt-1" style="display:none;">
                                        {{ __('Price cannot be less than stage cost') }}
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th>{{ __('Cost') }}</th>
                                <td>{{ $employeeStage->stage->cost }}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <button id="submitBtn" type="submit" class="btn btn-success btn-block mb_40">{{ __('Pay') }}</button>
        </form>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const accountSelect = document.getElementById("payment_account");
            const currentBalance = document.getElementById("currentBalance");
            const afterBalance = document.getElementById("afterBalance");
            const submitBtn = document.getElementById("submitBtn");

            const cost = parseFloat(@json($employeeStage->stage->cost)) || 0; // use cost, not price
            const priceInput = document.getElementById('stage_price');

            function updateBalances() {
                if (!accountSelect.value) {
                    currentBalance.textContent = "--";
                    afterBalance.textContent = "--";
                    submitBtn.disabled = true;
                    afterBalance.classList.remove('text-danger');
                    return;
                }

                const selected = accountSelect.selectedOptions[0];
                const balance = parseFloat(selected?.dataset?.balance) || 0;

                currentBalance.textContent = balance.toFixed(2);

                const after = balance - cost; // subtract cost, not price
                afterBalance.textContent = after.toFixed(2);

                if (after < 0) {
                    submitBtn.disabled = true;
                    afterBalance.classList.add('text-danger');
                } else {
                    submitBtn.disabled = false;
                    afterBalance.classList.remove('text-danger');
                }
            }

            // Validate price input (cannot be less than cost)
            priceInput.addEventListener('input', function () {
                const value = parseFloat(priceInput.value) || 0;

                if (value < cost) {
                    priceInput.classList.add('is-invalid');
                    submitBtn.disabled = true;
                } else {
                    priceInput.classList.remove('is-invalid');
                    submitBtn.disabled = false;
                }
            });

            accountSelect.addEventListener("change", updateBalances);

            updateBalances();
        });

    </script>
</x-dashboard.main-layout>
