<x-dashboard.main-layout>

    <div class="container-fluid">

        {{-- Company Header --}}
        <div class="card mb-4">
            <div class="card-body d-flex align-items-center">
                <img src="{{ asset('storage/' . $company->image) }}"
                     alt="Company Image"
                     class="rounded mr-3"
                     width="120">

                <div class="flex-grow-1">
                    <h3 class="mb-1">
                        {{ $company->getTranslation('name', app()->getLocale()) }}
                    </h3>

                    <p class="text-muted mb-2">
                        {{ $company->getTranslation('description', app()->getLocale()) }}
                    </p>

                    <span class="badge badge-{{ $company->status == 'active' ? 'success' : 'danger' }}">
                        {{ ucfirst($company->status) }}
                    </span>
                </div>

                <div class="text-right">
                    <a href="{{ route('admins.companies.profit-report', $company->id) }}" class="btn btn-primary">
                        <i class="fas fa-chart-line"></i> {{ __('Detailed Report') }}
                    </a>
                </div>
            </div>
        </div>

        {{-- Summary Statistics --}}
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card bg-info text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-0">{{ __('Total Employees') }}</h6>
                                <h3 class="mb-0">{{ $summary['total_employees'] }}</h3>
                            </div>
                            <i class="fas fa-users fa-2x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card bg-warning text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-0">{{ __('Total Cost') }}</h6>
                                <h3 class="mb-0">{{ number_format($summary['total_cost'], 2) }}</h3>
                                <small>{{ __('SAR') }}</small>
                            </div>
                            <i class="fas fa-money-bill-wave fa-2x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-0">{{ __('Total Revenue') }}</h6>
                                <h3 class="mb-0">{{ number_format($summary['total_price'], 2) }}</h3>
                                <small>{{ __('SAR') }}</small>
                            </div>
                            <i class="fas fa-dollar-sign fa-2x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-0">{{ __('Total Profit') }}</h6>
                                <h3 class="mb-0">{{ number_format($summary['total_profit'], 2) }}</h3>
                                <small>{{ number_format($summary['profit_margin'], 1) }}% {{ __('margin') }}</small>
                            </div>
                            <i class="fas fa-chart-line fa-2x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Wallet Information --}}
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-wallet"></i> {{ __('Current Wallet Balance') }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h2 class="mb-0">{{ number_format($summary['current_wallet_balance'], 2) }} {{ __('SAR') }}</h2>
                                <small class="text-muted">{{ __('Available Balance') }}</small>
                            </div>
                            <div class="text-right">
                                <p class="mb-1"><strong>{{ __('Total Charged:') }}</strong> {{ number_format($summary['total_wallet_charges'], 2) }} SAR</p>
                                <p class="mb-0"><strong>{{ __('Total Spent:') }}</strong> {{ number_format($summary['total_price'], 2) }} SAR</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0"><i class="fas fa-chart-pie"></i> {{ __('Completed Stages') }}</h5>
                    </div>
                    <div class="card-body">
                        <h2 class="mb-0">{{ $summary['total_stages_completed'] }}</h2>
                        <small class="text-muted">{{ __('Total stages completed for all employees') }}</small>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tabs for Different Sections --}}
        <ul class="nav nav-tabs mb-3" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" data-toggle="tab" href="#wallet-transactions">
                    <i class="fas fa-receipt"></i> {{ __('Wallet Transactions') }}
                    <span class="badge badge-primary">{{ $walletTransactions->total() }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#payment-transactions">
                    <i class="fas fa-exchange-alt"></i> {{ __('Payment Account Transactions') }}
                    <span class="badge badge-info">{{ $paymentTransactions->total() }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#employee-profits">
                    <i class="fas fa-user-chart"></i> {{ __('Employee Profits') }}
                    <span class="badge badge-success">{{ $employeeProfits->count() }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#employees">
                    <i class="fas fa-users"></i> {{ __('All Employees') }}
                    <span class="badge badge-secondary">{{ $company->employees->count() }}</span>
                </a>
            </li>
        </ul>

        {{-- Tab Content --}}
        <div class="tab-content">

            {{-- Wallet Transactions Tab --}}
            <div class="tab-pane fade show active" id="wallet-transactions">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">{{ __('Wallet Transactions (Company Payments)') }}</h5>
                        <button class="btn btn-sm btn-outline-primary" onclick="exportWalletTransactions()">
                            <i class="fas fa-download"></i> {{ __('Export') }}
                        </button>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered">
                                <thead class="thead-light">
                                <tr>
                                    <th>{{ __('ID') }}</th>
                                    <th>{{ __('Date') }}</th>
                                    <th>{{ __('Employee') }}</th>
                                    <th>{{ __('Stage') }}</th>
                                    <th>{{ __('Amount (Price)') }}</th>
                                    <th>{{ __('Cost') }}</th>
                                    <th>{{ __('Profit') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th>{{ __('Processed By') }}</th>
                                    <th>{{ __('Actions') }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse ($walletTransactions as $transaction)
                                    @php
                                        $cost = 0;
                                        $profit = 0;

                                        if ($transaction->employeeStage) {
                                            $cost = $transaction->employeeStage->amount_cost ?? 0;
                                            $profit = $transaction->amount - $cost;
                                        }
                                    @endphp
                                    <tr>
                                        <td><strong>#{{ $transaction->id }}</strong></td>
                                        <td>{{ $transaction->completed_at ? $transaction->completed_at->format('Y-m-d H:i') : $transaction->created_at->format('Y-m-d H:i') }}</td>

                                        <td>
                                            @if($transaction->employeeStage && $transaction->employeeStage->employee)
                                                <a href="{{ route('admins.employees.show', $transaction->employeeStage->employee->id) }}" class="text-decoration-none">
                                                    <i class="fas fa-user"></i> {{ $transaction->employeeStage->employee->name }}
                                                </a>
                                            @else
                                                <span class="text-muted">{{ __('Wallet Charge') }}</span>
                                            @endif
                                        </td>

                                        <td>
                                            @if($transaction->employeeStage && $transaction->employeeStage->stage)
                                                <span class="badge badge-info">{{ $transaction->employeeStage->stage->name }}</span>
                                            @else
                                                <span class="text-muted">—</span>
                                            @endif
                                        </td>

                                        <td>
                                            <span class="text-primary font-weight-bold">{{ number_format($transaction->amount, 2) }} SAR</span>
                                        </td>

                                        <td>
                                            @if($cost > 0)
                                                <span class="text-danger">{{ number_format($cost, 2) }} SAR</span>
                                            @else
                                                <span class="text-muted">—</span>
                                            @endif
                                        </td>

                                        <td>
                                            @if($profit > 0)
                                                <span class="text-success font-weight-bold">
                                                        {{ number_format($profit, 2) }} SAR
                                                    </span>
                                            @else
                                                <span class="text-muted">—</span>
                                            @endif
                                        </td>

                                        <td>
                                                <span class="badge badge-{{ $transaction->status == 'completed' ? 'success' : 'warning' }}">
                                                    {{ ucfirst($transaction->status) }}
                                                </span>
                                        </td>

                                        <td>
                                            @if($transaction->user)
                                                <small>{{ $transaction->user->name }}</small>
                                            @else
                                                <span class="text-muted">—</span>
                                            @endif
                                        </td>

                                        <td>
                                            <button class="btn btn-sm btn-info" onclick="viewTransactionDetails({{ $transaction->id }}, 'wallet')">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <a href="{{ route('admins.invoice.download', $transaction->id) }}" class="btn btn-sm btn-primary" target="_blank">
                                                <i class="fas fa-download"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="text-center text-muted py-4">
                                            {{ __('No wallet transactions available.') }}
                                        </td>
                                    </tr>
                                @endforelse
                                </tbody>
                                <tfoot class="bg-light">
                                <tr>
                                    <th colspan="4" class="text-right">{{ __('Totals:') }}</th>
                                    <th class="text-primary">{{ number_format($walletTransactions->sum('amount'), 2) }} SAR</th>
                                    <th class="text-danger">
                                        {{ number_format($walletTransactions->sum(function($t) {
                                            return $t->employeeStage ? $t->employeeStage->amount_cost ?? 0 : 0;
                                        }), 2) }} SAR
                                    </th>
                                    <th class="text-success">
                                        {{ number_format($walletTransactions->sum(function($t) {
                                            $cost = $t->employeeStage ? $t->employeeStage->amount_cost ?? 0 : 0;
                                            return $t->amount - $cost;
                                        }), 2) }} SAR
                                    </th>
                                    <th colspan="3"></th>
                                </tr>
                                </tfoot>
                            </table>
                        </div>

                        <div class="mt-3">
                            {{ $walletTransactions->links() }}
                        </div>
                    </div>
                </div>
            </div>

            {{-- Payment Account Transactions Tab --}}
            <div class="tab-pane fade" id="payment-transactions">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">{{ __('Payment Account Transactions (Processing Costs)') }}</h5>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered">
                                <thead class="thead-light">
                                <tr>
                                    <th>{{ __('ID') }}</th>
                                    <th>{{ __('Date') }}</th>
                                    <th>{{ __('Employee') }}</th>
                                    <th>{{ __('Stage') }}</th>
                                    <th>{{ __('Payment Account') }}</th>
                                    <th>{{ __('Amount (Cost)') }}</th>
                                    <th>{{ __('Balance Before') }}</th>
                                    <th>{{ __('Balance After') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th>{{ __('Processed By') }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse ($paymentTransactions as $transaction)
                                    <tr>
                                        <td><strong>#{{ $transaction->id }}</strong></td>
                                        <td>{{ $transaction->processed_at ? $transaction->processed_at->format('Y-m-d H:i') : $transaction->created_at->format('Y-m-d H:i') }}</td>

                                        <td>
                                            @if($transaction->employeeStage && $transaction->employeeStage->employee)
                                                <a href="{{ route('admins.employees.show', $transaction->employeeStage->employee->id) }}">
                                                    {{ $transaction->employeeStage->employee->name }}
                                                </a>
                                            @else
                                                <span class="text-muted">—</span>
                                            @endif
                                        </td>

                                        <td>
                                            @if($transaction->employeeStage && $transaction->employeeStage->stage)
                                                <span class="badge badge-secondary">{{ $transaction->employeeStage->stage->name }}</span>
                                            @else
                                                <span class="text-muted">—</span>
                                            @endif
                                        </td>

                                        <td>
                                            @if($transaction->paymentAccount)
                                                <small>{{ $transaction->paymentAccount->name ?? 'Account #' . $transaction->payment_account_id }}</small>
                                            @else
                                                <span class="text-muted">—</span>
                                            @endif
                                        </td>

                                        <td>
                                            <span class="text-danger font-weight-bold">{{ number_format($transaction->amount, 2) }} SAR</span>
                                        </td>

                                        <td>{{ number_format($transaction->from_balance_before ?? 0, 2) }} SAR</td>
                                        <td>{{ number_format($transaction->from_balance_after ?? 0, 2) }} SAR</td>

                                        <td>
                                                <span class="badge badge-{{ $transaction->status == 'completed' ? 'success' : 'warning' }}">
                                                    {{ ucfirst($transaction->status) }}
                                                </span>
                                        </td>

                                        <td>
                                            @if($transaction->createdBy)
                                                <small>{{ $transaction->createdBy->name }}</small>
                                            @else
                                                <span class="text-muted">—</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="text-center text-muted py-4">
                                            {{ __('No payment transactions available.') }}
                                        </td>
                                    </tr>
                                @endforelse
                                </tbody>
                                <tfoot class="bg-light">
                                <tr>
                                    <th colspan="5" class="text-right">{{ __('Total Cost:') }}</th>
                                    <th class="text-danger">{{ number_format($paymentTransactions->sum('amount'), 2) }} SAR</th>
                                    <th colspan="4"></th>
                                </tr>
                                </tfoot>
                            </table>
                        </div>

                        <div class="mt-3">
                            {{ $paymentTransactions->links() }}
                        </div>
                    </div>
                </div>
            </div>

            {{-- Employee Profits Tab --}}
            <div class="tab-pane fade" id="employee-profits">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">{{ __('Profit Analysis by Employee') }}</h5>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered">
                                <thead class="thead-light">
                                <tr>
                                    <th>{{ __('Employee') }}</th>
                                    <th>{{ __('Completed Stages') }}</th>
                                    <th>{{ __('Total Cost') }}</th>
                                    <th>{{ __('Total Price') }}</th>
                                    <th>{{ __('Total Profit') }}</th>
                                    <th>{{ __('Profit Margin') }}</th>
                                    <th>{{ __('Actions') }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse ($employeeProfits as $item)
                                    @php
                                        $margin = $item['total_price'] > 0 ? ($item['total_profit'] / $item['total_price']) * 100 : 0;
                                    @endphp
                                    <tr>
                                        <td>
                                            <a href="{{ route('admins.employees.show', $item['employee']->id) }}" class="font-weight-bold">
                                                {{ $item['employee']->name }}
                                            </a>
                                        </td>
                                        <td>
                                            <span class="badge badge-info">{{ $item['completed_stages'] }}</span>
                                        </td>
                                        <td class="text-danger">{{ number_format($item['total_cost'], 2) }} SAR</td>
                                        <td class="text-primary">{{ number_format($item['total_price'], 2) }} SAR</td>
                                        <td class="text-success font-weight-bold">{{ number_format($item['total_profit'], 2) }} SAR</td>
                                        <td>
                                                <span class="badge badge-{{ $margin >= 20 ? 'success' : ($margin >= 10 ? 'warning' : 'danger') }}">
                                                    {{ number_format($margin, 1) }}%
                                                </span>
                                        </td>
                                        <td>
                                            <a href="{{ route('admins.employees.show', $item['employee']->id) }}" class="btn btn-sm btn-info">
                                                <i class="fas fa-eye"></i> {{ __('View') }}
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-muted py-4">
                                            {{ __('No completed stages yet.') }}
                                        </td>
                                    </tr>
                                @endforelse
                                </tbody>
                                <tfoot class="bg-light">
                                <tr>
                                    <th>{{ __('TOTAL') }}</th>
                                    <th>{{ $employeeProfits->sum('completed_stages') }}</th>
                                    <th class="text-danger">{{ number_format($employeeProfits->sum('total_cost'), 2) }} SAR</th>
                                    <th class="text-primary">{{ number_format($employeeProfits->sum('total_price'), 2) }} SAR</th>
                                    <th class="text-success">{{ number_format($employeeProfits->sum('total_profit'), 2) }} SAR</th>
                                    <th colspan="2"></th>
                                </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            {{-- All Employees Tab --}}
            <div class="tab-pane fade" id="employees">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">{{ __('All Employees') }}</h5>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th>{{ __('ID') }}</th>
                                    <th>{{ __('Name') }}</th>
                                    <th>{{ __('Phone') }}</th>
                                    <th>{{ __('Email') }}</th>
                                    <th>{{ __('Total Stages') }}</th>
                                    <th>{{ __('Completed') }}</th>
                                    <th>{{ __('Actions') }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse ($company->employees as $employee)
                                    <tr>
                                        <td>{{ $employee->id }}</td>
                                        <td>{{ $employee->name }}</td>
                                        <td>{{ $employee->phone }}</td>
                                        <td>{{ $employee->email }}</td>
                                        <td>{{ $employee->stages->count() }}</td>
                                        <td>
                                                <span class="badge badge-success">
                                                    {{ $employee->stages->where('status', 'completed')->count() }}
                                                </span>
                                        </td>
                                        <td>
                                            <a href="{{ route('admins.employees.show', $employee->id) }}" class="btn btn-sm btn-info">
                                                <i class="fas fa-eye"></i> {{ __('Details') }}
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-muted">
                                            {{ __('No employees found for this company.') }}
                                        </td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>

    {{-- Transaction Details Modal --}}
    <div class="modal fade" id="transactionModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('Transaction Details') }}</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body" id="transactionDetails">
                    <div class="text-center">
                        <div class="spinner-border" role="status"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

{{--    @push('scripts')--}}

{{--    @endpush--}}

</x-dashboard.main-layout>
<script>
    function viewTransactionDetails(transactionId, type) {
        $('#transactionModal').modal('show');

        $.ajax({
            url: `/admin/transactions/${type}/${transactionId}/details`,
            method: 'GET',
            success: function(response) {
                let html = `
                        <div class="row">
                            <div class="col-12">
                                <h6><strong>${response.employee_name}</strong> - ${response.stage_name}</h6>
                                <hr>
                            </div>
                            <div class="col-md-6">
                                <div class="card border-danger">
                                    <div class="card-header bg-danger text-white">
                                        <h6>Cost (Payment Account)</h6>
                                    </div>
                                    <div class="card-body">
                                        <p><strong>Amount:</strong> ${response.cost} SAR</p>
                                        <p><strong>Account:</strong> ${response.payment_account}</p>
                                        <p><strong>Status:</strong> ${response.status}</p>
                                        <p><strong>Date:</strong> ${response.date}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card border-primary">
                                    <div class="card-header bg-primary text-white">
                                        <h6>Price (Company Wallet)</h6>
                                    </div>
                                    <div class="card-body">
                                        <p><strong>Amount:</strong> ${response.price} SAR</p>
                                        <p><strong>Wallet Balance:</strong> ${response.wallet_balance} SAR</p>
                                        <p><strong>Status:</strong> ${response.status}</p>
                                        <p><strong>Date:</strong> ${response.date}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="alert alert-success mt-3">
                            <h5><strong>Profit:</strong> ${response.profit} SAR</h5>
                        </div>
                    `;

                $('#transactionDetails').html(html);
            },
            error: function() {
                $('#transactionDetails').html('<div class="alert alert-danger">Error loading transaction details</div>');
            }
        });
    }

    function exportWalletTransactions() {
        window.location.href = '{{ route("admins.companies.export-transactions", $company->id) }}';
    }
</script>
