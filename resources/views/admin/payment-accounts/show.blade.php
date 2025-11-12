<x-dashboard.main-layout>
    @php
        $local = app()->getLocale() ?? 'en';
    @endphp
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 text-gray-800">{{ __('Payment Account Details') }}</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url()->previous() }}">{{ __('Dashboard') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admins.paymentAccounts.index') }}">{{ __('Payment Accounts') }}</a></li>
                    <li class="breadcrumb-item active">{{ $paymentAccount->name }}</li>
                </ol>
            </nav>
        </div>
        <a href="{{ route('admins.paymentAccounts.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left mr-1"></i> {{ __('Back') }}
        </a>
    </div>

    <!-- Account Information Card -->
    <div class="row mb-4">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">{{ __('Account Information') }}</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-sm table-borderless">
                                <tr>
                                    <td class="font-weight-bold">{{ __('Account Name') }}:</td>
                                    <td>{{ $paymentAccount->getTranslation('name', $local) }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-sm table-borderless">
                                <tr>
                                    <td class="font-weight-bold">{{ __('Current Balance') }}:</td>
                                    <td><strong class="text-success">{{ __('SAR') . ' ' . number_format($paymentAccount->balance, 2) }}</strong></td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold">{{ __('Created At') }}:</td>
                                    <td>{{ $paymentAccount->created_at->format('M j, Y g:i A') }}</td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold">{{ __('Updated At') }}:</td>
                                    <td>{{ $paymentAccount->updated_at->format('M j, Y g:i A') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    @if($paymentAccount->description)
                        <div class="mt-3">
                            <h6 class="font-weight-bold">{{ __('Description') }}</h6>
                            <p class="mb-0">{{ $paymentAccount->getTranslation('description', $local) }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Statistics Card -->
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">{{ __('Account Statistics') }}</h6>
                </div>
                <div class="card-body">
                    <div class="text-center">
                        <div class="mb-3">
                            <div class="text-primary font-weight-bold" style="font-size: 2rem;">
                                {{ __('SAR') . ' ' .  number_format($paymentAccount->balance, 2) }}
                            </div>
                            <div class="text-muted small">{{ __('Current Balance') }}</div>
                        </div>
                    </div>
                    <hr>
                    <div class="row text-center">
                        <div class="col-6 mb-3">
                            <div class="font-weight-bold text-gray-800">{{ $totalTransactions }}</div>
                            <div class="text-muted small">{{ __('Total Transactions') }}</div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="font-weight-bold text-success">{{ __('SAR') . ' ' . number_format($totalAmount, 2) }}</div>
                            <div class="text-muted small">{{ __('Total Amount') }}</div>
                        </div>
                        <div class="col-6">
                            <div class="font-weight-bold text-info">{{ $completedTransactions }}</div>
                            <div class="text-muted small">{{ __('Completed') }}</div>
                        </div>
                        <div class="col-6">
                            <div class="font-weight-bold text-warning">{{ $pendingTransactions }}</div>
                            <div class="text-muted small">{{ __('Pending') }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Monthly Breakdown -->
    @if($monthlyBreakdown->count() > 0)
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">{{ __('Monthly Transaction Breakdown') }}</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-sm">
                        <thead>
                        <tr>
                            <th>{{ __('Month') }}</th>
                            <th>{{ __('Transactions') }}</th>
                            <th>{{ __('Total Amount') }}</th>
                            <th>{{ __('Average per Transaction') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($monthlyBreakdown as $month)
                            @php
                                $monthName = \Carbon\Carbon::createFromDate($month->year, $month->month, 1)->format('F Y');
                                $averageAmount = $month->transaction_count > 0 ? $month->total_amount / $month->transaction_count : 0;
                            @endphp
                            <tr>
                                <td>{{ $monthName }}</td>
                                <td>{{ $month->transaction_count }}</td>
                                <td class="text-success"> {{ __('SAR') . ' ' . number_format($month->total_amount, 2) }}</td>
                                <td class="text-info"> {{ __('SAR') . ' ' . number_format($averageAmount, 2) }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif

    <!-- Assigned Users Table -->
    @if($paymentAccount->users->count() > 0)
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">{{ __('Assigned Users') }}</h6>
                <span class="badge badge-primary">{{ $paymentAccount->users->count() }} {{ __('users') }}</span>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                        <thead class="thead-light">
                        <tr>
                            <th>{{ __('ID') }}</th>
                            <th>{{ __('Name') }}</th>
                            <th>{{ __('Email') }}</th>
                            <th>{{ __('Role') }}</th>
                            <th>{{ __('Status') }}</th>
                            <th>{{ __('Transactions') }}</th>
                            <th>{{ __('Total Amount') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($paymentAccount->users as $user)
                            @php
                                $userTransactions = $paymentAccount->transactions->where('created_by', $user->id);
                                $userTransactionCount = $userTransactions->count();
                                $userTotalAmount = $userTransactions->sum('amount');
                            @endphp
                            <tr>
                                <td>{{ $user->id }}</td>
                                <td>
                                    <div>
                                        <strong>{{ $user->name }}</strong>
                                    </div>
                                </td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    @if($user->roles->count() > 0)
                                        @foreach($user->roles as $role)
                                            <span class="badge badge-secondary">{{ $role->name }}</span>
                                        @endforeach
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($user->status === 'active')
                                        <span class="badge badge-success">{{ __('Active') }}</span>
                                    @else
                                        <span class="badge badge-secondary">{{ __('Inactive') }}</span>
                                    @endif
                                </td>
                                <td>
                                    @if($userTransactionCount > 0)
                                        <span class="badge badge-info">{{ $userTransactionCount }}</span>
                                    @else
                                        <span class="text-muted">0</span>
                                    @endif
                                </td>
                                <td>
                                    @if($userTotalAmount > 0)
                                        <strong class="text-success">{{ __('SAR') . ' ' . number_format($userTotalAmount, 2) }}</strong>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif

    <!-- Transaction Users Table -->
    @php
        $transactionUsers = $paymentAccount->transactions->pluck('user')->filter()->unique('id');
    @endphp

    @if($transactionUsers->count() > 0)
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">{{ __('Transaction Users') }}</h6>
                <span class="badge badge-primary">{{ $transactionUsers->count() }} {{ __('users') }}</span>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                        <thead class="thead-light">
                        <tr>
                            <th>{{ __('ID') }}</th>
                            <th>{{ __('Name') }}</th>
                            <th>{{ __('Email') }}</th>
                            <th>{{ __('Role') }}</th>
                            <th>{{ __('Status') }}</th>
                            <th>{{ __('Transactions') }}</th>
                            <th>{{ __('Total Amount') }}</th>
                            <th>{{ __('Last Activity') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($transactionUsers as $user)
                            @php
                                $userTransactions = $paymentAccount->transactions->where('user_id', $user->id);
                                $userTransactionCount = $userTransactions->count();
                                $userTotalAmount = $userTransactions->sum('amount');
                                $lastTransaction = $userTransactions->sortByDesc('created_at')->first();
                            @endphp
                            <tr>
                                <td>{{ $user->id }}</td>
                                <td>
                                    <div>
                                        <strong>{{ $user->name }}</strong>
                                    </div>
                                </td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    @if($user->roles->count() > 0)
                                        @foreach($user->roles as $role)
                                            <span class="badge badge-secondary">{{ $role->name }}</span>
                                        @endforeach
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($user->status === 'active')
                                        <span class="badge badge-success">{{ __('Active') }}</span>
                                    @else
                                        <span class="badge badge-secondary">{{ __('Inactive') }}</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge badge-info">{{ $userTransactionCount }}</span>
                                </td>
                                <td>
                                    <strong class="text-success">${{ number_format($userTotalAmount, 2) }}</strong>
                                </td>
                                <td>
                                    @if($lastTransaction)
                                        <small>{{ $lastTransaction->created_at->format('Y-m-d H:i') }}</small>
                                        <br>
                                        <small class="text-muted">
                                            @switch($lastTransaction->type)
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
                                                    {{ $lastTransaction->type }}
                                            @endswitch
                                        </small>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif
    <!-- Transactions Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">{{ __('Recent Transactions') }}</h6>
            <span class="badge badge-primary">{{ $totalTransactions }} {{ __('transactions') }}</span>
        </div>
        <div class="card-body">
            @if($paymentAccount->transactions->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                        <thead class="thead-light">
                        <tr>
                            <th>{{ __('ID') }}</th>
                            <th>{{ __('Transaction ID') }}</th>
                            <th>{{ __('Type') }}</th>
                            <th>{{ __('User') }}</th>
                            <th>{{ __('Create By') }}</th>
                            <th>{{ __('Amount') }}</th>
                            <th>{{ __('Status') }}</th>
                            <th>{{ __('Description') }}</th>
                            <th>{{ __('Date') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($paymentAccount->transactions as $transaction)
                            <tr>
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
                                    @if($transaction->user)
                                        <div>
                                            <strong>{{ $transaction->user->name }}</strong>
                                            <br>
                                            <small class="text-muted">{{ $transaction->user->email }}</small>
                                        </div>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($transaction->createdBy)
                                        <div>
                                            <strong>{{ $transaction->createdBy->name }}</strong>
                                            <br>
                                            <small class="text-muted">{{ $transaction->createdBy->email }}</small>
                                        </div>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    <strong class="text-success">${{ number_format($transaction->amount, 2) }}</strong>
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
                                    <small>{{ $transaction->created_at->format('Y-m-d H:i') }}</small>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-4">
                    <i class="fas fa-exchange-alt fa-3x text-gray-300 mb-3"></i>
                    <h5 class="text-gray-500">{{ __('No transactions found') }}</h5>
                    <p class="text-muted">{{ __('This payment account has no transactions yet.') }}</p>
                </div>
            @endif
        </div>
    </div>
</x-dashboard.main-layout>
