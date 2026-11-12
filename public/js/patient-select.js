document.addEventListener('DOMContentLoaded', () => {
    if (!window.TomSelect) return;
    document.querySelectorAll('select[data-patient-search]').forEach(select => {
        if (select.tomselect) return;
        const picker = new TomSelect(select, {
            create: false,
            maxItems: 1,
            maxOptions: null,
            allowEmptyOption: true,
            placeholder: 'Type a patient name…',
            searchField: ['text'],
            plugins: ['clear_button'],
            // Keep the search input inside Bootstrap's offcanvas focus trap.
            dropdownParent: null,
            onDropdownOpen() { this.control_input.focus(); },
        });
        picker.wrapper.classList.add('patient-search');
        picker.control_input.setAttribute('aria-label', 'Search patients by name');
        picker.control_input.setAttribute('autocapitalize', 'none');
        picker.control_input.setAttribute('autocorrect', 'off');
        picker.control_input.setAttribute('inputmode', 'search');
        picker.control_input.setAttribute('autocomplete', 'off');
    });
});
