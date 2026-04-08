
        let currentLab = null;

        /**
         * Open lab details modal
         */
        function openLabModal(lab) {
            currentLab = lab;
            document.getElementById('modalLabName').textContent = lab.name;
            document.getElementById('modalLabNameValue').textContent = lab.name;
            document.getElementById('modalCertificate').textContent = lab.certificate;
            document.getElementById('modalLocation').textContent = lab.location;
            
            // Display tests
            const testsContainer = document.getElementById('modalTests');
            testsContainer.innerHTML = '';
            
            lab.tests.forEach(test => {
                const testBadge = document.createElement('div');
                testBadge.className = 'test-badge';
                testBadge.textContent = '✓ ' + test;
                testsContainer.appendChild(testBadge);
            });
            
            document.getElementById('labModal').classList.add('active');
        }

        /**
         * Close lab details modal
         */
        function closeLabModal() {
            document.getElementById('labModal').classList.remove('active');
        }

        /**
         * Book test for selected lab
         */
        function bookTest() {
            if (currentLab) {
                alert('Booking test at ' + currentLab.name + '!\nCertificate: ' + currentLab.certificate);
                // In real application, redirect to booking page
                // window.location.href = 'book-test.php?lab_id=' + currentLab.id;
            }
        }

    