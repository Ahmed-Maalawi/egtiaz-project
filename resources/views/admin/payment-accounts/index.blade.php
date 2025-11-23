<x-dashboard.main-layout>
    @php
        $rev_locale = app()->getLocale() == 'en' ? 'ar' : 'en';
    @endphp
    <h1 class="mb-3 text-gray-800 h3">{{ __('Payment Accounts') }}</h1>
    <div class="mb-4 shadow card">
        <div class="py-3 card-header">
            <h6 class="m-0 mt-2 font-weight-bold text-primary"></h6>
            <div class="float-right d-inline">
                <a href="{{ route('admins.paymentAccounts.create') }}" class="btn btn-primary btn-sm"><i
                        class="fa fa-plus"></i>{{ __('Add New') }}</a>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable-ar" cellspacing="0">
                    <thead>
                        <tr>
                            <th>{{ __('Serial') }}</th>
                            <th>{{ __('Payment Account') }}</th>
                            <th>{{ __('Description') }}</th>
                            <th>{{ __('Balance') }}</th>
                            <th>{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $i=0; @endphp
                        @foreach ($payment_accounts as $payment_account)
                            <tr data-id="{{ $payment_account->id }}">
                                <td>{{ ++$i }}</td>
                                <td>{{ $payment_account->name }}
                                    <br> {{ $payment_account->getTranslation('name', $rev_locale) }}
                                </td>
                                <td>{{ $payment_account->description }} <br>
                                    {{ $payment_account->getTranslation('description', $rev_locale) }}</td>
                                <td>{{ number_format($payment_account->balance, 2) }}</td>
                                <td class="d-flex justify-content-center">

                                    @can('chargePaymentAccounts')
                                        <button type="button"
                                                class="mx-1 btn btn-warning btn-sm charge-btn"
                                                data-id="{{ $payment_account->id }}">
                                            <i class="fas fa-bolt"></i>
                                        </button>
                                    @endcan


                                    <a href="{{ route('admins.paymentAccounts.show', $payment_account->id) }}"
                                        class="mx-1 btn btn-success btn-sm"><i class="fas fa-eye"></i></a>

                                    <a href="{{ route('admins.paymentAccounts.edit', $payment_account->id) }}"
                                        class="mx-1 btn btn-primary btn-sm"><i class="fas fa-edit"></i></a>
                                    <form id="delete-form-{{ $payment_account->id }}"
                                        action="{{ route('admins.paymentAccounts.destroy', $payment_account->id) }}" method="POST"
                                        style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="mx-1 btn btn-danger btn-sm"
                                            onclick="confirmDelete({{ $payment_account->id }}); event.preventDefault();">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>


                    {{--    charge wallet modal --}}
                    <div class="modal fade" id="chargeModal" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">{{ __('Charge Wallet') }}</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>

                                <form id="chargeForm" action="" method="POST">
                                    @csrf
                                    @method('POST')
                                    <div class="modal-body">
                                        <label class="form-label">{{ __('Enter Charge Amount') }}</label>
                                        <input type="number" name="amount" step="0.01" class="form-control" required>
                                    </div>

                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" id="close-btn" data-bs-dismiss="modal">{{ __('Close') }}</button>
                                        <button type="submit" class="btn btn-warning">{{ __('Charge') }}</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </table>
            </div>
        </div>
    </div>

    <script>
        function confirmDelete(cityId) {
            Swal.fire({
                title: "{{ __('Are you sure?') }}",
                text: "{{ __('You will not be able to revert this!') }}",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "{{ __('Yes, delete it!') }}"
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + cityId).submit();
                }
            });
        }

        const chargeModalEl = document.getElementById('chargeModal');
        const chargeModal = new bootstrap.Modal(chargeModalEl);

        document.querySelectorAll('.charge-btn').forEach(btn => {
            btn.addEventListener('click', function () {
                const id = this.dataset.id;
                const form = document.getElementById('chargeForm');
                form.action = `/admin/payment-accounts/${id}/charge`;
                chargeModal.show();
            });
        });

        document.getElementById('close-btn').addEventListener('click', function (e) {
            chargeModal.hide();
        })

    </script>

</x-dashboard.main-layout>
