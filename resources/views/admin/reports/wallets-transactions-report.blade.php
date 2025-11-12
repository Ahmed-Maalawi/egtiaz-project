<x-dashboard.main-layout>
    <div class="container-fluid py-4">

        {{-- Filter Form --}}
        <form method="GET" action="{{ route('admins.reports.wallet-transactions.report') }}" class="row g-3 mb-4">
            <div class="col-md-3">
                <label for="user_id" class="form-label">{{ __('User') }}</label>
                <select name="user_id" id="user_id" class="form-select">
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
                <select name="status" id="status" class="form-select">
                    <option value="">{{ __('All Statuses') }}</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>{{ __('Pending') }}</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>{{ __('Completed') }}</option>
                    <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>{{ __('Failed') }}</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>{{ __('Cancelled') }}</option>
                </select>
            </div>

            <div class="col-md-2">
                <label for="from_date" class="form-label">{{ __('From Date') }}</label>
                <input type="date" name="from_date" id="from_date" class="form-control" value="{{ request('from_date') }}">
            </div>

            <div class="col-md-2">
                <label for="to_date" class="form-label">{{ __('To Date') }}</label>
                <input type="date" name="to_date" id="to_date" class="form-control" value="{{ request('to_date') }}">
            </div>

            <div class="col-12 text-end">
                <button type="submit" class="btn btn-primary">{{ __('Filter') }}</button>
                <a href="{{ route('admins.reports.wallet-transactions.report') }}" class="btn btn-secondary">{{ __('Reset') }}</a>
            </div>
        </form>

        {{-- Table --}}
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <span>{{ __('Wallet Transactions') }}</span>
                <span class="badge bg-success text-white">{{ __('Total Amount') }}: {{ number_format($totalAmount, 2) }}</span>
            </div>

            <div class="card-body" style="overflow-x: auto;">
                <table id="walletTransactionsTable" class="table table-hover align-middle text-center w-100">
                    <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>{{ __('Payment ID') }}</th>
                        <th>{{ __('User') }}</th>
                        <th>{{ __('Amount') }}</th>
                        <th>{{ __('Currency') }}</th>
                        <th>{{ __('Company') }}</th>
                        <th>{{ __('Status') }}</th>
                        <th>{{ __('Completed At') }}</th>
                        <th>{{ __('Created At') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($transactions as $transaction)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $transaction->payment_id }}</td>
                            <td>{{ $transaction->user->name ?? 'N/A' }}</td>
                            <td>{{ number_format($transaction->amount, 2) }}</td>
                            <td>{{ $transaction->currency }}</td>
                            <td>{{ $transaction->wallet->company->getTranslation('name', app()->getLocale()) }}</td>
                            <td>
                                <span class="badge text-white
                                    @if($transaction->status === 'completed') bg-success
                                    @elseif($transaction->status === 'pending') bg-warning
                                    @elseif($transaction->status === 'failed') bg-danger
                                    @else bg-secondary @endif">
                                    {{ ucfirst($transaction->status) }}
                                </span>
                            </td>
                            <td>{{ $transaction->completed_at?->format('Y-m-d H:i') ?? 'N/A' }}</td>
                            <td>{{ $transaction->created_at->format('Y-m-d H:i') }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- JS --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css">

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>

    <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>

    <script>
        $(function () {
            $('#walletTransactionsTable').DataTable({
                pageLength: 10,
                scrollX: true,
                dom: 'Blfrtip',
                buttons: [
                    {
                        extend: 'print',
                        text: '<i class="fa fa-print"></i> {{ __("Print") }}',
                        className: 'btn btn-primary'
                    },
                    {
                        extend: 'excelHtml5',
                        text: '<i class="fa fa-file-excel"></i> {{ __("Export Excel") }}',
                        className: 'btn btn-success'
                    }
                ]
            });
        });
    </script>
</x-dashboard.main-layout>
