document.addEventListener('DOMContentLoaded', function () {
    var toggle = document.getElementById('navToggle');
    var nav = document.getElementById('mainNav');
    if (toggle && nav) {
        toggle.addEventListener('click', function () {
            nav.classList.toggle('open');
        });
    }

    // Checkout: show the GCash QR/reference panel only when GCash is selected.
    var paymentRadios = document.querySelectorAll('input[name="payment_method"]');
    var gcashPanel = document.getElementById('gcashPanel');
    var referenceInput = document.getElementById('reference_code');
    if (paymentRadios.length && gcashPanel && referenceInput) {
        var syncGcashPanel = function () {
            var selected = document.querySelector('input[name="payment_method"]:checked');
            var isGcash = !!selected && selected.value === 'gcash';
            gcashPanel.hidden = !isGcash;
            referenceInput.required = isGcash;
        };
        paymentRadios.forEach(function (radio) {
            radio.addEventListener('change', syncGcashPanel);
        });
        syncGcashPanel();
    }
});
