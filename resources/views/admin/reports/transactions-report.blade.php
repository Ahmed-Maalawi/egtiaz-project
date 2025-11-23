@php
    $local = app()->getLocale();
@endphp
<x-dashboard.main-layout>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

    <div class="container-fluid py-4">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <span>{{ __('Transactions Management') }}</span>
                <div>
                    {{-- Active Filters Badge --}}
                    @php
                        $activeFilterCount = 0;
                        $filterFields = ['transaction_id', 'status', 'type', 'payment_account_id', 'amount_min', 'amount_max', 'date_range'];
                        foreach ($filterFields as $field) {
                            if (request()->filled($field)) {
                                $activeFilterCount++;
                            }
                        }
                    @endphp

                    @if($activeFilterCount > 0)
                        <span class="badge badge-warning me-2" id="activeFiltersCount">
                        {{ $activeFilterCount }} {{ __('Active Filters') }}
                    </span>
                    @endif

                    <button class="btn btn-light btn-sm" type="button" data-toggle="modal" data-target="#filterModal">
                        <i class="fa fa-filter"></i> {{ __('Filters') }}
                    </button>
                </div>
            </div>

            {{-- Active Filters Display --}}
            @if($activeFilterCount > 0)
                <div class="card-body py-2 border-bottom bg-light">
                    <div class="active-filters">
                        <small class="text-muted">{{ __('Active Filters') }}:</small>

                        @php
                            // Helper function to remove query parameters
                            function removeQueryParams($paramsToRemove) {
                                $currentQuery = request()->query();
                                if (is_array($paramsToRemove)) {
                                    foreach ($paramsToRemove as $param) {
                                        unset($currentQuery[$param]);
                                    }
                                } else {
                                    unset($currentQuery[$paramsToRemove]);
                                }
                                return url()->current() . (!empty($currentQuery) ? '?' . http_build_query($currentQuery) : '');
                            }
                        @endphp

                        @if(request('transaction_id'))
                            <span class="badge badge-primary me-1 mb-1 text-white">
                                {{ __('ID') }}: {{ request('transaction_id') }}
                                <a href="{{ removeQueryParams('transaction_id') }}" class="text-white ms-1" style="text-decoration: none;">×</a>
                            </span>
                        @endif

                        @if(request('status'))
                            <span class="badge badge-info me-1 mb-1 text-white">
                                {{ __('Status') }}: {{ ucfirst(request('status')) }}
                                <a href="{{ removeQueryParams('status') }}" class="text-white ms-1" style="text-decoration: none;">×</a>
                            </span>
                        @endif

                        @if(request('type'))
                            <span class="badge badge-info me-1 mb-1 text-white">
                                {{ __('Type') }}: {{ ucfirst(request('type')) }}
                                <a href="{{ removeQueryParams('type') }}" class="text-white ms-1" style="text-decoration: none;">×</a>
                            </span>
                        @endif

                        @if(request('payment_account_id'))
                             @php
                                $accountName = $accounts->where('id', request('payment_account_id'))->first()->name ?? 'N/A';
                            @endphp
                            <span class="badge badge-secondary me-1 mb-1 text-white">
                                {{ __('Account') }}: {{ $accountName }}
                                <a href="{{ removeQueryParams('payment_account_id') }}" class="text-white ms-1" style="text-decoration: none;">×</a>
                            </span>
                        @endif

                        @if(request('amount_min') || request('amount_max'))
                            <span class="badge badge-success me-1 mb-1 text-white">
                                {{ __('Amount') }}: {{ request('amount_min', 0) }} - {{ request('amount_max', '∞') }}
                                <a href="{{ removeQueryParams(['amount_min', 'amount_max']) }}" class="text-white ms-1" style="text-decoration: none;">×</a>
                            </span>
                        @endif

                        @if(request('date_range'))
                            <span class="badge badge-secondary me-1 mb-1 text-white">
                                {{ __('Date') }}: {{ request('date_range') }}
                                <a href="{{ removeQueryParams('date_range') }}" class="text-white ms-1" style="text-decoration: none;">×</a>
                            </span>
                        @endif

                        <a href="{{ url()->current() }}" class="btn btn-outline-danger btn-sm ms-2">
                            <i class="fa fa-times"></i> {{ __('Clear All') }}
                        </a>
                    </div>
                </div>
            @endif

            <div class="card-body" style="overflow-x: auto;">

            <table id="transactionsTable" class="table table-hover align-middle text-center w-100">
                    <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>{{ __('Transaction ID') }}</th>
{{--                        <th>{{ __('From Account') }}</th>--}}
{{--                        <th>{{ __('To Wallet') }}</th>--}}
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
{{--                            <td>{{ $transaction->fromPaymentAccount->getTranslation('name', $local) ?? 'N/A' }}</td>--}}
{{--                            <td>{{ $transaction->toWallet->company->getTranslation('name', app()->getLocale()) ?? 'N/A' }}</td>--}}
                            <td>
                                <span class="text-white badge badge-info">
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
                                <span class="text-white badge badge-{{ $statusColors[$transaction->status] ?? 'light' }}">
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
                 <div class="mt-3">
                    {{ $transactions->withQueryString()->links() }}
                </div>
            </div>
        </div>
    </div>

    {{-- Filter Modal --}}
    <div class="modal fade" id="filterModal" tabindex="-1" role="dialog" aria-labelledby="filterModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="filterModalLabel">
                        <i class="fa fa-filter"></i> {{ __('Filter Transactions') }}
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="filterForm" method="GET" action="{{ url()->current() }}">
                        <div class="row">
                            {{-- Transaction ID --}}
                            <div class="col-md-6 mb-3">
                                <label for="transaction_id" class="form-label">{{ __('Transaction ID') }}</label>
                                <input type="text" id="transaction_id" name="transaction_id" class="form-control"
                                       placeholder="{{ __('Enter ID') }}" value="{{ request('transaction_id') }}">
                            </div>

                            {{-- Payment Account --}}
                            <div class="col-md-6 mb-3">
                                <label for="payment_account_id" class="form-label">{{ __('Payment Account') }}</label>
                                <select id="payment_account_id" name="payment_account_id" class="form-control select2-filter">
                                    <option value="">{{ __('All Accounts') }}</option>
                                    @foreach($accounts as $account)
                                        <option value="{{ $account->id }}" {{ request('payment_account_id') == $account->id ? 'selected' : '' }}>
                                            {{ $account->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Status --}}
                            <div class="col-md-6 mb-3">
                                <label for="status" class="form-label">{{ __('Status') }}</label>
                                <select id="status" name="status" class="form-control">
                                    <option value="">{{ __('All Statuses') }}</option>
                                    @foreach($statuses as $status)
                                        <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>
                                            {{ ucfirst($status) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Type --}}
                            <div class="col-md-6 mb-3">
                                <label for="type" class="form-label">{{ __('Type') }}</label>
                                <select id="type" name="type" class="form-control">
                                    <option value="">{{ __('All Types') }}</option>
                                    @foreach($types as $type)
                                        <option value="{{ $type }}" {{ request('type') == $type ? 'selected' : '' }}>
                                            {{ ucfirst($type) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                             {{-- Date Range Filter --}}
                            <div class="col-md-6 mb-3">
                                <label for="date_range" class="form-label">{{ __('Date Range') }}</label>
                                <input type="text" id="date_range" name="date_range" class="form-control date-range-picker"
                                       placeholder="{{ __('Select date range') }}"
                                       value="{{ request('date_range') }}">
                            </div>

                            {{-- Amount Range --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label">{{ __('Amount Range') }}</label>
                                <div class="row">
                                    <div class="col">
                                        <input type="number" id="amount_min" name="amount_min" class="form-control"
                                               placeholder="{{ __('Min') }}" value="{{ request('amount_min') }}"
                                               min="0" step="0.01">
                                    </div>
                                    <div class="col">
                                        <input type="number" id="amount_max" name="amount_max" class="form-control"
                                               placeholder="{{ __('Max') }}" value="{{ request('amount_max') }}"
                                               min="0" step="0.01">
                                    </div>
                                </div>
                            </div>

                            {{-- Quick Filters --}}
                            <div class="col-12 mb-3">
                                <label class="form-label">{{ __('Quick Date Filters') }}</label>
                                <div class="d-flex flex-wrap">
                                    <button type="button" class="btn btn-outline-primary btn-sm mr-2 mb-2 quick-filter" data-days="0">
                                        {{ __('Today') }}
                                    </button>
                                    <button type="button" class="btn btn-outline-primary btn-sm mr-2 mb-2 quick-filter" data-days="7">
                                        {{ __('Last 7 Days') }}
                                    </button>
                                    <button type="button" class="btn btn-outline-primary btn-sm mr-2 mb-2 quick-filter" data-days="30">
                                        {{ __('Last 30 Days') }}
                                    </button>
                                    <button type="button" class="btn btn-outline-primary btn-sm mr-2 mb-2 quick-filter" data-days="90">
                                        {{ __('Last 3 Months') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fa fa-times"></i> {{ __('Cancel') }}
                    </button>
                    <a href="{{ url()->current() }}" class="btn btn-warning">
                        <i class="fa fa-refresh"></i> {{ __('Reset All') }}
                    </a>
                    <button type="submit" form="filterForm" class="btn btn-primary">
                        <i class="fa fa-search"></i> {{ __('Apply Filters') }}
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- JS - Only load what's not already in the layout --}}
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap4.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://npmcdn.com/flatpickr/dist/l10n/ar.js"></script>

    <script>
        $(document).ready(function () {
             // Initialize Select2 for modal dropdowns
            $('#payment_account_id').select2({
                placeholder: "{{ __('Select Account') }}",
                allowClear: true,
                dropdownParent: $('#filterModal')
            });

            // Initialize Date Range Picker
            flatpickr("#date_range", {
                mode: "range",
                dateFormat: "Y-m-d",
                locale: "{{ app()->getLocale() }}",
                allowInput: true
            });

             // Quick filter buttons
            $('.quick-filter').on('click', function() {
                var days = $(this).data('days');
                var endDate = new Date();
                var startDate = new Date();
                startDate.setDate(startDate.getDate() - days);

                var dateRange = startDate.toISOString().split('T')[0] + ' to ' + endDate.toISOString().split('T')[0];
                $('#date_range').val(dateRange);
            });

            // Initialize DataTable with search enabled
            $('#transactionsTable').DataTable({
                paging: false, // Disable DataTables pagination since we use Laravel's
                info: false,   // Disable info display
                searching: true, // Enable client-side search
                scrollX: true,
                dom: 'Bfrtip',
                buttons: [
                    {
                        extend: 'print',
                        text: '<i class="fa fa-print"></i> {{ __("Print") }}',
                        className: 'btn btn-primary btn-sm',
                        title: '{{ __("Transactions Report") }}'
                    },
                    {
                        extend: 'excelHtml5',
                        text: '<i class="fa fa-file-excel"></i> {{ __("Export Excel") }}',
                        className: 'btn btn-success btn-sm',
                        title: 'Transactions_Report_{{ now()->format("Y_m_d") }}'
                    }
                ]
            });
        });
    </script>
    <style>
        .active-filters {
            padding: 10px;
            background: #f8f9fa;
            border-radius: 0.375rem;
            border: 1px solid #e9ecef;
        }
        .active-filters .badge {
            font-size: 0.75rem;
            padding: 0.35em 0.65em;
        }
        .select2-container {
            width: 100% !important;
        }
        .dataTables_wrapper .dataTables_filter {
            float: right;
            text-align: right;
            margin-bottom: 10px;
        }
        .dataTables_wrapper .dataTables_filter input {
            margin-left: 0.5em;
        }
    </style>
</x-dashboard.main-layout>