var stripe = Stripe(
    'pk_live_51OoEq7FFweABEjfJhD7dqZqhOuvXXSWbh7gdqPziIVSYAjft7AxlRQ4tpvzPLEy9x7wFsuBFIcOMOdQXGWPJ0uMT00ICiiGeNs'
    );

var form = document.getElementById('payment-form');
form.addEventListener('submit', function(ev) {
    ev.preventDefault();

    fetch('/create-checkout-session', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                amount: form.amount.value,
                recurring: form.recurring.checked
            })
        })
        .then(function(response) {
            return response.json();
        })
        .then(function(session) {
            return stripe.redirectToCheckout({
                sessionId: session.id
            });
        })
        .then(function(result) {
            if (result.error) {
                alert(result.error.message);
            }
        })
        .catch(function(error) {
            console.error('Error:', error);
        });
});