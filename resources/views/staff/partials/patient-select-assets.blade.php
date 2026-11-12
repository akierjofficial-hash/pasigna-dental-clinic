@once
    @push('styles')
        <link rel="stylesheet" href="{{ asset('vendor/tom-select/tom-select.bootstrap5.min.css') }}">
        <link rel="stylesheet" href="{{ asset('css/patient-select.css') }}">
    @endpush
    @push('scripts')
        <script src="{{ asset('vendor/tom-select/tom-select.complete.min.js') }}" defer></script>
        <script src="{{ asset('js/patient-select.js') }}" defer></script>
    @endpush
@endonce
