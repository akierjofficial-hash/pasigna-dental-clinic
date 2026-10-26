<div class="col-12" data-doctor-working-days>
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-2">
        <label class="form-label mb-0">Working Days</label>
        <button type="button" class="btn btn-sm btn-outline-primary" data-doctor-days-preset="friday-saturday">
            Friday + Saturday only
        </button>
    </div>
    <div class="d-flex flex-wrap gap-3">
        @foreach($dayOptions as $dayVal => $dayLabel)
            <label class="form-check-label d-flex align-items-center gap-2" style="font-weight:850;">
                <input type="checkbox" class="form-check-input" name="working_days[]" value="{{ $dayVal }}"
                       @checked(in_array($dayVal, $selectedDays, true))>
                <span>{{ $dayLabel }}</span>
            </label>
        @endforeach
    </div>
    <div class="hint mt-1">These days and times control this dentist's booking slots. The clinic is closed on Sundays.</div>
</div>

@push('scripts')
<script>
document.addEventListener('click', function (event) {
    const preset = event.target.closest('[data-doctor-days-preset="friday-saturday"]');
    if (!preset) return;
    const group = preset.closest('[data-doctor-working-days]');
    group.querySelectorAll('input[name="working_days[]"]').forEach(function (checkbox) {
        checkbox.checked = checkbox.value === '5' || checkbox.value === '6';
    });
});
</script>
@endpush
