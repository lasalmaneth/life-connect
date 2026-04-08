

// ============================================
// Amount Selection
// ============================================
const amountButtons = document.querySelectorAll('.amount-btn');
const customAmountInput = document.getElementById('customAmount');
const summaryAmount = document.getElementById('summaryAmount');
const summaryTotal = document.getElementById('summaryTotal');

amountButtons.forEach(button => {
    button.addEventListener('click', function() {
        amountButtons.forEach(btn => btn.classList.remove('selected'));
        this.classList.add('selected');
        const amount = this.getAttribute('data-amount');
        customAmountInput.value = amount;
        updateSummary(amount);
    });
});

customAmountInput.addEventListener('input', function() {
    amountButtons.forEach(btn => btn.classList.remove('selected'));
    updateSummary(this.value);
});

function updateSummary(amount) {
    if (amount && amount > 0) {
        const formatted = 'Rs. ' + parseFloat(amount).toLocaleString();
        summaryAmount.textContent = formatted;
        summaryTotal.textContent = formatted;
    } else {
        summaryAmount.textContent = 'Rs. 0';
        summaryTotal.textContent = 'Rs. 0';
    }
}

// ============================================
// Payment Method Toggle
// ============================================
const cardPaymentRadio = document.getElementById('cardPayment');
const bankPaymentRadio = document.getElementById('bankPayment');
const cardDetailsPanel = document.getElementById('cardDetailsPanel');

cardPaymentRadio.addEventListener('change', function() {
    if (this.checked) {
        cardDetailsPanel.classList.add('active');
    }
});

bankPaymentRadio.addEventListener('change', function() {
    if (this.checked) {
        cardDetailsPanel.classList.remove('active');
    }
});

// ============================================
// Card Number Formatting
// ============================================
const cardNumberInput = document.getElementById('cardNumber');
cardNumberInput.addEventListener('input', function(e) {
    let value = e.target.value.replace(/\s/g, '');
    let formattedValue = value.match(/.{1,4}/g)?.join(' ') || value;
    e.target.value = formattedValue;
});

// ============================================
// Expiry Date Formatting
// ============================================
const expiryDateInput = document.getElementById('expiryDate');
expiryDateInput.addEventListener('input', function(e) {
    let value = e.target.value.replace(/\//g, '');
    if (value.length >= 2) {
        value = value.substring(0, 2) + '/' + value.substring(2, 4);
    }
    e.target.value = value;
});
