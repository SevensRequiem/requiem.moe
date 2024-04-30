<div class="donate-box">
        <form id="payment-form">
            <div class="form-row">
                <label for="amount">Donation Amount</label>
                <input id="amount" name="amount" type="number" min="1" placeholder="Enter donation amount" required>
            </div>
            <div class="form-row">
                <label for="recurring">
                    <input id="recurring" name="recurring" type="checkbox"> Make this a monthly donation
                </label>
            </div>
            <button id="submit">Donate</button>
        </form>
</div>


<script src="https://js.stripe.com/v3/"></script>