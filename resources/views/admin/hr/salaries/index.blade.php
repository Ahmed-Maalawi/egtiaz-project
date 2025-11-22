<x-dashboard.main-layout>
    <!-- Salary Generation Form -->
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="fas fa-cogs mr-2"></i>{{ __('Generate Monthly Salaries') }}</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('admins.hr.salaries.generate') }}" method="POST" class="row align-items-end">
                @csrf
                <div class="col-md-4">
                    <label for="month" class="form-label font-weight-bold">{{ __('Select Month') }}</label>
                    <input type="month"
                           name="month"
                           id="month"
                           class="form-control"
                           value="{{ old('month', request('month', date('Y-m'))) }}"
                           max="{{ date('Y-m') }}"
                           required>
                    @error('month')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <div class="form-text">
                        <i class="fas fa-info-circle mr-1"></i>
                        {{ __('This will generate salary for all active users for the selected month.') }}
                    </div>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-success w-100">
                        <i class="fas fa-play-circle mr-1"></i>{{ __('Generate Salaries') }}
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle mr-2"></i>
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle mr-2"></i>
            {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if(session('info'))
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            <i class="fas fa-info-circle mr-2"></i>
            {{ session('info') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <h6 class="alert-heading"><i class="fas fa-exclamation-triangle mr-1"></i>{{ __('Please fix the following errors:') }}</h6>
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if(session('error_array') && is_array(session('error_array')))
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <h6 class="alert-heading"><i class="fas fa-exclamation-triangle mr-1"></i>{{ __('Some errors occurred during salary generation:') }}</h6>
            <ul class="mb-0">
                @foreach(session('error_array') as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <!-- Current Month Display -->
    @if(request('month'))
        <div class="alert alert-info mb-4">
            <i class="fas fa-calendar-alt mr-2"></i>
            <strong>{{ __('Showing salaries for:') }}</strong>
            {{ \Carbon\Carbon::parse(request('month'))->format('F Y') }}
        </div>
    @endif

    <!-- Month Selection for Viewing Salaries -->
    <div class="card mb-4">
        <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="fas fa-calendar-alt mr-2"></i>{{ __('View Salaries by Month') }}</h5>
            @if(request('month'))
                <a href="{{ route('admins.hr.salaries.index') }}" class="btn btn-sm btn-light">
                    <i class="fas fa-times mr-1"></i> {{ __('Clear Filter') }}
                </a>
            @endif
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admins.hr.salaries.index') }}" class="row align-items-end">
                <div class="col-md-5">
                    <label for="view_month" class="form-label font-weight-bold">{{ __('Select Month to View') }}</label>
                    <input type="month"
                           name="month"
                           id="view_month"
                           class="form-control"
                           value="{{ request('month', date('Y-m')) }}"
                           max="{{ date('Y-m') }}"
                           onchange="this.form.submit()">
                </div>
                <div class="col-md-5">
                    <div class="form-text">
                        <i class="fas fa-eye mr-1"></i>
                        @if(request('month'))
                            {{ __('Currently viewing salaries for:') }} <strong>{{ \Carbon\Carbon::parse(request('month'))->format('F Y') }}</strong>
                        @else
                            {{ __('View existing salaries for the selected month.') }}
                        @endif
                    </div>
                </div>
                <div class="col-md-2">
                    @if(request('month'))
                        <a href="{{ route('admins.hr.salaries.index') }}" class="btn btn-outline-secondary w-100">
                            <i class="fas fa-times mr-1"></i> {{ __('Clear') }}
                        </a>
                    @else
                        <button type="submit" class="btn btn-info w-100">
                            <i class="fas fa-eye mr-1"></i> {{ __('View') }}
                        </button>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Bulk Actions -->
    <div class="card mb-4">
        <div class="card-body d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                <div class="form-check mr-3">
                    <input type="checkbox" id="selectAll" onchange="toggleSelectAll(this)" class="form-check-input">
                    <label for="selectAll" class="form-check-label">{{ __('Select All') }}</label>
                </div>

                <button type="button" onclick="openBulkPayModal()" id="bulkPayBtn" disabled
                        class="btn btn-success btn-sm">
                    <i class="fas fa-money-bill-wave mr-1"></i> {{ __('Pay Selected Salaries') }}
                </button>
            </div>

            <div class="text-muted small">
                {{ __('Showing') }} {{ $salaries->firstItem() }} - {{ $salaries->lastItem() }} {{ __('of') }} {{ $salaries->total() }} {{ __('salaries') }}
            </div>
        </div>
    </div>

    <!-- Salaries Table -->
    <div class="card">
        <div class="table-responsive">
            <table class="table table-bordered table-hover mb-0">
                <thead class="thead-light">
                <tr>
                    <th style="width: 40px;"></th>
                    <th>{{ __('User') }}</th>
                    <th>{{ __('Amount') }}</th>
                    <th>{{ __('Month') }}</th>
                    <th>{{ __('Status') }}</th>
                    <th>{{ __('Paid Date') }}</th>
                    <th>{{ __('Actions') }}</th>
                </tr>
                </thead>
                <tbody>
                @forelse($salaries as $salary)
                    <tr>
                        <td>
                            @if($salary->status === 'pending')
                                <input type="checkbox" name="salary_ids[]" value="{{ $salary->id }}"
                                       onchange="updateBulkActionButton()" class="salary-checkbox form-check-input">
                            @endif
                        </td>
                        <td>
                            <div>
                                <strong>{{ $salary->user?->name }}</strong><br>
                                <small class="text-muted">{{ $salary->user?->email }}</small>
                            </div>
                        </td>
                        <td>
                            <strong>${{ number_format($salary->amount, 2) }}</strong>
                        </td>
                        <td>
                            {{ \Carbon\Carbon::parse($salary->month . '-01')->format('F Y') }}
                        </td>
                        <td>
                            @if($salary->status === 'paid')
                                <span class="badge badge-success">{{ __('Paid') }}</span>
                            @else
                                <span class="badge badge-warning">{{ __('Pending') }}</span>
                            @endif
                        </td>
                        <td>
                            {{ $salary->paid_at ? $salary->paid_at->format('M j, Y g:i A') : '-' }}
                        </td>
                        <td>
                            @if($salary->status === 'pending')
                                <button type="button" onclick="openPayModal({{ $salary->id }}, '{{ $salary->user?->name }}', {{ $salary->amount }})"
                                        class="btn btn-outline-success btn-sm">
                                    <i class="fas fa-money-bill-wave mr-1"></i> {{ __('Pay Salary') }}
                                </button>
                            @elseif($salary->status === 'paid')
                                <form action="{{ route('admins.hr.salaries.delete', $salary) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">{{ __('Delete') }}</button>
                                </form>
                            @else
                                <span class="text-muted">{{ __('Paid') }}</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted">
                            {{ __('No salaries found for the selected criteria.') }}
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        @if($salaries->hasPages())
            <div class="py-2 d-flex justify-content-center">
                {{ $salaries->links() }}
            </div>
        @endif
    </div>

    <!-- Pay Salary Modal -->
    <div class="modal fade" id="payModal" tabindex="-1" role="dialog" aria-labelledby="payModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="payModalLabel">{{ __('Pay Salary') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="paySalaryForm" action="{{ route('admins.hr.salaries.pay') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="salary_id" id="modal_salary_id">

                        <div class="form-group">
                            <label class="font-weight-bold">{{ __('User') }}</label>
                            <p id="modal_user_name" class="form-control-plaintext"></p>
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold">{{ __('Amount') }}</label>
                            <p id="modal_salary_amount" class="form-control-plaintext"></p>
                        </div>

                        <div class="form-group">
                            <label for="modal_payment_account_id" class="font-weight-bold">{{ __('Payment Account') }} *</label>
                            <select name="payment_account_id" id="modal_payment_account_id" class="form-control" required>
                                <option value="">{{ __('Select Payment Account') }}</option>
                                @foreach($paymentAccounts as $account)
                                    <option value="{{ $account->id }}" data-balance="{{ $account->balance }}">
                                        {{ $account->name }} - ${{ number_format($account->balance, 2) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="modal_description" class="font-weight-bold">{{ __('Description') }}</label>
                            <textarea name="description" id="modal_description" rows="3" class="form-control" placeholder="{{ __('Optional payment description') }}"></textarea>
                        </div>

                        <!-- Balance Information in Modal -->
                        <div class="alert alert-info">
                            <div class="row">
{{--                                <div class="col-6">--}}
{{--                                    <small class="font-weight-bold">{{ __('Company Wallet') }}</small>--}}
{{--                                    <div id="modalCompanyBalance" class="font-weight-bold">Loading...</div>--}}
{{--                                </div>--}}
                                <div class="col-6">
                                    <small class="font-weight-bold">{{ __('Payment Account') }}</small>
                                    <div id="modalPaymentAccountBalance" class="font-weight-bold">-</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Cancel') }}</button>
                        <button type="submit" class="btn btn-success">{{ __('Confirm Payment') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Bulk Pay Modal -->
    <div class="modal fade" id="bulkPayModal" tabindex="-1" role="dialog" aria-labelledby="bulkPayModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="bulkPayModalLabel">{{ __('Pay Selected Salaries') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="bulkPayForm" action="{{ route('admins.hr.salaries.bulk-pay') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div id="bulkSalaryIds"></div>

                        <div class="form-group">
                            <label class="font-weight-bold">{{ __('Selected Salaries') }}</label>
                            <p id="bulk_selected_count" class="font-weight-bold mb-1"></p>
                            <p id="bulk_total_amount" class="text-muted mb-0"></p>
                        </div>

                        <div class="form-group">
                            <label for="bulk_payment_account_id" class="font-weight-bold">{{ __('Payment Account') }} *</label>
                            <select name="payment_account_id" id="bulk_payment_account_id" class="form-control" required>
                                <option value="">{{ __('Select Payment Account') }}</option>
                                @foreach($paymentAccounts as $account)
                                    <option value="{{ $account->id }}" data-balance="{{ $account->balance }}">
                                        {{ $account->name }} - ${{ number_format($account->balance, 2) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Balance Information in Bulk Modal -->
                        <div class="alert alert-info">
                            <div class="row">
                                <div class="col-6">
                                    <small class="font-weight-bold">{{ __('Company Wallet') }}</small>
                                    <div id="bulkModalCompanyBalance" class="font-weight-bold">Loading...</div>
                                </div>
                                <div class="col-6">
                                    <small class="font-weight-bold">{{ __('Payment Account') }}</small>
                                    <div id="bulkModalPaymentAccountBalance" class="font-weight-bold">-</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Cancel') }}</button>
                        <button type="submit" class="btn btn-success">{{ __('Confirm Bulk Payment') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Load company balance on page load
        document.addEventListener('DOMContentLoaded', function() {
            loadCompanyBalance();
        });

        // Load company balance
        function loadCompanyBalance() {
            // Check if company balance elements exist before trying to update them
            const companyBalanceEl = document.getElementById('companyBalance');
            const modalCompanyBalanceEl = document.getElementById('modalCompanyBalance');
            const bulkModalCompanyBalanceEl = document.getElementById('bulkModalCompanyBalance');

            if (!companyBalanceEl && !modalCompanyBalanceEl && !bulkModalCompanyBalanceEl) {
                console.log('Company balance elements not found, skipping balance load');
                return;
            }

            fetch('{{ route("admins.hr.salaries.company-balance") }}')
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    const companyBalance = '$' + parseFloat(data.company_balance).toLocaleString('en-US', {minimumFractionDigits: 2});

                    // Only update elements that exist
                    if (companyBalanceEl) companyBalanceEl.textContent = companyBalance;
                    if (modalCompanyBalanceEl) modalCompanyBalanceEl.textContent = companyBalance;
                    if (bulkModalCompanyBalanceEl) bulkModalCompanyBalanceEl.textContent = companyBalance;

                    // Update company name if element exists
                    const companyNameEl = document.getElementById('companyName');
                    if (companyNameEl) companyNameEl.textContent = data.company_name;
                })
                .catch(error => {
                    console.error('Error loading company balance:', error);
                    const errorText = 'Error loading balance';
                    if (companyBalanceEl) companyBalanceEl.textContent = errorText;
                    if (modalCompanyBalanceEl) modalCompanyBalanceEl.textContent = errorText;
                    if (bulkModalCompanyBalanceEl) bulkModalCompanyBalanceEl.textContent = errorText;
                });
        }

        // Update payment account balance display when selection changes
        document.addEventListener('DOMContentLoaded', function() {
            const paymentAccountSelect = document.getElementById('modal_payment_account_id');
            const bulkPaymentAccountSelect = document.getElementById('bulk_payment_account_id');

            if (paymentAccountSelect) {
                paymentAccountSelect.addEventListener('change', function() {
                    updatePaymentAccountBalance(this, 'modal');
                });
            }

            if (bulkPaymentAccountSelect) {
                bulkPaymentAccountSelect.addEventListener('change', function() {
                    updatePaymentAccountBalance(this, 'bulk');
                });
            }
        });

        function updatePaymentAccountBalance(selectElement, type) {
            const selectedOption = selectElement.options[selectElement.selectedIndex];
            const balance = selectedOption?.getAttribute('data-balance');

            // Define balance elements based on type
            let balanceElements = [];
            if (type === 'modal') {
                balanceElements = [
                    document.getElementById('paymentAccountBalance'),
                    document.getElementById('modalPaymentAccountBalance')
                ];
            } else {
                balanceElements = [
                    document.getElementById('paymentAccountBalance'),
                    document.getElementById('bulkModalPaymentAccountBalance')
                ];
            }

            if (balance) {
                const formattedBalance = '$' + parseFloat(balance).toLocaleString('en-US', {minimumFractionDigits: 2});
                balanceElements.forEach(el => {
                    if (el) el.textContent = formattedBalance;
                });
            } else {
                const dash = '-';
                balanceElements.forEach(el => {
                    if (el) el.textContent = dash;
                });
            }
        }

        // Modal functions using Bootstrap
        function openPayModal(salaryId, userName, amount) {
            // Set modal values
            document.getElementById('modal_salary_id').value = salaryId;
            document.getElementById('modal_user_name').textContent = userName;
            document.getElementById('modal_salary_amount').textContent = '$' + parseFloat(amount).toLocaleString('en-US', {minimumFractionDigits: 2});

            // Reset payment account selection safely
            const select = document.getElementById('modal_payment_account_id');
            if (select) {
                select.selectedIndex = 0;
                updatePaymentAccountBalance(select, 'modal');
            }

            // Show modal using Bootstrap
            $('#payModal').modal('show');
        }

        function openBulkPayModal() {
            const checkboxes = document.querySelectorAll('input[name="salary_ids[]"]:checked');
            const selectedIds = Array.from(checkboxes).map(cb => cb.value);

            if (selectedIds.length === 0) {
                alert('Please select at least one salary to pay.');
                return;
            }

            const totalAmount = Array.from(checkboxes).reduce((sum, cb) => {
                const row = cb.closest('tr');
                const amountText = row.querySelector('td:nth-child(3)').textContent;
                const amount = parseFloat(amountText.replace('$', '').replace(',', ''));
                return sum + amount;
            }, 0);

            // Add hidden inputs for selected IDs
            const container = document.getElementById('bulkSalaryIds');
            if (container) {
                container.innerHTML = '';
                selectedIds.forEach(id => {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'salary_ids[]';
                    input.value = id;
                    container.appendChild(input);
                });
            }

            // Update bulk modal info safely
            const selectedCountEl = document.getElementById('bulk_selected_count');
            const totalAmountEl = document.getElementById('bulk_total_amount');

            if (selectedCountEl) selectedCountEl.textContent = selectedIds.length + ' {{ __("salaries selected") }}';
            if (totalAmountEl) totalAmountEl.textContent = '{{ __("Total amount") }}: $' + totalAmount.toLocaleString('en-US', {minimumFractionDigits: 2});

            // Reset payment account selection safely
            const select = document.getElementById('bulk_payment_account_id');
            if (select) {
                select.selectedIndex = 0;
                updatePaymentAccountBalance(select, 'bulk');
            }

            // Show modal using Bootstrap
            $('#bulkPayModal').modal('show');
        }

        // Form validation
        document.getElementById('paySalaryForm')?.addEventListener('submit', function(e) {
            const amountText = document.getElementById('modal_salary_amount').textContent;
            const amount = parseFloat(amountText.replace('$', '').replace(',', ''));
            const paymentAccountSelect = document.getElementById('modal_payment_account_id');
            const selectedOption = paymentAccountSelect?.options[paymentAccountSelect.selectedIndex];

            if (!selectedOption?.value) {
                e.preventDefault();
                alert('Please select a payment account.');
                return false;
            }

            const accountBalance = parseFloat(selectedOption.getAttribute('data-balance'));

            if (accountBalance < amount) {
                e.preventDefault();
                alert('Insufficient balance in the selected payment account. Please select a different account.');
                return false;
            }
        });

        document.getElementById('bulkPayForm')?.addEventListener('submit', function(e) {
            const totalAmountText = document.getElementById('bulk_total_amount').textContent;
            const totalAmountMatch = totalAmountText.match(/\$([\d,]+\.\d{2})/);

            if (!totalAmountMatch) {
                e.preventDefault();
                alert('Invalid total amount calculation.');
                return false;
            }

            const totalAmount = parseFloat(totalAmountMatch[1].replace(',', ''));
            const paymentAccountSelect = document.getElementById('bulk_payment_account_id');
            const selectedOption = paymentAccountSelect?.options[paymentAccountSelect.selectedIndex];

            if (!selectedOption?.value) {
                e.preventDefault();
                alert('Please select a payment account.');
                return false;
            }

            const accountBalance = parseFloat(selectedOption.getAttribute('data-balance'));

            if (accountBalance < totalAmount) {
                e.preventDefault();
                alert('Insufficient balance in the selected payment account for bulk payment. Please select a different account.');
                return false;
            }
        });

        // Selection functions
        function toggleSelectAll(source) {
            const checkboxes = document.querySelectorAll('input[name="salary_ids[]"]');
            checkboxes.forEach(checkbox => {
                const statusBadge = checkbox.closest('tr').querySelector('.badge');
                if (statusBadge && statusBadge.textContent === 'Pending') {
                    checkbox.checked = source.checked;
                }
            });
            updateBulkActionButton();
        }

        function updateBulkActionButton() {
            const checkboxes = document.querySelectorAll('input[name="salary_ids[]"]:checked');
            const bulkPayBtn = document.getElementById('bulkPayBtn');
            if (bulkPayBtn) {
                bulkPayBtn.disabled = checkboxes.length === 0;
            }
        }

        // Reset forms when modals are hidden
        $('#payModal').on('hidden.bs.modal', function () {
            const form = document.getElementById('paySalaryForm');
            if (form) form.reset();
        });

        $('#bulkPayModal').on('hidden.bs.modal', function () {
            const form = document.getElementById('bulkPayForm');
            if (form) form.reset();
        });
    </script>

    <style>
        .bg-light-blue {
            background-color: #e3f2fd;
        }
        .bg-light-green {
            background-color: #e8f5e8;
        }
        .text-blue-600 {
            color: #1e40af;
        }
        .text-blue-800 {
            color: #1e3a8a;
        }
        .text-green-600 {
            color: #059669;
        }
        .text-green-800 {
            color: #065f46;
        }
    </style>
</x-dashboard.main-layout>
