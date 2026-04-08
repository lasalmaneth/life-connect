<?php
/**
 * Donor Portal — Make a Financial Donation
 */
include __DIR__ . '/../inc/header.view.php';
include __DIR__ . '/../inc/sidebar.view.php';

$totalPrevious = (float)($total_previous_donated ?? 0);
$donorName = htmlspecialchars(($donor_data['first_name'] ?? '') . ' ' . ($donor_data['last_name'] ?? ''));
?>

<main class="d-content">
    <div class="d-content__header">
        <h2><i class="fas fa-hand-holding-dollar text-accent"></i> Make a Donation</h2>
        <p>Your generosity saves lives and brings hope to those in need.</p>
    </div>

    <div class="d-content__body">
        <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 2rem; align-items: start;">

            <!-- LEFT: Donation Form -->
            <div class="d-widget">
                <div class="d-widget__header">
                    <div class="d-widget__title"><i class="fas fa-coins"></i> Donation Details</div>
                </div>
                <div class="d-widget__body">
                    <form id="donationForm" novalidate>

                        <div style="margin-bottom: 1.5rem;">
                            <label style="display: block; font-size: 0.8rem; font-weight: 700; color: var(--g500); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.75rem;">
                                <i class="fas fa-bolt" style="color: var(--blue-600);"></i> Quick Amounts
                            </label>
                            <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 0.5rem;">
                                <button type="button" class="d-btn d-btn--outline amount-btn" data-amount="1000">Rs. 1,000</button>
                                <button type="button" class="d-btn d-btn--outline amount-btn" data-amount="2500">Rs. 2,500</button>
                                <button type="button" class="d-btn d-btn--outline amount-btn" data-amount="5000">Rs. 5,000</button>
                                <button type="button" class="d-btn d-btn--outline amount-btn" data-amount="10000" style="grid-column: span 3;">Rs. 10,000</button>
                            </div>
                        </div>

                        <div style="margin-bottom: 1.5rem;">
                            <label for="customAmount" style="display: block; font-size: 0.8rem; font-weight: 700; color: var(--g600); margin-bottom: 0.4rem;">
                                Custom Amount (Rs.) <span style="color: var(--danger);">*</span>
                            </label>
                            <input type="number" id="customAmount" name="amount" min="100" placeholder="Enter amount (minimum Rs. 100)"
                                   style="width: 100%; padding: 0.7rem 1rem; border: 1.5px solid var(--g300); border-radius: var(--r); font-size: 1rem; transition: border 0.2s; box-sizing: border-box;">
                            <div id="amountError" style="color: var(--danger); font-size: 0.78rem; margin-top: 0.3rem; display: none;">
                                <i class="fas fa-exclamation-circle"></i> Please enter at least Rs. 100.
                            </div>
                        </div>

                        <div style="margin-bottom: 1.5rem;">
                            <label style="display: block; font-size: 0.8rem; font-weight: 700; color: var(--g500); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.75rem;">
                                <i class="fas fa-credit-card" style="color: var(--blue-600);"></i> Payment Method
                            </label>
                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem; margin-bottom: 1.25rem;">
                                <label for="cardPayment" style="display: flex; align-items: center; gap: 0.75rem; padding: 1rem; border: 2px solid var(--g300); border-radius: var(--r); cursor: pointer; transition: all 0.2s;" id="cardLabel">
                                    <input type="radio" id="cardPayment" name="payment_method" value="card" checked style="display: none;">
                                    <span style="font-size: 1.6rem;">💳</span>
                                    <div>
                                        <div style="font-weight: 700; font-size: 0.9rem;">Credit/Debit Card</div>
                                        <div style="font-size: 0.75rem; color: var(--g500);">Visa, Mastercard</div>
                                    </div>
                                </label>
                                <label for="bankPayment" style="display: flex; align-items: center; gap: 0.75rem; padding: 1rem; border: 2px solid var(--g300); border-radius: var(--r); cursor: pointer; transition: all 0.2s;" id="bankLabel">
                                    <input type="radio" id="bankPayment" name="payment_method" value="bank" style="display: none;">
                                    <span style="font-size: 1.6rem;">🏦</span>
                                    <div>
                                        <div style="font-weight: 700; font-size: 0.9rem;">Bank Transfer</div>
                                        <div style="font-size: 0.75rem; color: var(--g500);">Direct deposit</div>
                                    </div>
                                </label>
                            </div>

                            <div id="cardDetailsPanel" style="background: var(--g50); padding: 1.25rem; border-radius: var(--r); border: 1px solid var(--g200);">
                                <div style="margin-bottom: 1rem;">
                                    <label style="display: block; font-size: 0.8rem; font-weight: 700; color: var(--g600); margin-bottom: 0.4rem;">Card Number <span style="color: var(--danger);">*</span></label>
                                    <input type="text" id="cardNumber" placeholder="1234 5678 9012 3456" maxlength="19"
                                           style="width: 100%; padding: 0.65rem 1rem; border: 1.5px solid var(--g300); border-radius: var(--r); font-size: 0.9rem; box-sizing: border-box;">
                                    <div id="cardNumberError" style="color: var(--danger); font-size: 0.78rem; margin-top: 0.25rem; display: none;">Invalid card number.</div>
                                </div>
                                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                                    <div>
                                        <label style="display: block; font-size: 0.8rem; font-weight: 700; color: var(--g600); margin-bottom: 0.4rem;">Expiry <span style="color: var(--danger);">*</span></label>
                                        <input type="text" id="expiryDate" placeholder="MM/YY" maxlength="5"
                                               style="width: 100%; padding: 0.65rem 1rem; border: 1.5px solid var(--g300); border-radius: var(--r); font-size: 0.9rem; box-sizing: border-box;">
                                        <div id="expiryError" style="color: var(--danger); font-size: 0.78rem; margin-top: 0.25rem; display: none;">Use MM/YY format.</div>
                                    </div>
                                    <div>
                                        <label style="display: block; font-size: 0.8rem; font-weight: 700; color: var(--g600); margin-bottom: 0.4rem;">CVV <span style="color: var(--danger);">*</span></label>
                                        <input type="text" id="cvv" placeholder="123" maxlength="3"
                                               style="width: 100%; padding: 0.65rem 1rem; border: 1.5px solid var(--g300); border-radius: var(--r); font-size: 0.9rem; box-sizing: border-box;">
                                        <div id="cvvError" style="color: var(--danger); font-size: 0.78rem; margin-top: 0.25rem; display: none;">3-digit CVV required.</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div style="margin-bottom: 1.5rem;">
                            <label for="donationMessage" style="display: block; font-size: 0.8rem; font-weight: 700; color: var(--g600); margin-bottom: 0.4rem;">
                                <i class="fas fa-comment-dots" style="color: var(--blue-600);"></i> Message (Optional)
                            </label>
                            <textarea id="donationMessage" name="message" placeholder="Leave a message of hope..."
                                      style="width: 100%; padding: 0.7rem 1rem; border: 1.5px solid var(--g300); border-radius: var(--r); font-size: 0.9rem; resize: vertical; min-height: 80px; box-sizing: border-box;"></textarea>
                        </div>

                        <button type="submit" class="d-btn d-btn--primary" id="submitBtn" style="width: 100%; padding: 0.9rem; font-size: 1rem;">
                            <i class="fas fa-heart"></i> Complete Donation
                        </button>
                    </form>
                </div>
            </div>

            <!-- RIGHT: Summary Sidebar -->
            <div>
                <div class="d-widget" style="position: sticky; top: 80px;">
                    <div class="d-widget__header">
                        <div class="d-widget__title"><i class="fas fa-receipt"></i> Summary</div>
                    </div>
                    <div class="d-widget__body">
                        <div style="margin-bottom: 0.75rem; padding-bottom: 0.75rem; border-bottom: 1px solid var(--g200);">
                            <div style="display: flex; justify-content: space-between; font-size: 0.85rem; color: var(--g500);">
                                <span>Previous Total</span>
                                <span style="font-weight: 600; color: var(--slate);">LKR <?= number_format($totalPrevious, 2) ?></span>
                            </div>
                        </div>
                        <div style="display: flex; justify-content: space-between; font-size: 0.9rem; margin-bottom: 1rem; padding: 0.75rem; background: var(--blue-50); border-radius: var(--r); border: 1px solid var(--blue-100);">
                            <span style="color: var(--blue-700); font-weight: 600;">This Donation</span>
                            <span id="summaryNewDonation" style="font-weight: 700; color: var(--blue-700);">LKR 0.00</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; font-size: 1.1rem; font-weight: 800; color: var(--navy);">
                            <span>Running Total</span>
                            <span id="summaryTotal">LKR <?= number_format($totalPrevious, 2) ?></span>
                        </div>

                        <div style="margin-top: 1.5rem; padding: 1rem; background: #ecfdf5; border-radius: var(--r); border: 1px solid #a7f3d0; text-align: center;">
                            <i class="fas fa-heart" style="color: #10b981; font-size: 1.2rem;"></i>
                            <div style="font-weight: 700; color: #065f46; margin-top: 0.3rem;">Thank you!</div>
                            <div style="font-size: 0.8rem; color: #047857;">Your kindness saves lives.</div>
                        </div>

                        <?php if (!empty($donation_history)): ?>
                        <div style="margin-top: 1.5rem;">
                            <div style="font-size: 0.72rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.06em; color: var(--g500); margin-bottom: 0.75rem;">Recent Donations</div>
                            <?php foreach (array_slice((array)$donation_history, 0, 3) as $d): ?>
                            <div style="display: flex; justify-content: space-between; font-size: 0.8rem; padding: 0.4rem 0; border-bottom: 1px solid var(--g100);">
                                <span style="color: var(--g500);"><?= date('M d', strtotime($d->created_at)) ?></span>
                                <span style="font-weight: 600; color: var(--blue-700);">LKR <?= number_format($d->amount, 2) ?></span>
                            </div>
                            <?php endforeach; ?>
                            <a href="<?= ROOT ?>/donor/financial-history" style="font-size: 0.78rem; color: var(--blue-600); text-decoration: none; display: block; margin-top: 0.75rem; text-align: center;">
                                View all history →
                            </a>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

        </div>
    </div>
</main>

<!-- Success Modal -->
<div id="successModal" class="d-modal">
    <div class="d-modal__body" style="max-width: 420px; text-align: center;">
        <div style="font-size: 4rem; color: #10b981; margin-bottom: 1rem;"><i class="fas fa-check-circle"></i></div>
        <h3 class="d-modal__title" style="margin-bottom: 0.5rem; color: #065f46;">Donation Successful!</h3>
        <p style="color: var(--g500); margin-bottom: 2rem;">Thank you for your generous contribution.<br>Your support saves lives.</p>
        <div style="display: flex; gap: 0.75rem; justify-content: center;">
            <a href="<?= ROOT ?>/donor/financial-history" class="d-btn d-btn--primary">View History</a>
            <button onclick="document.getElementById('successModal').classList.remove('active'); window.location.reload();" class="d-btn d-btn--outline">Donate Again</button>
        </div>
    </div>
</div>

<style>
.amount-btn.selected {
    background: var(--blue-600) !important;
    color: #fff !important;
    border-color: var(--blue-600) !important;
}
#cardLabel.active, #bankLabel.active {
    border-color: var(--blue-600);
    background: var(--blue-50);
}
</style>

<script>
const PREVIOUS_TOTAL = <?= (float)$totalPrevious ?>;

// Amount buttons
document.querySelectorAll('.amount-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        document.getElementById('customAmount').value = this.dataset.amount;
        document.querySelectorAll('.amount-btn').forEach(b => b.classList.remove('selected'));
        this.classList.add('selected');
        document.getElementById('amountError').style.display = 'none';
        updateSummary();
    });
});

document.getElementById('customAmount').addEventListener('input', () => {
    document.querySelectorAll('.amount-btn').forEach(b => b.classList.remove('selected'));
    updateSummary();
});

function updateSummary() {
    const v = parseFloat(document.getElementById('customAmount').value) || 0;
    document.getElementById('summaryNewDonation').textContent = 'LKR ' + v.toLocaleString(undefined, {minimumFractionDigits: 2});
    document.getElementById('summaryTotal').textContent = 'LKR ' + (PREVIOUS_TOTAL + v).toLocaleString(undefined, {minimumFractionDigits: 2});
}

// Card input masking
document.getElementById('cardNumber').addEventListener('input', function(e) {
    let v = e.target.value.replace(/\D/g, '').substring(0, 16);
    e.target.value = v !== '' ? v.match(/.{1,4}/g).join(' ') : '';
});
document.getElementById('expiryDate').addEventListener('input', function(e) {
    let v = e.target.value.replace(/\D/g, '').substring(0, 4);
    if (v.length >= 3) v = v.substring(0, 2) + '/' + v.substring(2);
    e.target.value = v;
});

// Payment method toggle
function updatePaymentLabel() {
    const isCard = document.getElementById('cardPayment').checked;
    document.getElementById('cardLabel').style.borderColor = isCard ? 'var(--blue-600)' : 'var(--g300)';
    document.getElementById('cardLabel').style.background  = isCard ? 'var(--blue-50)' : '';
    document.getElementById('bankLabel').style.borderColor = !isCard ? 'var(--blue-600)' : 'var(--g300)';
    document.getElementById('bankLabel').style.background  = !isCard ? 'var(--blue-50)' : '';
    document.getElementById('cardDetailsPanel').style.display = isCard ? 'block' : 'none';
}
document.getElementById('cardPayment').addEventListener('change', updatePaymentLabel);
document.getElementById('bankPayment').addEventListener('change', updatePaymentLabel);
updatePaymentLabel();

// Submit
document.getElementById('donationForm').addEventListener('submit', function(e) {
    e.preventDefault();
    let valid = true;

    // Reset
    document.querySelectorAll('[id$="Error"]').forEach(el => el.style.display = 'none');

    const amount = parseFloat(document.getElementById('customAmount').value);
    if (isNaN(amount) || amount < 100) {
        document.getElementById('amountError').style.display = 'block';
        valid = false;
    }

    if (document.getElementById('cardPayment').checked) {
        const cn = document.getElementById('cardNumber').value.replace(/\s/g, '');
        const ed = document.getElementById('expiryDate').value;
        const cv = document.getElementById('cvv').value;
        if (cn.length < 13) { document.getElementById('cardNumberError').style.display = 'block'; valid = false; }
        if (!/^\d{2}\/\d{2}$/.test(ed)) { document.getElementById('expiryError').style.display = 'block'; valid = false; }
        if (cv.length < 3) { document.getElementById('cvvError').style.display = 'block'; valid = false; }
    }

    if (!valid) return;

    const btn = document.getElementById('submitBtn');
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
    btn.disabled = true;

    const fd = new FormData(this);
    fetch('<?= ROOT ?>/donor/process-financial-donation', { method: 'POST', body: fd })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                document.getElementById('successModal').classList.add('active');
                btn.innerHTML = '<i class="fas fa-heart"></i> Complete Donation';
                btn.disabled = false;
                document.getElementById('donationForm').reset();
                updateSummary();
            } else {
                alert('Error: ' + data.message);
                btn.innerHTML = '<i class="fas fa-heart"></i> Complete Donation';
                btn.disabled = false;
            }
        })
        .catch(() => {
            alert('Connection error. Please try again.');
            btn.innerHTML = '<i class="fas fa-heart"></i> Complete Donation';
            btn.disabled = false;
        });
});
</script>

<?php include __DIR__ . '/../inc/footer.view.php'; ?>
