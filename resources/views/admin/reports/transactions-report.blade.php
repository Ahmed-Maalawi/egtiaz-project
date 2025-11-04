@php
    $local = app()->getLocale();
@endphp
<x-dashboard.main-layout>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css">

    <div class="container-fluid py-4">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <span>{{ __('Transactions Management') }}</span>
            </div>

            <div class="card-body" style="overflow-x: auto;">

            <table id="transactionsTable" class="table table-hover align-middle text-center w-100">
                    <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>{{ __('Transaction ID') }}</th>
                        <th>{{ __('From Account') }}</th>
                        <th>{{ __('To Wallet') }}</th>
                        <th>{{ __('Type') }}</th>
                        <th>{{ __('User') }}</th>
                        <th>{{ __('Amount') }}</th>
                        <th>{{ __('Balance Before') }}</th>
                        <th>{{ __('Balance After') }}</th>
                        <th>{{ __('Status') }}</th>
                        <th>{{ __('Processed At') }}</th>
                        <th>{{ __('Created By') }}</th>
                        <th>{{ __('Created At') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($transactions as $transaction)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $transaction->transaction_id }}</td>
                            <td>{{ $transaction->fromPaymentAccount->getTranslation('name', $local) ?? 'N/A' }}</td>
                            <td>{{ $transaction->toWallet->name ?? 'N/A' }}</td>
                            <td>
                                <span class="text-white badge bg-info">
                                    {{ $transaction->transactionable_type_name  }}
                                </span>
                            </td>
                            <td>
                                @if($transaction->transactionable_type_name == 'Employee Stage')
                                    {{ $transaction?->transactionable->employee->name ?? 'N/A' }}
                                @elseif($transaction->transactionable_type_name == 'Salary')
                                    {{ $transaction?->transactionable->user->name ?? 'N/A' }}
                                @else
                                    {{ __('N/A') }}
                                @endif
                            </td>
                            <td>{{ number_format($transaction->amount ?? 0, 2) }}</td>
                            <td>{{ number_format($transaction->from_balance_before ?? 0, 2) }}</td>
                            <td>{{ number_format($transaction->from_balance_after  ?? 0, 2) }}</td>
                            <td>
                                @php
                                    $statusColors = [
                                        'pending' => 'warning',
                                        'completed' => 'success',
                                        'failed' => 'danger',
                                        'refund' => 'secondary',
                                        'canceled' => 'dark'
                                    ];
                                @endphp
                                <span class="text-white badge bg-{{ $statusColors[$transaction->status] ?? 'light' }}">
                                        {{ ucfirst($transaction->status) }}
                                    </span>
                            </td>
                            <td>{{ $transaction->processed_at?->format('Y-m-d H:i') ?? 'N/A' }}</td>
                            <td>{{ $transaction?->createdBy->name ?? 'System' }}</td>
                            <td>{{ $transaction->created_at->format('Y-m-d H:i') }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- JS --}}
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>

    <script>
        $(function () {
            $('#transactionsTable').DataTable({
                pageLength: 3,
                scrollX: true,
                dom: 'Blfrtip',
                buttons: [
                    {
                        extend: 'print',
                        text: '<i class="fa fa-print"></i> {{ __("Print") }}',
                        className: 'btn btn-primary',
                        title: '{{ __("Transactions Report") }}'
                    },
                    {
                        extend: 'excelHtml5',
                        text: '<i class="fa fa-file-excel"></i> {{ __("Export Excel") }}',
                        className: 'btn btn-success',
                        title: 'Transactions_Report_{{ now()->format("Y_m_d") }}'
                    }
                ]
            });
        });
    </script>
</x-dashboard.main-layout>
