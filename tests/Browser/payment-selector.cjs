const assert = require('node:assert/strict');
const fs = require('node:fs');
const vm = require('node:vm');

const source = fs.readFileSync('public/js/payment-selector.js', 'utf8');
const settle = () => new Promise(resolve => setImmediate(resolve));

function setup(fetch) {
    class Option {
        constructor(text, value) { this.text = text; this.value = value; this.dataset = {}; }
    }
    const nodes = {};
    for (const name of ['payPatient', 'payTarget', 'targetType', 'targetId', 'payBalance', 'payAmount', 'paySubmit', 'paymentForm']) {
        nodes[name] = {value: '', dataset: {}, options: [], listeners: {},
            addEventListener(type, fn) { this.listeners[type] = fn; },
            replaceChildren(...items) { this.options = items; this.value = items[0]?.value || ''; },
            add(option) { this.options.push(option); },
            setCustomValidity() {}, removeAttribute(key) { delete this[key]; },
            dispatchEvent(event) { this.listeners[event.type]?.(event); },
            get selectedOptions() { return this.options.filter(option => option.value === this.value); },
        };
    }
    nodes.paymentSelectorConfig = {textContent: JSON.stringify({url: '/staff/payments/payable-items', wanted: '', open: false})};
    const document = {getElementById: id => nodes[id], addEventListener: (type, fn) => {
        if (type === 'DOMContentLoaded') fn();
    }};
    vm.runInNewContext(source, {document, fetch, Option, URL, Event: class { constructor(type) { this.type = type; } },
        window: {location: {origin: 'https://clinic.example'}}, bootstrap: {Offcanvas: {getOrCreateInstance: () => ({show() {}})}}});
    const choose = value => { nodes.payPatient.value = value; nodes.payPatient.listeners.change(); };
    return {nodes, choose};
}

async function run() {
    const item = {type: 'visit', id: 12, label: 'Visit #12 · Cleaning', balance: 1000};
    let requested;
    let reply = {ok: true, json: async () => ({items: [item]})};
    const {nodes, choose} = setup(async url => { requested = url; return reply; });
    choose('8'); await settle();
    assert.equal(requested.origin, 'https://clinic.example');
    assert.equal(requested.searchParams.get('patient_id'), '8');
    assert.equal(nodes.payTarget.options[1].value, 'visit:12');
    nodes.payTarget.value = 'visit:12'; nodes.payTarget.listeners.change();
    assert.equal(nodes.targetId.value, '12');
    assert.equal(nodes.payAmount.max, 1000);
    assert.equal(nodes.paySubmit.disabled, false);
    reply = {ok: true, json: async () => ({items: []})};
    choose('9'); await settle();
    assert.match(nodes.payTarget.options[0].text, /No unpaid/);
    assert.equal(nodes.targetId.value, '');
    assert.equal(nodes.paySubmit.disabled, true);
    reply = {ok: false, status: 500};
    choose('8'); await settle();
    assert.match(nodes.payTarget.options[0].text, /Unable to load/);
    reply = {ok: true, redirected: true};
    choose('8'); await settle();
    assert.match(nodes.payTarget.options[0].text, /Session expired/);

    const pending = [];
    const race = setup(() => new Promise(resolve => pending.push(resolve)));
    race.choose('8'); race.choose('9');
    pending[1]({ok: true, json: async () => ({items: [{...item, id: 99}]})}); await settle();
    pending[0]({ok: true, json: async () => ({items: [item]})}); await settle();
    assert.equal(race.nodes.payTarget.options[1].value, 'visit:99');
    console.log('PASS: same-origin loading, selection, empty results, errors, session expiry, patient switching');
}
run().catch(error => { console.error(error); process.exitCode = 1; });
