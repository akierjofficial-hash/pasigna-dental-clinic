document.addEventListener('DOMContentLoaded', () => {
    const config = JSON.parse(document.getElementById('paymentSelectorConfig').textContent);
    const patient = document.getElementById('payPatient');
    const target = document.getElementById('payTarget');
    const type = document.getElementById('targetType');
    const id = document.getElementById('targetId');
    const balance = document.getElementById('payBalance');
    const amount = document.getElementById('payAmount');
    const submit = document.getElementById('paySubmit');
    let wanted = config.wanted;
    let requestNumber = 0;
    const money = value => '₱' + Number(value).toLocaleString(undefined, {minimumFractionDigits: 2});
    const showDrawer = () => bootstrap.Offcanvas.getOrCreateInstance('#recordPayment').show();

    async function load(open = false) {
        const currentRequest = ++requestNumber;
        type.value = id.value = '';
        balance.textContent = money(0);
        amount.removeAttribute('max');
        target.setCustomValidity('');
        target.disabled = submit.disabled = true;
        target.replaceChildren(new Option(patient.value ? 'Loading…' : 'Select a patient first', ''));
        if (open) showDrawer();
        if (!patient.value) return;

        try {
            // Use the page origin, including its HTTPS scheme and port behind Render's proxy.
            const url = new URL(config.url, window.location.origin);
            url.searchParams.set('patient_id', patient.value);
            const response = await fetch(url, {headers: {Accept: 'application/json'}, credentials: 'same-origin'});
            if (currentRequest !== requestNumber) return;
            if (response.status === 401 || response.redirected) throw new Error('session');
            if (!response.ok) throw new Error('request');
            const data = await response.json();
            if (currentRequest !== requestNumber) return;
            if (!Array.isArray(data.items)) throw new Error('request');
            target.replaceChildren(new Option(data.items.length ? 'Select payable item' : (data.message || 'No unpaid visits or active plans for this patient'), ''));
            data.items.forEach(item => {
                const option = new Option(`${item.label} · ${item.balance === null ? 'No final total agreed' : money(item.balance)}`, `${item.type}:${item.id}`);
                option.dataset.balance = item.balance === null ? 'unknown' : item.balance;
                target.add(option);
            });
            target.disabled = data.items.length === 0;
            if (wanted && [...target.options].some(option => option.value === wanted)) {
                target.value = wanted;
                target.dispatchEvent(new Event('change'));
                wanted = '';
            }
        } catch (error) {
            if (currentRequest !== requestNumber) return;
            target.replaceChildren(new Option(error.message === 'session'
                ? 'Session expired. Refresh this page and sign in again.'
                : 'Unable to load balances. Reselect the patient to retry.', ''));
        }
    }

    patient.addEventListener('change', () => { wanted = ''; load(); });
    target.addEventListener('change', () => {
        const [targetType, targetId] = target.value.split(':');
        const raw = target.selectedOptions[0]?.dataset.balance;
        type.value = targetType || '';
        id.value = targetId || '';
        balance.textContent = raw === 'unknown' ? 'Not determinable — no total agreed' : money(raw || 0);
        if (raw === 'unknown') amount.removeAttribute('max');
        else amount.max = Number(raw) || '';
        submit.disabled = !id.value;
        target.setCustomValidity('');
    });
    document.addEventListener('click', event => {
        const button = event.target.closest('.js-plan-pay');
        if (!button) return;
        if (patient.tomselect) patient.tomselect.setValue(button.dataset.patient, true);
        else patient.value = button.dataset.patient;
        wanted = button.dataset.target;
        load(true);
    });
    document.getElementById('paymentForm').addEventListener('submit', event => {
        if (!id.value) {
            event.preventDefault();
            target.setCustomValidity('Select a payable item.');
            target.reportValidity();
            return;
        }
        submit.disabled = true;
        submit.textContent = 'Recording…';
    });
    load(config.open);
});
