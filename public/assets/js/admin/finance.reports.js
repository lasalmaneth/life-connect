/**
 * finance.reports.js - Handles PDF generation and reporting for Financial Admin
 */

function exportPaymentsReport() {
    const startVal = document.getElementById('export-start-date').value;
    const endVal = document.getElementById('export-end-date').value;
    const donorQuery = document.getElementById('export-donor-search').value.trim();

    if (!startVal || !endVal) {
        showToast("warning", "Please select both a Start Date and an End Date to export the report.");
        return;
    }

    const start = new Date(startVal);
    const end = new Date(endVal);

    if (end < start) {
        showToast("error", "The End Date cannot be before the Start Date.");
        return;
    }

    // 12 month validation
    const limitDate = new Date(start);
    limitDate.setMonth(start.getMonth() + 12);

    if (end > limitDate) {
        showToast("warning", "The selected date range cannot exceed 12 months. Please narrow your selection.");
        return;
    }

    // Performance warning for > 3 months
    const threeMonthLimit = new Date(start);
    threeMonthLimit.setMonth(start.getMonth() + 3);

    if (end > threeMonthLimit) {
        showToast("warning", "Generating a detailed report for over 3 months may take a few seconds...");
    } else {
        showToast("success", "Generating your professional report PDF...");
    }

    // Generate PDF Report Layout
    const reportWindow = window.open('', '_blank');
    const logoUrl = `${ROOT}/public/assets/images/logo.png`;
    const currentAdmin = typeof ADMIN_NAME !== 'undefined' ? ADMIN_NAME : 'System Administrator';

    let reportTitle = "Financial Donation Report";
    let donorSubheading = '';
    const isSingleDonor = donorQuery !== '' && filteredPayments.length > 0 && filteredPayments.every(p => (p.full_name || '').toLowerCase() === donorQuery.toLowerCase());

    if (isSingleDonor) {
        const email = filteredPayments[0].email || 'N/A';
        reportTitle = `Donor Contribution Statement`;
        donorSubheading = `
            <div style="background: #f8fafc; padding: 20px; border-radius: 12px; border: 1px solid #e2e8f0; margin-bottom: 30px; display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <span style="font-size: 0.7rem; color: #94a3b8; text-transform: uppercase; font-weight: 700; letter-spacing: 0.05em; display: block; margin-bottom: 4px;">Donor Details</span>
                    <h2 style="margin: 0; font-size: 1.2rem; color: #1e293b;">${donorQuery}</h2>
                </div>
                <div style="text-align: right;">
                    <span style="font-size: 0.7rem; color: #94a3b8; text-transform: uppercase; font-weight: 700; letter-spacing: 0.05em; display: block; margin-bottom: 4px;">Contact Email</span>
                    <p style="margin: 0; font-size: 1rem; color: #005baa; font-weight: 600;">${email}</p>
                </div>
            </div>
        `;
    } else if (donorQuery) {
        reportTitle = `Financial Report: ${donorQuery}`;
    }

    let totalAmount = 0;
    let tableRows = '';

    filteredPayments.forEach(p => {
        const amt = parseFloat(p.amount);
        totalAmount += amt;
        
        if (isSingleDonor) {
            // Simplified table for single donor
            tableRows += `
                <tr>
                    <td>#${p.id}</td>
                    <td>${formatDate(p.date)}</td>
                    <td>${p.note || '—'}</td>
                    <td style="text-align: right; font-weight: 700;">${amt.toLocaleString('en-US', { minimumFractionDigits: 2 })}</td>
                </tr>
            `;
        } else {
            // General table
            tableRows += `
                <tr>
                    <td>#${p.id}</td>
                    <td>${formatDate(p.date)}</td>
                    <td>${p.full_name || '—'}</td>
                    <td>${p.email || 'N/A'}</td>
                    <td style="text-align: right; font-weight: 700;">${amt.toLocaleString('en-US', { minimumFractionDigits: 2 })}</td>
                </tr>
            `;
        }
    });

    if (filteredPayments.length === 0) {
        const colspan = isSingleDonor ? 4 : 5;
        tableRows = `<tr><td colspan="${colspan}" style="text-align:center; padding: 20px;">No transactions found for the selected period.</td></tr>`;
    }

    const reportRangeText = activeRangeContext || `${formatDate(startVal)} - ${formatDate(endVal)}`;

    reportWindow.document.write(`
        <!DOCTYPE html>
        <html>
        <head>
            <title>&#8203;</title>
            <style>
                body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; padding: 40px; color: #334155; line-height: 1.6; background: #fff; }
                .report-container { max-width: 900px; margin: 0 auto; position: relative; }
                .report-header { display: flex; align-items: center; justify-content: space-between; border-bottom: 2px solid #005baa; padding-bottom: 20px; margin-bottom: 20px; }
                .logo-area { display: flex; align-items: center; gap: 15px; }
                .logo-img { height: 50px; }
                .brand-name { font-size: 1.8rem; font-weight: 800; color: #003b6e; letter-spacing: -0.02em; }
                .report-title { text-align: right; }
                .report-title h1 { margin: 0; font-size: 1.25rem; color: #64748b; text-transform: uppercase; letter-spacing: 0.1em; }
                .report-title p { margin: 5px 0 0; font-size: 0.85rem; color: #94a3b8; font-weight: 600; }
                
                .data-table { width: 100%; border-collapse: collapse; margin-top: 30px; font-size: 0.85rem; }
                .data-table th { background: #f8fafc; text-align: left; padding: 12px; border-bottom: 2px solid #e2e8f0; color: #475569; text-transform: uppercase; font-size: 0.7rem; letter-spacing: 0.05em; }
                .data-table td { padding: 12px; border-bottom: 1px solid #f1f5f9; color: #334155; }
                
                .total-section { margin-top: 30px; display: flex; justify-content: flex-end; }
                .total-box { background: #f8fafc; padding: 20px; border-radius: 8px; border: 1px solid #e2e8f0; min-width: 250px; }
                .total-row { display: flex; justify-content: space-between; align-items: center; }
                .total-label { font-weight: 700; color: #64748b; text-transform: uppercase; font-size: 0.75rem; }
                .total-value { font-size: 1.4rem; font-weight: 800; color: #005baa; }
                
                .footer { margin-top: 60px; text-align: center; border-top: 1px solid #f1f5f9; padding-top: 25px; }
                .footer-text { font-size: 0.8rem; color: #94a3b8; margin: 0; }
                .auto-gen-msg { font-size: 0.75rem; color: #94a3b8; margin-top: 8px; display: block; font-weight: 500; }
                .admin-tag { font-size: 0.65rem; color: #cbd5e1; text-transform: uppercase; letter-spacing: 0.05em; margin-top: 15px; display: block; }
                
                @media print {
                    @page { margin: 15mm; }
                    body { padding: 0; }
                    .report-container { max-width: 100%; }
                }
            </style>
        </head>
        <body>
            <div class="report-container">
                <div class="report-header">
                    <div class="logo-area">
                        <img src="${logoUrl}" class="logo-img" onerror="this.style.display='none'">
                        <div class="brand-name">LifeConnect</div>
                    </div>
                    <div class="report-title">
                        <h1>${reportTitle}</h1>
                        <p>${reportRangeText}</p>
                    </div>
                </div>

                ${donorSubheading}
                
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Date</th>
                            ${!isSingleDonor ? '<th>Donor Name</th><th>Email</th>' : '<th>Note / Reference</th>'}
                            <th style="text-align: right;">Amount (LKR)</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${tableRows}
                    </tbody>
                </table>
                
                <div class="total-section">
                    <div class="total-box">
                        <div class="total-row">
                            <span class="total-label">Grand Total </span>
                            <span class="total-value">LKR ${totalAmount.toLocaleString('en-US', { minimumFractionDigits: 2 })}</span>
                        </div>
                    </div>
                </div>
                
                <div class="footer">
                    <p class="footer-text">This report summarizes financial contributions received by LifeConnect during the selected period.</p>
                    <span class="auto-gen-msg">This is a computer-generated report and does not require a physical signature.</span>
                    <span class="admin-tag">Generated by: ${currentAdmin}</span>
                </div>
            </div>
            <script>
                window.onload = function() {
                    window.print();
                    setTimeout(function() { window.close(); }, 500);
                };
            </script>
        </body>
        </html>
    `);
    reportWindow.document.close();
}

function printDonationReceipt(id) {
    const payment = allPayments.find(p => p.id == id);
    if (!payment) return;

    const printWindow = window.open('', '_blank');
    const logoUrl = `${ROOT}/public/assets/images/logo.png`;
    const currentAdmin = typeof ADMIN_NAME !== 'undefined' ? ADMIN_NAME : 'System Administrator';
    const amountStr = "LKR " + parseFloat(payment.amount).toLocaleString('en-US', { minimumFractionDigits: 2 });
    const dateStr = formatDate(payment.date);
    const status = (payment.status || 'SUCCESS').toUpperCase();

    printWindow.document.write(`
        <!DOCTYPE html>
        <html>
        <head>
            <title>&#8203;</title>
            <style>
                body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; padding: 40px; color: #334155; line-height: 1.6; background: #fff; }
                .receipt-container { max-width: 800px; margin: 0 auto; padding: 20px; position: relative; }
                .receipt-header { display: flex; align-items: center; justify-content: space-between; border-bottom: 2px solid #005baa; padding-bottom: 20px; margin-bottom: 30px; }
                .logo-area { display: flex; align-items: center; gap: 15px; }
                .logo-img { height: 50px; }
                .brand-name { font-size: 1.8rem; font-weight: 800; color: #003b6e; letter-spacing: -0.02em; }
                .receipt-title { font-size: 1.2rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.1em; }
                .details-table { width: 100%; border-collapse: collapse; margin-top: 10px; }
                .details-table td { padding: 14px 0; border-bottom: 1px solid #f1f5f9; vertical-align: top; }
                .label { font-weight: 700; color: #005baa; width: 220px; text-transform: uppercase; font-size: 0.7rem; letter-spacing: 0.05em; }
                .value { color: #0f172a; font-size: 0.95rem; font-weight: 500; }
                .amount-highlight { font-size: 1.6rem; font-weight: 800; color: #005baa; }
                .footer { margin-top: 60px; text-align: center; border-top: 1px solid #f1f5f9; padding-top: 25px; }
                .footer-text { font-size: 0.8rem; color: #94a3b8; margin: 0; }
                .auto-gen-msg { font-size: 0.75rem; color: #94a3b8; margin-top: 8px; display: block; font-weight: 500; }
                .admin-tag { font-size: 0.65rem; color: #cbd5e1; text-transform: uppercase; letter-spacing: 0.05em; margin-top: 15px; display: block; }
                @media print {
                    @page { margin: 15mm; }
                    body { padding: 0; }
                    .receipt-container { max-width: 100%; margin: 0; padding: 0; }
                }
            </style>
        </head>
        <body>
            <div class="receipt-container">
                <div class="receipt-header">
                    <div class="logo-area">
                        <img src="${logoUrl}" class="logo-img" onerror="this.style.display='none'">
                        <div class="brand-name">LifeConnect</div>
                    </div>
                    <div class="receipt-title">Donation Receipt #${payment.id}</div>
                </div>
                <table class="details-table">
                    <tr><td class="label">Contributor Name</td><td class="value">${payment.full_name || '—'}</td></tr>
                    <tr><td class="label">Email Address</td><td class="value">${payment.email || 'N/A'}</td></tr>
                    <tr><td class="label">Donation Amount</td><td class="value amount-highlight">${amountStr}</td></tr>
                    <tr><td class="label">Transaction Date</td><td class="value">${dateStr}</td></tr>
                    <tr><td class="label">Status</td><td class="value" style="font-weight: 700;">${status}</td></tr>
                    <tr><td class="label">Donor Note / Message</td><td class="value" style="font-style: italic; color: #475569;">${payment.note || 'No additional notes provided.'}</td></tr>
                </table>
                <div class="footer">
                    <p class="footer-text">Thank you for your generous contribution to LifeConnect. Your support saves lives.</p>
                    <span class="auto-gen-msg">This is a computer-generated receipt and does not require a physical signature.</span>
                    <span class="admin-tag">Report Generated by: ${currentAdmin}</span>
                </div>
            </div>
            <script>
                window.onload = function() {
                    window.print();
                    setTimeout(function() { window.close(); }, 500);
                };
            </script>
        </body>
        </html>
    `);
    printWindow.document.close();
}
