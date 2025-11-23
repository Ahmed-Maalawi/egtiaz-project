<x-dashboard.main-layout>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap4.min.css">

    <div class="container-fluid py-4">

        {{-- Filter Form --}}
        <form method="GET" action="{{ route('admins.reports.wallet-transactions.report') }}" class="row g-3 mb-4">
            <div class="col-md-3">
                <label for="user_id" class="form-label">{{ __('User') }}</label>
                <select name="user_id" id="user_id" class="form-control">
                    <option value="">{{ __('All Users') }}</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                            {{ $user->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3">
                <label for="status" class="form-label">{{ __('Status') }}</label>
                <select name="status" id="status" class="form-control">
                    <option value="">{{ __('All Statuses') }}</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>{{ __('Pending') }}
                    </option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>
                        {{ __('Completed') }}</option>
                    <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>{{ __('Failed') }}
                    </option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>
                        {{ __('Cancelled') }}</option>
                </select>
            </div>

            <div class="col-md-2">
                <label for="from_date" class="form-label">{{ __('From Date') }}</label>
                <input type="date" name="from_date" id="from_date" class="form-control"
                    value="{{ request('from_date') }}">
            </div>

            <div class="col-md-2">
                <label for="to_date" class="form-label">{{ __('To Date') }}</label>
                <input type="date" name="to_date" id="to_date" class="form-control" value="{{ request('to_date') }}">
            </div>

            <div class="col-12 text-end">
                <button type="submit" class="btn btn-primary">{{ __('Filter') }}</button>
                <a href="{{ route('admins.reports.wallet-transactions.report') }}"
                    class="btn btn-secondary">{{ __('Reset') }}</a>
            </div>
        </form>

        {{-- Summary Cards --}}
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card bg-info text-white">
                    <div class="card-body">
                        <h6 class="mb-0">{{ __('Total Amount') }}</h6>
                        <h3 class="mb-0">{{ number_format($totalAmount, 2) }} SAR</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <h6 class="mb-0">{{ __('Total Credit (Wallet Charges)') }}</h6>
                        <h3 class="mb-0">{{ number_format($totalCredit, 2) }} SAR</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-danger text-white">
                    <div class="card-body">
                        <h6 class="mb-0">{{ __('Total Debit (Payments)') }}</h6>
                        <h3 class="mb-0">{{ number_format($totalDebit, 2) }} SAR</h3>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tabs --}}
        <ul class="nav nav-tabs mb-3" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" data-toggle="tab" href="#all-transactions">
                    <i class="fas fa-list"></i> {{ __('All Transactions') }}
                    <span class="badge badge-primary">{{ count($transactions) }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#credit-transactions">
                    <i class="fas fa-arrow-up"></i> {{ __('Wallet Credit') }}
                    <span class="badge badge-success">{{ count($creditTransactions) }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#debit-transactions">
                    <i class="fas fa-arrow-down"></i> {{ __('Wallet Debit') }}
                    <span class="badge badge-danger">{{ count($debitTransactions) }}</span>
                </a>
            </li>
        </ul>

        {{-- Tab Content --}}
        <div class="tab-content">
            {{-- All Transactions Tab --}}
            <div class="tab-pane fade show active" id="all-transactions">
                @include('admin.reports.partials.wallet-transactions-table', [
                    'transactions' => $transactions,
                    'title' => __('All Wallet Transactions'),
                    'tableId' => 'allTransactionsTable'
                ])
            </div>

            {{-- Credit Transactions Tab --}}
            <div class="tab-pane fade" id="credit-transactions">
                @include('admin.reports.partials.wallet-transactions-table', [
                    'transactions' => $creditTransactions,
                    'title' => __('Wallet Credit Transactions (Charges)'),
                    'tableId' => 'creditTransactionsTable',
                    'showType' => false
                ])
            </div>

            {{-- Debit Transactions Tab --}}
            <div class="tab-pane fade" id="debit-transactions">
                @include('admin.reports.partials.wallet-transactions-table', [
                    'transactions' => $debitTransactions,
                    'title' => __('Wallet Debit Transactions (Payments)'),
                    'tableId' => 'debitTransactionsTable',
                    'showEmployee' => true
                ])
            </div>
        </div>
    </div>

</x-dashboard.main-layout>