<x-dashboard.main-layout>
    <div class="card-body" data-aos="fade-up">
        <form class="my-3">
            <div class="row">
                <div class="m-3 mb-4">
                    <label for="employee_id" class="block mb-2 font-semibold">{{ __('Employee') }}</label>
                    <select disabled id="employee_id" name="employee_id" class="w-full p-2 border rounded">
                        <option value="">{{ $leave->employee->name }}</option>
                    </select>
                </div>

                <div class="m-3 mb-4">
                    <label for="type" class="block mb-2 font-semibold">{{ __('Leave Type') }}</label>
                    <select disabled id="type" name="type" class="w-full p-2 border rounded">
                        <option>{{ __($leave->type) }}</option>
                    </select>
                </div>
            </div>

            <div class="form-group m-3 mb-4">
                <label for="reason" class="block mb-2 font-semibold">{{ __('reason') }}</label>
                <input disabled type="text" id="reason" name="reason" class="form-control w-full p-2 border rounded"
                       value="{{ old('reason', $leave->reason) }}">
            </div>

            <div class="m-3 mb-4">
                <label for="start_date" class="block mb-2 font-semibold">{{ __('Start Date') }}</label>
                <input disabled type="date" id="start_date" name="start_date" class="form-control w-full p-2 border rounded"
                       value="{{ old('start_date', $leave->start_date->format('Y-m-d')) }}">
            </div>

            <div class="m-3 mb-4">
                <label for="end_date" class="block mb-2 font-semibold">{{ __('End Date') }}</label>
                <input disabled type="date" id="end_date" name="end_date" class="form-control w-full p-2 border rounded"
                       value="{{ old('end_date', $leave->end_date->format('Y-m-d')) }}">
            </div>

            <div class="form-group m-3 mb-4">
                <label for="status" class="block mb-2 font-semibold">{{ __('Status') }}</label>
                <select disabled id="status" name="status" class="w-full p-2 border rounded">
                    <option value="pending" {{ $leave->status == 'pending' ? 'selected' : '' }}>{{ __('Pending') }}</option>
                    <option value="approved" {{ $leave->status == 'approved' ? 'selected' : '' }}>{{ __('Approved') }}</option>
                    <option value="rejected" {{ $leave->status == 'rejected' ? 'selected' : '' }}>{{ __('Rejected') }}</option>
                </select>
            </div>

            <div class="form-group m-3 mb-4">
                <label for="admin_notes" class="block mb-2 font-semibold">{{ __('Admin Notes') }}</label>
                <textarea disabled id="admin_notes" rows="3" name="admin_notes" class="form-control w-full p-2 border rounded">{{ old('admin_notes', $leave->admin_notes) }}</textarea>
            </div>
        </form>

        <!-- Display leave information -->
        <div class="mt-6 p-4 bg-gray-50 rounded">
            <h3 class="font-semibold mb-3">{{ __('Leave Information') }}</h3>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <strong>{{ __('Employee:') }}</strong> {{ $leave->employee->name ?? 'N/A' }}
                </div>
                <div>
                    <strong>{{ __('Type:') }}</strong> {{ __(ucfirst($leave->type)) }}
                </div>
                <div>
                    <strong>{{ __('Duration:') }}</strong> {{ $leave->days_taken }} {{ __('days') }}
                </div>
                <div>
                    <strong>{{ __('Current Status:') }}</strong>
                    <span class="px-2 py-1 rounded text-white
                        {{ $leave->status === 'pending' ?  'bg-warning black' : '' }}
                        {{ $leave->status === 'approved' ? 'bg-success' : '' }}
                        {{ $leave->status === 'rejected' ? 'bg-danger' : '' }}">
                        {{ __($leave->status) }}
                    </span>
                </div>
                @if($leave->approved_by)
                    <div>
                        <strong>{{ __('Approved By:') }}</strong> {{ $leave->approver->name ?? 'N/A' }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-dashboard.main-layout>
