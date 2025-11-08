<x-dashboard.main-layout>
    <div class="card-body" data-aos="fade-up">
        <form class="my-3" action="{{ route('admins.hr.leaves.update', $leave->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="m-3 mb-4">
                    <label for="user_id" class="block mb-2 font-semibold">{{ __('User') }}</label>
                    <select id="user_id" name="user_id" class="w-full p-2 border rounded">
                        <option value="">{{ __('Choose User') }}</option>
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}" {{ $leave->user->id == $user->id ? 'selected' : '' }}>
                                {{ $user->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('user_id')
                    <span class="text-red d-block text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="m-3 mb-4">
                    <label for="type" class="block mb-2 font-semibold">{{ __('Leave Type') }}</label>
                    <select id="type" name="type" class="w-full p-2 border rounded">
                        <option value="">{{ __('Choose Type') }}</option>
                        @foreach ($leaveTypes as $type)
                            <option value="{{ $type }}" {{ $leave->type == $type ? 'selected' : '' }}>
                                {{ __(ucfirst($type)) }}
                            </option>
                        @endforeach
                    </select>
                    @error('type')
                        <span class="text-red d-block text-sm">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="form-group m-3 mb-4">
                <label for="reason" class="block mb-2 font-semibold">{{ __('reason') }}</label>
                <input type="text" id="reason" name="reason" class="form-control w-full p-2 border rounded"
                       value="{{ old('reason', $leave->reason) }}">
                @error('reason')
                    <span class="text-red d-block text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div class="row">
                <div class="m-3 mb-4">
                    <label for="start_date" class="block mb-2 font-semibold">{{ __('Start Date') }}</label>
                    <input type="date" id="start_date" name="start_date" class="form-control w-full p-2 border rounded"
                           value="{{ old('start_date', $leave->start_date->format('Y-m-d')) }}">
                    @error('start_date')
                    <span class="text-red d-block text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="m-3 mb-4">
                    <label for="end_date" class="block mb-2 font-semibold">{{ __('End Date') }}</label>
                    <input type="date" id="end_date" name="end_date" class="form-control w-full p-2 border rounded"
                           value="{{ old('end_date', $leave->end_date->format('Y-m-d')) }}">
                    @error('end_date')
                    <span class="text-red d-block text-sm">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="form-group m-3 mb-4">
                <label for="status" class="block mb-2 font-semibold">{{ __('Status') }}</label>
                <select id="status" name="status" class="w-full p-2 border rounded">
                    <option value="pending" {{ $leave->status == 'pending' ? 'selected' : '' }}>{{ __('pending') }}</option>
                    <option value="approved" {{ $leave->status == 'approved' ? 'selected' : '' }}>{{ __('approved') }}</option>
                    <option value="rejected" {{ $leave->status == 'rejected' ? 'selected' : '' }}>{{ __('rejected') }}</option>
                </select>
                @error('status')
                <span class="text-red d-block text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group m-3 mb-4">
                <label for="notes" class="block mb-2 font-semibold">{{ __('notes') }}</label>
                <textarea id="notes" rows="3" name="notes" class="form-control w-full p-2 border rounded">{{ old('notes', $leave->notes) }}</textarea>
                @error('notes')
                <span class="text-red d-block text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div class="m-3 mb-4">
                <button type="submit" class="btn btn-success text-white px-4 py-2 rounded">
                    {{ __('Update Leave') }}
                </button>
                <a href="{{ route('admins.hr.leaves.index') }}" class="btn btn-secondary bg-secondary text-white px-4 py-2 rounded ml-2">
                    {{ __('Cancel') }}
                </a>
            </div>
        </form>

        <!-- Display leave information -->
        <div class="mt-6 p-4 bg-gray-50 rounded">
            <h3 class="font-semibold mb-3">{{ __('Leave Information') }}</h3>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <strong>{{ __('User:') }}</strong> {{ $leave->user->name ?? __('unknown') }}
                </div>
                <div>
                    <strong>{{ __('Type:') }}</strong> {{ __($leave->type) }}
                </div>
                <div>
                    <strong>{{ __('Duration:') }}</strong> {{ $leave->days_taken }} {{ __('days') }}
                </div>
                <div>
                    <strong>{{ __('Current Status:') }}</strong>
                    <span class="px-2 py-1 rounded text-white
                        {{ $leave->status == 'approved' ? 'bg-success' : '' }}
                        {{ $leave->status == 'pending' ? 'bg-warning' : '' }}
                        {{ $leave->status == 'rejected' ? 'bg-danger' : '' }}">
                        {{ __(ucfirst($leave->status)) }}
                    </span>
                </div>
                @if($leave->approved_by)
                    <div>
                        <strong>{{ __('Approved By:') }}</strong> {{ $leave->approver->name ?? __('unknown') }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-dashboard.main-layout>
