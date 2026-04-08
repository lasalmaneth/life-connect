let currentPDF = '';

function openPDF(filename) {
    currentPDF = filename;
    document.getElementById('pdfTitle').textContent = filename;
    
    // For demo purposes, using a sample PDF URL
    // Replace this with your actual PDF path: 'path/to/your/pdfs/' + filename
    const pdfUrl = 'https://www.w3.org/WAI/ER/tests/xhtml/testfiles/resources/pdf/dummy.pdf';
    
    document.getElementById('pdfViewer').src = pdfUrl;
    document.getElementById('pdfModal').classList.add('active');
}

function closeModal() {
    document.getElementById('pdfModal').classList.remove('active');
    document.getElementById('pdfViewer').src = '';
}

function downloadPDF() {
    // For demo purposes, using a sample PDF URL
    // Replace with your actual PDF path
    const pdfUrl = 'https://www.w3.org/WAI/ER/tests/xhtml/testfiles/resources/pdf/dummy.pdf';
    
    const link = document.createElement('a');
    link.href = pdfUrl;
    link.download = currentPDF;
    link.click();
}

// Close modal when clicking outside
document.getElementById('pdfModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeModal();
    }
});




