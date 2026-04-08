
    
        /**
         * Opens the specified modal by document type
         * @param {string} modalType - Type of modal to open (donorCard, consentForm, certificate)
         */
        function openModal(modalType) {
            let modalId = '';
            
            // Determine which modal to open based on type
            switch(modalType) {
                case 'donorCard':
                    modalId = 'donorCardModal';
                    break;
                case 'consentForm':
                    modalId = 'consentFormModal';
                    break;
                case 'certificate':
                    modalId = 'certificateModal';
                    break;
            }
            
            // Display the modal
            document.getElementById(modalId).style.display = 'block';
            
            // Prevent body scrolling when modal is open
            document.body.style.overflow = 'hidden';
        }

        /**
         * Closes the specified modal
         * @param {string} modalType - Type of modal to close
         */
        function closeModal(modalType) {
            let modalId = '';
            
            // Determine which modal to close
            switch(modalType) {
                case 'donorCard':
                    modalId = 'donorCardModal';
                    break;
                case 'consentForm':
                    modalId = 'consentFormModal';
                    break;
                case 'certificate':
                    modalId = 'certificateModal';
                    break;
            }
            
            // Hide the modal
            document.getElementById(modalId).style.display = 'none';
            
            // Restore body scrolling
            document.body.style.overflow = 'auto';
        }

        /**
         * Downloads PDF from database
         * This function sends request to PHP backend to retrieve PDF from database
         * @param {string} documentType - Type of document to download
         */
        function downloadPDF(documentType) {
            // Show loading message
            alert('Preparing your document for download...');
            
            // Create form data to send to PHP
            const formData = new FormData();
            formData.append('document_type', documentType);
            formData.append('action', 'download_pdf');
            
            // Send AJAX request to PHP backend
            fetch(window.location.href, {
                method: 'POST',
                body: formData
            })
            .then(response => response.blob())
            .then(blob => {
                // Create download link
                const url = window.URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = documentType + '_' + Date.now() + '.pdf';
                document.body.appendChild(a);
                a.click();
                
                // Clean up
                window.URL.revokeObjectURL(url);
                document.body.removeChild(a);
                
                alert('Document downloaded successfully!');
            })
            .catch(error => {
                console.error('Error downloading PDF:', error);
                alert('Error downloading document. Please try again.');
            });
        }

        /**
         * Close modal when clicking outside of it
         */
        window.onclick = function(event) {
            // Check if click is on modal background
            if (event.target.classList.contains('modal')) {
                event.target.style.display = 'none';
                document.body.style.overflow = 'auto';
            }
        }

        /**
         * Handle keyboard events (ESC key to close modal)
         */
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                // Close all modals when ESC is pressed
                const modals = document.querySelectorAll('.modal');
                modals.forEach(modal => {
                    modal.style.display = 'none';
                });
                document.body.style.overflow = 'auto';
            }
        });
    