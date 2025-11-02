<x-dashboard.main-layout>
    @php
        $rev_locale = app()->getLocale() == 'ar' ? 'en' : 'ar';
    @endphp
    <h1 class="mb-3 text-gray-800 h3">{{ __('Employees Stages') }}</h1>
    <div class="mb-4 shadow card">
        <div class="card-body">
            @livewire('show-employee-stages', ['types' => $types])
        </div>
        <div class="py-2 d-flex justify-content-center">
        </div>
    </div>

</x-dashboard.main-layout>
