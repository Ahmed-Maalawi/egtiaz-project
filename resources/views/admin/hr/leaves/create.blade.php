<x-dashboard.main-layout>
    <div class="card-body" data-aos="fade-up">
        <form class="my-3" action="{{ route('admins.hr.leaves.store') }}" method="post">
            @csrf
            <div class="row">
                <div class="m-3 mb-4 form-group">
                    <label for="employee_id" class="block mb-2 font-semibold">{{ __('Employee') }}</label>
                    <select id="employee_id" name="employee_id" class="w-full p-2 border rounded">
                        <option value="">{{ __('Choose Employee') }}</option>
                        @foreach ($employees as $employee)
                            <option value="{{ $employee->id }}">{{ $employee->name  }}</option>
                        @endforeach
                    </select>
                    @error('employee_id')
                        <span class="text-red d-block text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="m-3 mb-4">
                    <label for="type" class="block mb-2 font-semibold">{{ __('leave type') }}</label>
                    <select id="type" name="type" class="w-full p-2 border rounded">
                        <option value="">{{ __('Choose type') }}</option>
                        @foreach ($leaveTypes as $type)
                            <option value="{{ $type }}">{{ __($type)  }}</option>
                        @endforeach
                    </select>
                    @error('type')
                        <span class="text-red d-block text-sm">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="form-group">
                <label for="reason">{{ __('reason') }}</label>
                <input type="text" id="reason" name="reason" class="form-control" value="{{ old('reason') }}">
                @error('reason')
                    <span class="text-red d-block text-sm">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group">
                <label for="start_date">{{ __('start date') }}</label>
                <input type="date" id="start_date" name="start_date" class="form-control" value="{{ old('start_date') }}">
                @error('start_date')
                    <span class="text-red d-block text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="">{{ __('end date') }}</label>
                <input type="date" name="end_date" class="form-control" value="{{ old('end_date') }}">
                @error('end_date')
                    <span class="text-red d-block text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="">{{ __('notes') }}</label>
                <textarea id="notes" rows="3" name="notes" class="form-control" value="{{ old('notes') }}"></textarea>
                @error('notes')
                    <span class="text-red d-block text-sm">{{ $message }}</span>
                @enderror
            </div>

            <button type="submit" class="btn btn-success">{{ __('Create') }}</button>
        </form>
    </div>

</x-dashboard.main-layout>
