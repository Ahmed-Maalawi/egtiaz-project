<div class="card shadow-sm">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0">{{ $title }}</h5>
    </div>
    <div class="card-body">
        <table id="{{ $tableId }}" class="table table-hover align-middle text-center w-100">
            <thead class="table-light">
            <tr>
                <th>#</th>
                <th>{{ __('Transaction ID') }}</th>
                <th>{{ __('User') }}</th>
                @if($showCompany ?? false)
                    <th>{{ __('Company') }}</th>
                @endif
                @if($showEmployee ?? false)
                    <th>{{ __('Employee') }}</th>
                @endif
                <th>{{ __('Amount (SAR)') }}</th>
                <th>{{ __('Type') }}</th>
                <th>{{ __('Status') }}</th>
                <th>{{ __('Payment Method') }}</th>
                <th>{{ __('Created At') }}</th>
                <th>{{ __('Processed At') }}</th>
            </tr>
            </thead>
            <tbody>
            @foreach($transactions as $transaction)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $transaction->payment_id ?? 'N/A' }}</td>
                    <td>{{ $transaction->user->name ?? 'N/A' }}</td>
                    @if($showCompany ?? false)
                        <td>{{ $transaction->wallet->company->name ?? 'N/A' }}</td>
                    @endif
                    @if($showEmployee ?? false)
                        <td>
                            @if($transaction->type == 'stage_payment')
                                {{ $transaction->employeeStage->employee->name ?? 'N/A' }}
                            @else
                                N/A
                            @endif
                        </td>
                    @endif
                    <td>{{ number_format($transaction->amount, 2) }}</td>
                    <td>
                        @if($transaction->type == 'stage_payment')
                            <span class="badge badge-info">{{ __('Stage Payment') }}</span>
                        @else
                            <span class="badge badge-success">{{ __('Wallet Charge') }}</span>
                        @endif
                    </td>
                    <td>
                        @php
                            $statusColors = [
                                'pending' => 'warning',
                                'completed' => 'success',
                                'failed' => 'danger',
                                'cancelled' => 'secondary'
                            ];
                        @endphp
                        <span class="badge badge-{{ $statusColors[$transaction->status] ?? 'light' }}">
                            {{ ucfirst($transaction->status) }}
                        </span>
                    </td>
                    <td>{{ $transaction->type == 'stage_payment' ? __('Debit') : __('Credit') }}</td>
                    <td>{{ $transaction->created_at->format('Y-m-d H:i') }}</td>
                    <td>{{ $transaction->completed_at?->format('Y-m-d H:i') ?? '-' }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
