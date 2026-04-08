<?php
// FILE: app/views/financial_donor/donate.view.php
$pageKey = 'donate';
$pageKey = 'donate';
require __DIR__ . '/layout.view.php';

// Calculate total donated safely from objects
$total_previous_donated = 0;
if (!empty($donation_history)) {
    foreach ($donation_history as $donation) {
        $total_previous_donated += (float)($donation->amount ?? 0);
    }
}
?>

<div class="sec active">
    <!-- Header -->
    <div class="c-hdr" style="margin-bottom: 2rem;">
        <div class="c-ey">Funding</div>
        <h2>Make a Donation</h2>
        <p>Your generosity saves lives and brings hope to those in need</p>
    </div>

    <div class="wrap" style="padding: 0; display: grid; grid-template-columns: 2fr 1fr; gap: 2rem;">
        
        <!-- LEFT SIDE: DONATION FORM -->
        <div class="card bb" style="margin: 0;">
            <form id="donationForm" novalidate>
                
                <div class="fst" style="margin-top: 0;"><i class="fas fa-coins" style="color: var(--blue-600);"></i> Amount</div>
                <div class="g3" style="margin-bottom: 1.5rem; gap: 0.5rem;">
                    <button type="button" class="btn btn-g amount-btn" data-amount="1000">Rs. 1,000</button>
                    <button type="button" class="btn btn-g amount-btn" data-amount="2500">Rs. 2,500</button>
                    <button type="button" class="btn btn-g amount-btn" data-amount="5000">Rs. 5,000</button>
                    <button type="button" class="btn btn-g amount-btn" data-amount="10000" style="grid-column: span 3;">Rs. 10,000</button>
                </div>
                
                <div class="fg" style="margin-bottom: 2rem;">
                    <label class="fl">Custom Amount (Rs.)</label>
                    <input type="number" class="fc" id="customAmount" name="amount" min="100" placeholder="Enter amount">
                    <div class="fh" id="amountError" style="color: var(--danger); display: none;">Please enter at least Rs. 100.</div>
                </div>

                <div class="fst"><i class="fas fa-credit-card" style="color: var(--blue-600);"></i> Payment</div>
                
                <div class="g2" style="margin-bottom: 1.5rem;">
                    <div class="payment-card">
                        <input type="radio" id="cardPayment" name="payment_method" value="card" checked style="display: none;">
                        <label for="cardPayment" class="card" style="cursor: pointer; padding: 1rem; margin: 0; align-items: center; gap: 0.8rem; display: flex;">
                            <span style="font-size: 1.5rem;">💳</span>
                            <div>
                                <div style="font-weight: 700; font-size: 0.9rem;">Credit/Debit Card</div>
                                <div style="font-size: 0.75rem; color: var(--g500);">Visa, Mastercard</div>
                            </div>
                        </label>
                    </div>
                    <div class="payment-card">
                        <input type="radio" id="bankPayment" name="payment_method" value="bank" style="display: none;">
                        <label for="bankPayment" class="card" style="cursor: pointer; padding: 1rem; margin: 0; align-items: center; gap: 0.8rem; display: flex;">
                            <span style="font-size: 1.5rem;">🏦</span>
                            <div>
                                <div style="font-weight: 700; font-size: 0.9rem;">Bank Transfer</div>
                                <div style="font-size: 0.75rem; color: var(--g500);">Direct deposit</div>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Card Details Panel -->
                <div id="cardDetailsPanel" class="card" style="background: var(--g50); padding: 1.5rem; margin-bottom: 2rem;">
                    <div class="fg">
                        <label class="fl">Card Number <span class="req">*</span></label>
                        <input type="text" class="fc" id="cardNumber" placeholder="1234 5678 9012 3456" maxlength="19">
                        <div class="fh" id="cardNumberError" style="color: var(--danger); display: none;">Invalid format.</div>
                    </div>
                    
                    <div class="fr2">
                        <div class="fg">
                            <label class="fl">Expiry <span class="req">*</span></label>
                            <input type="text" class="fc" id="expiryDate" placeholder="MM/YY" maxlength="5">
                            <div class="fh" id="expiryError" style="color: var(--danger); display: none;">Use MM/YY.</div>
                        </div>
                        <div class="fg">
                            <label class="fl">CVV <span class="req">*</span></label>
                            <input type="text" class="fc" id="cvv" placeholder="123" maxlength="3">
                            <div class="fh" id="cvvError" style="color: var(--danger); display: none;">3-digit CVV required.</div>
                        </div>
                    </div>
                </div>

                <div class="fst"><i class="fas fa-comment-dots" style="color: var(--blue-600);"></i> Extra</div>
                <div class="fg">
                    <label class="fl">Message (Optional)</label>
                    <textarea class="fc" name="message" id="donationMessage" placeholder="Leave a message..."></textarea>
                </div>

                <button type="submit" class="btn btn-p btn-fw btn-lg" id="submitBtn" style="margin-top: 1.5rem;">
                    Complete Donation <i class="fas fa-heart" style="margin-left: 0.4rem;"></i>
                </button>
            </form>
        </div>

        <!-- RIGHT SIDE: SUMMARY -->
        <div>
            <div class="card" style="position: sticky; top: 100px;">
                <div class="ch">
                    <div class="ct"><i class="fas fa-list-ul"></i> Summary</div>
                </div>
                
                <div class="ir">
                    <div class="il">Previous</div>
                    <div class="iv">Rs. <?= number_format($total_previous_donated, 2) ?></div>
                </div>
                <div class="ir" style="margin-top: 0.5rem; background: var(--blue-50);">
                    <div class="il" style="color: var(--blue-800);">New Donation</div>
                    <div class="iv" id="summaryNewDonation" style="color: var(--blue-800);">Rs. 0.00</div>
                </div>
                
                <div class="mdiv" style="margin: 1.5rem 0;"></div>
                
                <div style="display: flex; justify-content: space-between; font-weight: 700; color: var(--blue-800); font-size: 1.25rem;">
                    <span>Total:</span>
                    <span id="summaryTotal">Rs. <?= number_format(((float)$total_previous_donated), 2) ?></span>
                </div>
                
                <div class="notice ns" style="margin-top: 2rem; justify-content: center; text-align: center;">
                    <i class="fas fa-heart"></i>
                    <div>
                        <strong style="display: block;">Thank you!</strong>
                        Your kindness saves lives.
                    </div>
                </div>
            </div>
        </div>
        
    </div>
</div>

<style>
.amount-btn.selected { background: var(--blue-600); color: var(--white); border-color: var(--blue-600); }
input[name="payment_method"]:checked + label { border-color: var(--blue-600); background: var(--blue-50); }
.fc.error { border-color: var(--danger); box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1); }
</style>

<script>
const PREVIOUS_TOTAL = <?= (float)$total_previous_donated ?>;
const PREVIOUS_TOTAL = <?= (float)$total_previous_donated ?>;

// Input Masking
document.getElementById('cardNumber').addEventListener('input', function(e) {
    let value = e.target.value.replace(/\D/g, '').substring(0, 16);
    value = value !== '' ? value.match(/.{1,4}/g).join(' ') : '';
    e.target.value = value;
});

document.getElementById('expiryDate').addEventListener('input', function(e) {
    let value = e.target.value.replace(/\D/g, '').substring(0, 4);
    if (value.length >= 3) value = value.substring(0, 2) + '/' + value.substring(2);
    e.target.value = value;
});

// Amount selection
document.querySelectorAll('.amount-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        document.getElementById('customAmount').value = this.dataset.amount;
        document.querySelectorAll('.amount-btn').forEach(b => b.classList.remove('selected'));
        this.classList.add('selected');
        document.getElementById('customAmount').classList.remove('error');
        document.getElementById('amountError').style.display = 'none';
        document.getElementById('customAmount').value = this.dataset.amount;
        document.querySelectorAll('.amount-btn').forEach(b => b.classList.remove('selected'));
        this.classList.add('selected');
        document.getElementById('customAmount').classList.remove('error');
        document.getElementById('amountError').style.display = 'none';
        updateSummary();
    });
});

document.getElementById('customAmount').addEventListener('input', () => {
    document.querySelectorAll('.amount-btn').forEach(b => b.classList.remove('selected'));
    document.querySelectorAll('.amount-btn').forEach(b => b.classList.remove('selected'));
    updateSummary();
});

function updateSummary() {
    const newVal = parseFloat(document.getElementById('customAmount').value) || 0;
    document.getElementById('summaryNewDonation').textContent = 'Rs. ' + newVal.toLocaleString(undefined, {minimumFractionDigits: 2});
    document.getElementById('summaryTotal').textContent = 'Rs. ' + (PREVIOUS_TOTAL + newVal).toLocaleString(undefined, {minimumFractionDigits: 2});
}

// Method toggle
document.getElementById('cardPayment').addEventListener('change', () => document.getElementById('cardDetailsPanel').style.display = 'block');
document.getElementById('bankPayment').addEventListener('change', () => document.getElementById('cardDetailsPanel').style.display = 'none');

// Validation & Submit
document.getElementById('donationForm').addEventListener('submit', function(e) {
    e.preventDefault();
    let isValid = true;

    document.querySelectorAll('.fh').forEach(t => t.style.display = 'none');
    document.querySelectorAll('.fc').forEach(i => i.classList.remove('error'));

    const amount = parseFloat(document.getElementById('customAmount').value);
    if (isNaN(amount) || amount < 100) {
        document.getElementById('customAmount').classList.add('error');
        document.getElementById('amountError').style.display = 'block';
        isValid = false;
    }

    const payMethod = document.querySelector('input[name="payment_method"]:checked').value;
    if (payMethod === 'card') {
        const cn = document.getElementById('cardNumber').value.replace(/\s/g, '');
        const ed = document.getElementById('expiryDate').value;
        const cv = document.getElementById('cvv').value;

        if (cn.length < 13) { document.getElementById('cardNumber').classList.add('error'); document.getElementById('cardNumberError').style.display = 'block'; isValid = false; }
        if (!/^\d{2}\/\d{2}$/.test(ed)) { document.getElementById('expiryDate').classList.add('error'); document.getElementById('expiryError').style.display = 'block'; isValid = false; }
        if (cv.length < 3) { document.getElementById('cvv').classList.add('error'); document.getElementById('cvvError').style.display = 'block'; isValid = false; }
    }

    if (!isValid) return;

    const btn = document.getElementById('submitBtn');
    const originalText = btn.innerHTML;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
    btn.disabled = true;

    const formData = new FormData(this);
    fetch('<?= ROOT ?>/financial-donor/process-donation', { method: 'POST', body: formData })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            alert('Thank you for your generous donation!');
            alert('Thank you for your generous donation!');
            window.location.href = '<?= ROOT ?>/financial-donor/history';
        } else {
            alert('Error: ' + data.message);
            btn.innerHTML = originalText; btn.disabled = false;
        }
    })
    .catch(error => {
        alert('Connection error. Please try again.');
        btn.innerHTML = originalText; btn.disabled = false;
    });
});
</script>

    </main>
</div> <!-- .wrap -->
</body>
</html>
