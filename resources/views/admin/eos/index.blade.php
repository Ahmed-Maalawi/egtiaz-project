<x-dashboard.main-layout>
    <div class="container-fluid py-4">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3>{{ __('End of Service Records') }}</h3>
            <a href="{{ route('admins.eos.create') }}" class="btn btn-primary print-d-none">
                <i class="fa-solid fa-plus"></i> {{ __('Add New Record') }}
            </a>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                {{ __('End of Service List') }}
            </div>
            <div class="card-body table-responsive">
                <table id="eosTable" class="table table-hover align-middle text-center">
                    <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>{{ __('Employee Name') }}</th>
                        <th>{{ __('Joining Date') }}</th>
                        <th>{{ __('Leaving Date') }}</th>
                        <th>{{ __('Years of Service') }}</th>
                        <th>{{ __('Net Pay (AED)') }}</th>
                        <th class="print-d-none">{{ __('Actions') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($eosRecords as $eo)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $eo->user->name ?? __('N/A') }}</td>
                            <td>{{ $eo->joining_date }}</td>
                            <td>{{ $eo->leaving_date }}</td>
                            <td>
                                {{ number_format(\Carbon\Carbon::parse($eo->joining_date)->diffInYears(\Carbon\Carbon::parse($eo->leaving_date)), 2) }}
                            </td>
                            <td><strong>{{ number_format($eo->net_pay, 2) }}</strong></td>

                            <!-- ACTION BUTTONS -->
                            <td class="print-d-none">
                                <a href="{{ route('admins.eos.show', $eo) }}" class="btn btn-sm btn-info text-white">
                                    {{ __('View') }}
                                </a>

                                <a href="{{ route('admins.eos.edit', $eo) }}" class="btn btn-sm btn-warning text-white">
                                    {{ __('Edit') }}
                                </a>

                                <form action="{{ route('admins.eos.destroy', $eo) }}"
                                      method="POST" class="d-inline"
                                      onsubmit="return confirm('{{ __('Are you sure you want to delete this record?') }}');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger">
                                        {{ __('Delete') }}
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-muted">
                                {{ __('No End of Service Records Found') }}
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>

                <div class="mt-3">
                    {{ $eosRecords->links() }}
                </div>
            </div>
        </div>

{{--        <div class="mt-4 text-end print-d-none">--}}
{{--            <button onclick="window.print()" class="btn btn-outline-primary">--}}
{{--                <i class="fa-solid fa-print fa-flip-horizontal"></i> {{ __('Print All Records') }}--}}
{{--            </button>--}}
{{--        </div>--}}
    </div>
</x-dashboard.main-layout>

<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        $('#eosTable').DataTable({
            paging: true,
            searching: true,
            info: false,
            lengthChange: true,
            ordering: true,
            responsive: true,
            autoWidth: false,
            language: {
                search: "{{ __('Search:') }}",
                lengthMenu: "{{ __('Show _MENU_ records per page') }}",
                zeroRecords: "{{ __('No matching records found') }}",
                paginate: {
                    previous: "{{ __('Previous') }}",
                    next: "{{ __('Next') }}"
                }
            },
            dom: '<"top"lf>t<"bottom"p>'
        });
    });
</script>

<style>
    @media print {
        .print-d-none { display: none !important; }
        table { font-size: 13px; }
        h3 { text-align: center; }
    }
</style>
