@php
    $locale = app()->getLocale();
@endphp

<x-dashboard.main-layout>
    <div class="container mt-4">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">{{ __('Generate Monthly Salaries') }}</h4>
            </div>
            <div class="card-body">
                <p class="text-muted">{{ __('Generate salary records for all active employees for a specific month.') }}</p>

                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('admins.hr.salaries.generate') }}" method="POST">
                    @csrf

                    <!-- Month Input -->
                    <div class="form-group">
                        <label for="month">
                            {{ __('Select Month') }} <span class="text-danger">*</span>
                        </label>
                        <input type="month"
                               name="month"
                               id="month"
                               value="{{ old('month', date('Y-m')) }}"
                               class="form-control"
                               required>
                        <small class="form-text text-muted">
                            {{ __('Salaries will be generated for all active employees for this month.') }}
                        </small>
                    </div>

                    <!-- Preview Section -->
                    <div class="card bg-light mb-3">
                        <div class="card-body">
                            <h5 class="card-title">{{ __('Preview') }}</h5>
                            <p class="mb-1"><strong>{{ __('Active Employees:') }}</strong> {{ $users->count() }}</p>
                            <p class="mb-1">
                                <strong>{{ __('Selected Month:') }}</strong>
                                <span id="monthPreview">{{ date('F Y') }}</span>
                            </p>
                            <small class="text-muted">
                                {{ __('Note: Existing salaries for the selected month will be skipped.') }}
                            </small>
                        </div>
                    </div>

                    <!-- Buttons -->
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admins.hr.salaries.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left mr-1"></i> {{ __('Back to Salaries') }}
                        </a>

                        <button type="submit"
                                onclick="return confirmAction('Generate salaries for ' + document.getElementById('monthPreview').textContent + '?')"
                                class="btn btn-primary">
                            <i class="fas fa-cogs mr-1"></i> {{ __('Generate Salaries') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Update month preview dynamically
        document.getElementById('month').addEventListener('change', function () {
            const month = this.value;
            if (month) {
                const date = new Date(month + '-01');
                const monthName = date.toLocaleString('default', { month: 'long', year: 'numeric' });
                document.getElementById('monthPreview').textContent = monthName;
            }
        });

        function confirmAction(message) {
            return confirm(message);
        }
    </script>
</x-dashboard.main-layout>
