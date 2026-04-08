
// ============================================
// SAMPLE DONATION DATA
// In production, fetch this from your PHP backend
// ============================================
const donationsData = [
    {
        id: "DON-2024-008",
        date: "2024-10-15",
        amount: 10000,
        campaign: "Emergency Medical Fund",
        status: "Completed"
    },
    {
        id: "DON-2024-007",
        date: "2024-09-28",
        amount: 5000,
        campaign: "Child Surgery Campaign",
        status: "Completed"
    },
    {
        id: "DON-2024-006",
        date: "2024-09-10",
        amount: 7500,
        campaign: "Cancer Treatment Support",
        status: "Completed"
    },
    {
        id: "DON-2024-005",
        date: "2024-08-22",
        amount: 3000,
        campaign: "Blood Donation Drive",
        status: "Completed"
    },
    {
        id: "DON-2024-004",
        date: "2024-08-05",
        amount: 5000,
        campaign: "Dialysis Equipment Fund",
        status: "Completed"
    },
    {
        id: "DON-2024-003",
        date: "2024-07-18",
        amount: 8000,
        campaign: "COVID Relief Fund",
        status: "Completed"
    },
    {
        id: "DON-2024-002",
        date: "2024-07-01",
        amount: 4000,
        campaign: "Medical Equipment Drive",
        status: "Completed"
    },
    {
        id: "DON-2024-001",
        date: "2024-06-15",
        amount: 2500,
        campaign: "Healthcare Workers Support",
        status: "Completed"
    }
];


// ============================================
// LOAD DONATIONS DATA
// ============================================
function loadDonations() {
    const tableBody = document.getElementById('donationsTableBody');
    const totalAmountEl = document.getElementById('totalAmount');
    const donationsCountEl = document.getElementById('donationsCount');
    
    // Calculate total
    const total = donationsData.reduce((sum, donation) => sum + donation.amount, 0);
    totalAmountEl.textContent = 'Rs. ' + total.toLocaleString();
    donationsCountEl.textContent = donationsData.length + ' Donations Made';
    
    // Clear existing rows
    tableBody.innerHTML = '';
    
    // Add donation rows
    donationsData.forEach(donation => {
        const row = document.createElement('tr');
        row.onclick = () => openCertificateModal(donation);
        
        row.innerHTML = `
            <td data-label="Donation ID" class="donation-id">${donation.id}</td>
            <td data-label="Date">${formatDate(donation.date)}</td>
            <td data-label="Amount" class="donation-amount">Rs. ${donation.amount.toLocaleString()}</td>
            <td data-label="Campaign">${donation.campaign}</td>
            <td data-label="Status">
                <span class="donation-status ${donation.status === 'Completed' ? 'status-completed' : 'status-pending'}">
                    ${donation.status}
                </span>
            </td>
            <td data-label="Action">
                <button class="view-certificate-btn" onclick="event.stopPropagation(); openCertificateModal(${JSON.stringify(donation).replace(/"/g, '&quot;')})">
                    View Certificate
                </button>
            </td>
        `;
        
        tableBody.appendChild(row);
    });
}

// ============================================
// FORMAT DATE FUNCTION
// ============================================
function formatDate(dateString) {
    const options = { year: 'numeric', month: 'long', day: 'numeric' };
    return new Date(dateString).toLocaleDateString('en-US', options);
}

// ============================================
// CERTIFICATE MODAL FUNCTIONS
// ============================================
let currentDonation = null;

function openCertificateModal(donation) {
    currentDonation = donation;
    
    // Update certificate content
    document.getElementById('certAmount').textContent = 'Rs. ' + donation.amount.toLocaleString();
    document.getElementById('certDonationId').textContent = donation.id;
    document.getElementById('certDate').textContent = formatDate(donation.date);
    document.getElementById('certCampaign').textContent = donation.campaign;
    
    // Generate certificate ID
    const certId = 'CERT-' + donation.id.replace('DON-', '') + '-' + 
                   Math.random().toString(36).substring(2, 8).toUpperCase();
    document.getElementById('certCertificateId').textContent = certId;
    
    // Show modal
    document.getElementById('certificateModal').classList.add('active');
}

function closeCertificateModal() {
    document.getElementById('certificateModal').classList.remove('active');
}

// ============================================
// DOWNLOAD CERTIFICATE FUNCTION
// ============================================
function downloadCertificate() {
    if (!currentDonation) return;
    
    // In a real application, you would:
    // 1. Send request to server to generate PDF
    // 2. Server generates PDF using library like TCPDF or FPDF
    // 3. Return PDF file for download
    
    // For demo purposes, we'll show an alert
    alert('Certificate download started!\n\n' +
          'Donation ID: ' + currentDonation.id + '\n' +
          'Amount: Rs. ' + currentDonation.amount.toLocaleString() + '\n\n' +
          'In production, this would download a PDF certificate.');
    
    // In production, you would do something like:
    // window.location.href = 'download_certificate.php?donation_id=' + currentDonation.id;
}

// ============================================
// CLOSE MODAL ON OUTSIDE CLICK
// ============================================
document.getElementById('certificateModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeCertificateModal();
    }
});

// ============================================
// INITIALIZE PAGE
// ============================================
document.addEventListener('DOMContentLoaded', function() {
    loadDonations();
});

// ============================================
// PRINT CERTIFICATE FUNCTION (Optional)
// ============================================
function printCertificate() {
    window.print();
}
