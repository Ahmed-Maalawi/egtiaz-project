<x-dashboard.main-layout>
    @php
        $rev_locale = app()->getLocale() == 'en' ? 'ar' : 'en';
    @endphp
    <h1 class="mb-3 text-gray-800 h3">{{ __('Transactions') }}</h1>

    <!-- Filters Section -->
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <h6 class="m-0 font-weight-bold">{{ __('Filter Transactions') }}</h6>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admins.transactions.index') }}" class="row">
                <div class="col-md-3 mb-3">
                    <label for="type" class="form-label">{{ __('Type') }}</label>
                    <select name="type" id="type" class="form-control">
                        <option value="">{{ __('All Types') }}</option>
                        <option value="stage_payment" {{ request('type') == 'stage_payment' ? 'selected' : '' }}>{{ __('Stage Payment') }}</option>
                        <option value="salary_payment" {{ request('type') == 'salary_payment' ? 'selected' : '' }}>{{ __('Salary Payment') }}</option>
                        <option value="refund" {{ request('type') == 'refund' ? 'selected' : '' }}>{{ __('Refund') }}</option>
                        <option value="charge" {{ request('type') == 'charge' ? 'selected' : '' }}>{{ __('Charge') }}</option>
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label for="status" class="form-label">{{ __('Status') }}</label>
                    <select name="status" id="status" class="form-control">
                        <option value="">{{ __('All Statuses') }}</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>{{ __('Pending') }}</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>{{ __('Completed') }}</option>
                        <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>{{ __('Failed') }}</option>
                        <option value="refund" {{ request('status') == 'refund' ? 'selected' : '' }}>{{ __('Refunded') }}</option>
                        <option value="canceled" {{ request('status') == 'canceled' ? 'selected' : '' }}>{{ __('Canceled') }}</option>
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label for="date_from" class="form-label">{{ __('From Date') }}</label>
                    <input type="date" name="date_from" id="date_from" class="form-control" value="{{ request('date_from') }}">
                </div>
                <div class="col-md-3 mb-3">
                    <label for="date_to" class="form-label">{{ __('To Date') }}</label>
                    <input type="date" name="date_to" id="date_to" class="form-control" value="{{ request('date_to') }}">
                </div>
                <div class="col-md-12">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-filter mr-1"></i> {{ __('Filter') }}
                        </button>
                        <a href="{{ route('admins.transactions.index') }}" class="btn btn-secondary">
                            <i class="fas fa-redo mr-1"></i> {{ __('Reset') }}
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                {{ __('Total Transactions') }}</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $transactions->total() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-exchange-alt fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                {{ __('Total Amount') }}</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                ${{ number_format($transactions->sum('amount'), 2) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                {{ __('Completed') }}</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $transactions->where('status', 'completed')->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                {{ __('Pending') }}</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $transactions->where('status', 'pending')->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Transactions Table -->
    <div class="mb-4 shadow card">
        <div class="py-3 card-header d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">{{ __('All Transactions') }}</h6>
            <div class="text-muted small">
                {{ __('Showing') }} {{ $transactions->firstItem() }} - {{ $transactions->lastItem() }}
                {{ __('of') }} {{ $transactions->total() }} {{ __('transactions') }}
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                    <thead class="thead-light">
                    <tr>
                        <th>{{ __('ID') }}</th>
                        <th>{{ __('Transaction ID') }}</th>
                        <th>{{ __('Type') }}</th>
                        <th>{{ __('Employee') }}</th>
                        <th>{{ __('Amount') }}</th>
                        <th>{{ __('Payment Account') }}</th>
                        <th>{{ __('Status') }}</th>
                        <th>{{ __('Description') }}</th>
                        <th>{{ __('Processed At') }}</th>
                        <th>{{ __('Actions') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($transactions as $transaction)
                        <tr data-id="{{ $transaction->id }}">
                            <td>{{ $transaction->id }}</td>
                            <td>
                                <small class="text-muted">{{ Str::limit($transaction->transaction_id, 8) }}</small>
                            </td>
                            <td>
                                    <span class="badge badge-info">
                                        @switch($transaction->type)
                                            @case('stage_payment')
                                                {{ __('Stage Payment') }}
                                                @break
                                            @case('salary_payment')
                                                {{ __('Salary Payment') }}
                                                @break
                                            @case('refund')
                                                {{ __('Refund') }}
                                                @break
                                            @case('charge')
                                                {{ __('Charge') }}
                                                @break
                                            @default
                                                {{ $transaction->type }}
                                        @endswitch
                                    </span>
                            </td>
                            <td>
                                @if($transaction->employee)
                                    <div>
                                        <strong>{{ $transaction->employee->name }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $transaction->employee->email }}</small>
                                    </div>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                <strong class="text-success">${{ number_format($transaction->amount, 2) }}</strong>
                            </td>
                            <td>
                                @if($transaction->paymentAccount)
                                    {{ $transaction->paymentAccount->name }}
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @switch($transaction->status)
                                    @case('pending')
                                        <span class="badge badge-warning">{{ __('Pending') }}</span>
                                        @break
                                    @case('completed')
                                        <span class="badge badge-success">{{ __('Completed') }}</span>
                                        @break
                                    @case('failed')
                                        <span class="badge badge-danger">{{ __('Failed') }}</span>
                                        @break
                                    @case('refund')
                                        <span class="badge badge-info">{{ __('Refunded') }}</span>
                                        @break
                                    @case('canceled')
                                        <span class="badge badge-secondary">{{ __('Canceled') }}</span>
                                        @break
                                    @default
                                        <span class="badge badge-light">{{ $transaction->status }}</span>
                                @endswitch
                            </td>
                            <td>
                                <small title="{{ $transaction->description }}">
                                    {{ Str::limit($transaction->description, 50) }}
                                </small>
                            </td>
                            <td>
                                @if($transaction->processed_at)
                                    <small>{{ $transaction->processed_at->format('Y-m-d H:i') }}</small>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex justify-content-center gap-1">
                                    <!-- View Details Button -->
                                    <button type="button" class="btn btn-info btn-sm" onclick="viewTransactionDetails({{ $transaction->id }})"
                                            title="{{ __('View Details') }}">
                                        <i class="fas fa-eye"></i>
                                    </button>

                                    <!-- Refund Button (for completed transactions) -->
                                    @if($transaction->status === 'completed' && in_array($transaction->type, ['stage_payment', 'salary_payment']))
                                        <button type="button" class="btn btn-warning btn-sm"
                                                onclick="confirmRefund({{ $transaction->id }})"
                                                title="{{ __('Refund') }}">
                                            <i class="fas fa-undo"></i>
                                        </button>
                                    @endif

                                    <!-- Cancel Button (for pending transactions) -->
                                    @if($transaction->status === 'pending')
                                        <button type="button" class="btn btn-danger btn-sm"
                                                onclick="confirmCancel({{ $transaction->id }})"
                                                title="{{ __('Cancel') }}">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="py-2 d-flex justify-content-center">
            {{ $transactions->links() }}
        </div>
    </div>

    <!-- Transaction Details Modal -->
    <div class="modal fade" id="transactionDetailsModal" tabindex="-1" role="dialog" aria-labelledby="transactionDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="transactionDetailsModalLabel">{{ __('Transaction Details') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="transactionDetailsContent">
                    <!-- Details will be loaded via AJAX -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function viewTransactionDetails(transactionId) {
            // Show loading
            $('#transactionDetailsContent').html(`
                <div class="text-center">
                    <div class="spinner-border text-primary" role="status">
                        <span class="sr-only">{{ __('Loading...') }}</span>
                    </div>
                    <p>{{ __('Loading transaction details...') }}</p>
                </div>
            `);

            $('#transactionDetailsModal').modal('show');

            // Load transaction details via AJAX
            fetch(`/admin/transactions/${transactionId}/details`)
                .then(response => response.json())
                .then(data => {
                    $('#transactionDetailsContent').html(`
                        <div class="row">
                            <div class="col-md-6">
                                <h6>{{ __('Basic Information') }}</h6>
                                <table class="table table-sm">
                                    <tr>
                                        <td><strong>{{ __('Transaction ID') }}:</strong></td>
                                        <td>${data.transaction_id}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>{{ __('Type') }}:</strong></td>
                                        <td>${data.type_display}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>{{ __('Status') }}:</strong></td>
                                        <td>${data.status_display}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>{{ __('Amount') }}:</strong></td>
                                        <td><strong class="text-success">$${data.amount}</strong></td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <h6>{{ __('Balance Information') }}</h6>
                                <table class="table table-sm">
                                    <tr>
                                        <td><strong>{{ __('Balance Before') }}:</strong></td>
                                        <td>$${data.from_balance_before}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>{{ __('Balance After') }}:</strong></td>
                                        <td>$${data.from_balance_after}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>{{ __('Difference') }}:</strong></td>
                                        <td class="text-danger">-$${data.amount}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-12">
                                <h6>{{ __('Description') }}</h6>
                                <p>${data.description || '{{ __("No description provided") }}'}</p>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-6">
                                <h6>{{ __('Timestamps') }}</h6>
                                <table class="table table-sm">
                                    <tr>
                                        <td><strong>{{ __('Created') }}:</strong></td>
                                        <td>${data.created_at}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>{{ __('Processed') }}:</strong></td>
                                        <td>${data.processed_at || '{{ __("Not processed") }}'}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    `);
                })
                .catch(error => {
                    $('#transactionDetailsContent').html(`
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-triangle mr-2"></i>
                            {{ __('Failed to load transaction details.') }}
                    </div>
`);
                    console.error('Error loading transaction details:', error);
                });
        }

        function confirmRefund(transactionId) {
            Swal.fire({
                title: "{{ __('Are you sure?') }}",
                text: "{{ __('This transaction will be refunded.') }}",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#ffc107",
                cancelButtonColor: "#6c757d",
                confirmButtonText: "{{ __('Yes, refund it!') }}",
                cancelButtonText: "{{ __('Cancel') }}"
            }).then((result) => {
                if (result.isConfirmed) {
                    // Submit refund form
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `/admin/transactions/${transactionId}/refund`;

                    const csrfToken = document.createElement('input');
                    csrfToken.type = 'hidden';
                    csrfToken.name = '_token';
                    csrfToken.value = '{{ csrf_token() }}';

                    form.appendChild(csrfToken);
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }

        function confirmCancel(transactionId) {
            Swal.fire({
                title: "{{ __('Are you sure?') }}",
                text: "{{ __('This transaction will be canceled.') }}",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#dc3545",
                cancelButtonColor: "#6c757d",
                confirmButtonText: "{{ __('Yes, cancel it!') }}",
                cancelButtonText: "{{ __('Cancel') }}"
            }).then((result) => {
                if (result.isConfirmed) {
                    // Submit cancel form
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `/admin/transactions/${transactionId}/cancel`;

                    const csrfToken = document.createElement('input');
                    csrfToken.type = 'hidden';
                    csrfToken.name = '_token';
                    csrfToken.value = '{{ csrf_token() }}';

                    form.appendChild(csrfToken);
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }

        // Auto-submit filter form when select changes (optional)
        document.addEventListener('DOMContentLoaded', function() {
            const typeSelect = document.getElementById('type');
            const statusSelect = document.getElementById('status');

            if (typeSelect) {
                typeSelect.addEventListener('change', function() {
                    this.form.submit();
                });
            }

            if (statusSelect) {
                statusSelect.addEventListener('change', function() {
                    this.form.submit();
                });
            }
        });
    </script>

    <style>
        .table td {
            vertical-align: middle;
        }
        .badge {
            font-size: 0.75em;
        }
        .w_200 {
            width: 200px;
        }
    </style>
</x-dashboard.main-layout>
