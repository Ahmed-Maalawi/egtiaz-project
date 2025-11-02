<x-dashboard.main-layout>

    <div class="container">
        <h3>{{ __('Create End of Service Record') }}</h3>

        {{-- ✅ Display global validation errors --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                <strong>{{ __('Please fix the errors below before submitting.') }}</strong>
            </div>
        @endif

        <form action="{{ route('admins.eos.store') }}" method="POST">
            @csrf
            <input type="hidden" name="net_pay" id="net_pay_input">

            <div class="row">
                {{-- Employee --}}
                <div class="col-md-6 mb-3">
                    <label>{{ __('Employee') }}</label>
                    <select name="employee_id" class="form-control @error('employee_id') is-invalid @enderror">
                        <option value="">{{ __('Select Employee') }}</option>
                        @foreach($employees as $emp)
                            <option value="{{ $emp->id }}" {{ old('employee_id') == $emp->id ? 'selected' : '' }}>
                                {{ $emp->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('employee_id')
                    <div class="alert alert-danger mt-2 py-1 px-2">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Joining Date --}}
                <div class="col-md-3 mb-3">
                    <label>{{ __('Joining Date') }}</label>
                    <input type="date" name="joining_date" class="form-control @error('joining_date') is-invalid @enderror"
                           value="{{ old('joining_date') }}">
                    @error('joining_date')
                    <div class="alert alert-danger mt-2 py-1 px-2">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Leaving Date --}}
                <div class="col-md-3 mb-3">
                    <label>{{ __('Leaving Date') }}</label>
                    <input type="date" name="leaving_date" class="form-control @error('leaving_date') is-invalid @enderror"
                           value="{{ old('leaving_date') }}">
                    @error('leaving_date')
                    <div class="alert alert-danger mt-2 py-1 px-2">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Basic Salary --}}
                <div class="col-md-3 mb-3">
                    <label>{{ __('Basic Salary') }}</label>
                    <input type="number" step="0.01" name="basic_salary" class="form-control @error('basic_salary') is-invalid @enderror"
                           value="{{ old('basic_salary') }}">
                    @error('basic_salary')
                    <div class="alert alert-danger mt-2 py-1 px-2">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Gross Salary --}}
                <div class="col-md-3 mb-3">
                    <label>{{ __('Gross Salary') }}</label>
                    <input type="number" step="0.01" name="gross_salary" class="form-control @error('gross_salary') is-invalid @enderror"
                           value="{{ old('gross_salary') }}">
                    @error('gross_salary')
                    <div class="alert alert-danger mt-2 py-1 px-2">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Annual Leave Balance --}}
                <div class="col-md-3 mb-3">
                    <label>{{ __('Annual Leave Balance (days)') }}</label>
                    <input type="number" step="0.1" name="annual_leave_balance" class="form-control @error('annual_leave_balance') is-invalid @enderror"
                           value="{{ old('annual_leave_balance') }}">
                    @error('annual_leave_balance')
                    <div class="alert alert-danger mt-2 py-1 px-2">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <hr>
            <h5>{{ __('Additions') }}</h5>
            <div class="row">
                <div class="col-md-3 mb-3">
                    <input type="number" step="0.01" name="incentive" value="{{ old('incentive') }}"
                           placeholder="{{ __('Incentive') }}" class="form-control @error('incentive') is-invalid @enderror">
                    @error('incentive')
                    <div class="alert alert-danger mt-2 py-1 px-2">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-3 mb-3">
                    <input type="number" step="0.01" name="rewards" value="{{ old('rewards') }}"
                           placeholder="{{ __('Rewards') }}" class="form-control @error('rewards') is-invalid @enderror">
                    @error('rewards')
                    <div class="alert alert-danger mt-2 py-1 px-2">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-3 mb-3">
                    <input type="number" step="0.01" name="other_additions" value="{{ old('other_additions') }}"
                           placeholder="{{ __('Other Additions') }}" class="form-control @error('other_additions') is-invalid @enderror">
                    @error('other_additions')
                    <div class="alert alert-danger mt-2 py-1 px-2">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <hr>
            <h5>{{ __('Deductions') }}</h5>
            <div class="row">
                @foreach ([
                    'cash_advance' => __('Cash Advance'),
                    'petty_cash' => __('Petty Cash'),
                    'fines' => __('Fines'),
                    'compensation_notice' => __('Compensation Notice')
                ] as $field => $label)
                    <div class="col-md-3 mb-3">
                        <input type="number" step="0.01" name="{{ $field }}" value="{{ old($field) }}"
                               placeholder="{{ $label }}" class="form-control @error($field) is-invalid @enderror">
                        @error($field)
                        <div class="alert alert-danger mt-2 py-1 px-2">{{ $message }}</div>
                        @enderror
                    </div>
                @endforeach
            </div>

            <hr>
            <div class="alert alert-info mt-3">
                <h6> {{ __('End of Service Calculation Formula') }}</h6>
                <ul class="mb-0">
                    <li><strong><i class="fa-solid fa-arrow-pointer" style="color: #74C0FC;"></i> {{ __('Years of Service') }}:</strong> <code>{{ __('Leaving Date - Joining Date') }}</code></li>
                    <li><strong><i class="fa-solid fa-arrow-pointer" style="color: #74C0FC;"></i> {{ __('EOS Entitlement') }}:</strong>
                        <ul>
                            <li>{{ __('Less than 1 year → No EOS') }}</li>
                            <li>{{ __('1–5 years → Basic Salary × (21 ÷ 30) × Years') }}</li>
                            <li>{{ __('Over 5 years → Basic Salary × (30 ÷ 30) × Years') }}</li>
                        </ul>
                    </li>
                    <li><strong><i class="fa-solid fa-arrow-pointer" style="color: #74C0FC;"></i> {{ __('Leave Pay') }}:</strong> <code>{{ __('Annual Leave Balance × (Basic Salary ÷ 30)') }}</code></li>
                    <li><strong><i class="fa-solid fa-arrow-pointer" style="color: #74C0FC;"></i> {{ __('Net Pay') }}:</strong> <code>{{ __('EOS + Additions + Leave Pay - Deductions') }}</code></li>
                </ul>
            </div>

            <hr>
            <h5>{{ __('End of Service Calculation') }}</h5>

            <div id="eos-result" class="border p-3 rounded bg-light mb-3" style="display:none;">
                <p><strong>{{ __('Years of Service') }}:</strong> <span id="res-years"></span> {{ __('years') }}</p>
                <p><strong>{{ __('End of Service Amount') }}:</strong> AED <span id="res-eos"></span></p>
                <p><strong>{{ __('Leave Balance Pay') }}:</strong> AED <span id="res-leave"></span></p>
                <p><strong>{{ __('Total Additions') }}:</strong> AED <span id="res-additions"></span></p>
                <p><strong>{{ __('Total Deductions') }}:</strong> AED <span id="res-deductions"></span></p>
                <hr>
                <h5><strong>{{ __('Final Net Pay') }}: AED <span id="res-net" class="text-success"></span></strong></h5>
            </div>

            <button type="button" id="calculateBtn" class="btn btn-outline-primary">{{ __('Calculate EOS') }}</button>

            <div class="mt-3">
                <button class="btn btn-success">{{ __('Save Record') }}</button>
            </div>
        </form>
    </div>
</x-dashboard.main-layout>

<script>
    function getValue(name) {
        const el = document.querySelector(`[name="${name}"]`);
        return el ? el.value : 0;
    }

    document.getElementById('calculateBtn').addEventListener('click', function() {
        const data = {
            joining_date: getValue('joining_date'),
            leaving_date: getValue('leaving_date'),
            basic_salary: getValue('basic_salary'),
            annual_leave_balance: getValue('annual_leave_balance'),
            incentive: getValue('incentive'),
            rewards: getValue('rewards'),
            other_additions: getValue('other_additions'),
            cash_advance: getValue('cash_advance'),
            petty_cash: getValue('petty_cash'),
            fines: getValue('fines'),
            compensation_notice: getValue('compensation_notice'),
            other_deductions: getValue('other_deductions'),
            _token: '{{ csrf_token() }}'
        };

        fetch('{{ route("admins.eos.calculate") }}', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify(data)
        })
            .then(res => res.json())
            .then(result => {
                document.getElementById('eos-result').style.display = 'block';
                document.getElementById('res-years').textContent = result.years;
                document.getElementById('res-eos').textContent = result.eos_amount;
                document.getElementById('res-leave').textContent = result.leave_pay;
                document.getElementById('res-additions').textContent = result.additions;
                document.getElementById('res-deductions').textContent = result.deductions;
                document.getElementById('res-net').textContent = result.net_pay;
                document.getElementById('net_pay_input').value = result.net_pay;
            })
            .catch(err => alert('Error: ' + err));
    });
</script>
