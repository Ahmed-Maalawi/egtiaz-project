{{-- Reusable Wallet Transactions Table --}}
<div class="card shadow-sm">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0">{{ $title }}</h5>
    </div>

    <div class="card-body" style="overflow-x: auto;">
        <table id="{{ $tableId }}" class="table table-hover align-middle text-center w-100">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>{{ __('Payment ID') }}</th>
                    <th>{{ __('User') }}</th>
                    @if($showEmployee ?? false)
                        <th>{{ __('Employee') }}</th>
                        <th>{{ __('Stage') }}</th>
                    @endif
                    @if($showType ?? true)
                        <th>{{ __('Type') }}</th>
                    @endif
                    <th>{{ __('Amount') }}</th>
                    <th>{{ __('Currency') }}</th>
                    <th>{{ __('Company') }}</th>
                    <th>{{ __('Status') }}</th>
                    <th>{{ __('Completed At') }}</th>
                    <th>{{ __('Created At') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transactions as $transaction)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $transaction->payment_id }}</td>
                        <td>{{ $transaction->user->name ?? 'N/A' }}</td>
                        @if($showEmployee ?? false)
                            <td>
                                @if($transaction->employeeStage && $transaction->employeeStage->employee)
                                    <a href="{{ route('admins.employees.show', $transaction->employeeStage->employee->id) }}"
                                        class="text-decoration-none">
                                        <i class="fas fa-user"></i> {{ $transaction->employeeStage->employee->name }}
                                    </a>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td>
                                @if($transaction->employeeStage && $transaction->employeeStage->stage)
                                    <span class="badge badge-info">{{ $transaction->employeeStage->stage->name }}</span>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                        @endif
                        @if($showType ?? true)
                            <td>
                                @if($transaction->type == 'stage_payment')
                                    <span class="badge badge-danger text-white">
                                        <i class="fa fa-arrow-down"></i> {{ __('Debit') }}
                                    </span>
                                @else
                                    <span class="badge badge-success text-white">
                                        <i class="fa fa-arrow-up"></i> {{ __('Credit') }}
                                    </span>
                                @endif
                            </td>
                        @endif
                        <td>{{ number_format($transaction->amount, 2) }}</td>
                        <td>{{ $transaction->currency }}</td>
                        <td>{{ $transaction->wallet->company->getTranslation('name', app()->getLocale()) }}</td>
                        <td>
                            <span class="badge text-white
                                @if($transaction->status === 'completed') badge-success
                                @elseif($transaction->status === 'pending') badge-warning
                                @elseif($transaction->status === 'failed') badge-danger
                                @else badge-secondary @endif">
                                {{ ucfirst($transaction->status) }}
                            </span>
                        </td>
                        <td>{{ $transaction->completed_at?->format('Y-m-d H:i') ?? 'N/A' }}</td>
                        <td>{{ $transaction->created_at->format('Y-m-d H:i') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="12" class="text-center text-muted py-4">
                            {{ __('No transactions available.') }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
            @if($transactions->count() > 0)
                <tfoot class="bg-light">
                    <tr>
                        <th colspan="{{ ($showEmployee ?? false) ? 6 : 4 }}{{ ($showType ?? true) ? '' : ' - 1' }}"
                            class="text-right">
                            {{ __('Total:') }}
                        </th>
                        <th class="text-primary">{{ number_format($transactions->sum('amount'), 2) }} SAR</th>
                        <th colspan="5"></th>
                    </tr>
                </tfoot>
            @endif
        </table>
    </div>
</div>

{{-- DataTable Scripts --}}
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap4.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>

<script>
    $(function () {
        $('#{{ $tableId }}').DataTable({
            pageLength: 10,
            scrollX: true,
            dom: 'Blfrtip',
            buttons: [
                {
                    extend: 'print',
                    text: '<i class="fa fa-print"></i> {{ __("Print") }}',
                    className: 'btn btn-primary btn-sm',
                    title: '{{ $title }}'
                },
                {
                    extend: 'excelHtml5',
                    text: '<i class="fa fa-file-excel"></i> {{ __("Export Excel") }}',
                    className: 'btn btn-success btn-sm',
                    title: '{{ str_replace(" ", "_", $title) }}_{{ now()->format("Y_m_d") }}'
                }
            ]
        });
    });
</script>