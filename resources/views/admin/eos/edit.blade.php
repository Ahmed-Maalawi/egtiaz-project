<x-dashboard.main-layout>
    <div class="container py-4">
        <h3>{{ __('Edit End of Service Record') }}</h3>

        <form action="{{ route('admins.eos.update', $eo) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row">
                <div class="col-md-6 mb-2">
                    <label>{{ __('Employee') }}</label>
                    <select name="employee_id" class="form-control">
                        @foreach($employees as $emp)
                            <option value="{{ $emp->id }}" {{ $emp->id == $eo->employee_id ? 'selected' : '' }}>
                                {{ $emp->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 mb-2">
                    <label>{{ __('Joining Date') }}</label>
                    <input type="date" name="joining_date" class="form-control" value="{{ $eo->joining_date }}">
                </div>
                <div class="col-md-3 mb-2">
                    <label>{{ __('Leaving Date') }}</label>
                    <input type="date" name="leaving_date" class="form-control" value="{{ $eo->leaving_date }}">
                </div>
                <div class="col-md-3 mb-2">
                    <label>{{ __('Basic Salary') }}</label>
                    <input type="number" name="basic_salary" step="0.01" class="form-control" value="{{ $eo->basic_salary }}">
                </div>
                <div class="col-md-3 mb-2">
                    <label>{{ __('Gross Salary') }}</label>
                    <input type="number" name="gross_salary" step="0.01" class="form-control" value="{{ $eo->gross_salary }}">
                </div>
                <div class="col-md-3 mb-2">
                    <label>{{ __('Annual Leave Balance (days)') }}</label>
                    <input type="number" name="annual_leave_balance" step="0.1" class="form-control" value="{{ $eo->annual_leave_balance }}">
                </div>
            </div>

            <hr>
            <h5>{{ __('Additions') }}</h5>
            <div class="row">
                <div class="col-md-3"><input type="number" step="0.01" name="incentive" value="{{ $eo->incentive }}" placeholder="{{ __('Incentive') }}" class="form-control"></div>
                <div class="col-md-3"><input type="number" step="0.01" name="rewards" value="{{ $eo->rewards }}" placeholder="{{ __('Rewards') }}" class="form-control"></div>
                <div class="col-md-3"><input type="number" step="0.01" name="other_additions" value="{{ $eo->other_additions }}" placeholder="{{ __('Other Additions') }}" class="form-control"></div>
            </div>

            <hr>
            <h5>{{ __('Deductions') }}</h5>
            <div class="row">
                <div class="col-md-3"><input type="number" step="0.01" name="cash_advance" value="{{ $eo->cash_advance }}" placeholder="{{ __('Cash Advance') }}" class="form-control"></div>
                <div class="col-md-3"><input type="number" step="0.01" name="petty_cash" value="{{ $eo->petty_cash }}" placeholder="{{ __('Petty Cash') }}" class="form-control"></div>
                <div class="col-md-3"><input type="number" step="0.01" name="fines" value="{{ $eo->fines }}" placeholder="{{ __('Fines') }}" class="form-control"></div>
                <div class="col-md-3"><input type="number" step="0.01" name="compensation_notice" value="{{ $eo->compensation_notice }}" placeholder="{{ __('Compensation Notice') }}" class="form-control"></div>
            </div>

            <div class="mt-4 text-end">
                <button class="btn btn-success">{{ __('Update') }}</button>
                <a href="{{ route('admins.eos.show', $eo) }}" class="btn btn-secondary">{{ __('Cancel') }}</a>
            </div>
        </form>
    </div>
</x-dashboard.main-layout>
