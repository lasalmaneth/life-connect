<?php
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?php echo ROOT ?? '/life-connect'; ?>/public/assets/css/hospital/hospital.css">
    <title>Recipient Patients - Hospital Management - LifeConnect</title>
</head>
<body>
    <?php
        $current_page = 'recipients';
    
        require_once __DIR__ . '/header.php';
    ?>

    <div class="container">
        <div class="main-content">
            <?php require_once __DIR__ . '/sidebar.php'; ?>

            <div class="content-area">
                <div class="content-section" style="display: block;">
                    <div class="content-header">
                        <h2>Recipient Patient Management</h2>
                        <p>Add, update, and view recipient patient records and treatment logs.</p>
                    </div>
                    <div class="content-body">
                        <div class="action-section">
                            <h3>Patient Actions</h3>
                            <div class="action-buttons">
                                <button class="btn btn-primary" onclick="openRecipientModal()">Add Recipient</button>
                                <button class="btn btn-secondary" onclick="exportRecipients()">Export Records</button>
                            </div>
                        </div>

                        <div class="search-bar">
                            <span class="search-icon">Search:</span>
                            <input type="text" class="search-input" placeholder="Search by patient ID or name...">
                        </div>

                        <div class="data-table">
                            <div class="table-header">
                                <h4>Recipient Patients</h4>
                            </div>
                            <div class="table-content" id="recipients-table">
                                <div class="table-row" style="font-weight: 600; background: var(--gray-bg-color);">
                                    <div class="table-cell">Patient NIC</div>
                                    <div class="table-cell">Patient Name</div>
                                    <div class="table-cell">Organ Received</div>
                                    <div class="table-cell">Surgery Date</div>
                                    <div class="table-cell">Status</div>
                                    <div class="table-cell">Actions</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
    <div class="modal" id="recipient-modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Add Recipient Patient</h3>
                <button class="modal-close" onclick="closeRecipientModal()">×</button>
            </div>
            <div>
                <div class="form-group">
                    <label class="form-label">Patient NIC</label>
                    <input type="text" class="form-input" id="recipient-nic" placeholder="1999XXXXXXX">
                </div>
                <div class="form-group">
                    <label class="form-label">Patient Name</label>
                    <input type="text" class="form-input" id="recipient-name" placeholder="Full name">
                </div>
                <div class="form-group">
                    <label class="form-label">Organ Received</label>
                    <select class="form-select" id="recipient-organ">
                        <option value="">Select Organ</option>
                        <option value="kidney">Kidney</option>
                        <option value="liver">Liver</option>
                        <option value="heart">Heart</option>
                        <option value="lung">Lung</option>
                        <option value="skin">Skin Graft</option>
                        <option value="eye">Eye</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Surgery Date</label>
                    <input type="date" class="form-input" id="surgery-date">
                </div>
                <div class="form-group">
                    <label class="form-label">Treatment Notes</label>
                    <textarea class="form-textarea" id="treatment-notes" placeholder="Post-surgery treatment details..."></textarea>
                </div>
                <button class="btn btn-primary" onclick="saveRecipient()">Save Recipient</button>
            </div>
        </div>
    </div>

    
    <div class="modal" id="export-modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Export Recipient Records</h3>
                <button class="modal-close" onclick="closeExportModal()">×</button>
            </div>
            <div>
                <div class="form-group">
                    <label class="form-label">Select Export Format</label>
                    <select class="form-select" id="export-format">
                        <option value="">Choose format...</option>
                        <option value="xlsx">Excel (.xlsx) - For data analysis</option>
                        <option value="csv">CSV (.csv) - For generic data use</option>
                        <option value="pdf">PDF (.pdf) - For formal reports</option>
                    </select>
                </div>
                <button class="btn btn-primary" onclick="downloadExport()">Download Report</button>
            </div>
        </div>
    </div>

    <footer style="background: linear-gradient(135deg, #005baa 0%, #003b6e 100%); color: white; text-align: center; padding: 20px; margin-top: 40px; box-shadow: 0 -4px 20px rgba(0, 91, 170, 0.2); width: 100%;">
        <p style="margin: 0; font-size: 14px;">Copyright © 2025 Ministry of Health - LifeConnect Sri Lanka</p>
    </footer>

    <script>
                function openRecipientModal() { document.getElementById('recipient-modal').classList.add('show'); }
        function closeRecipientModal() { 
            document.getElementById('recipient-modal').classList.remove('show');
            document.querySelector('#recipient-modal .modal-header h3').textContent = 'Add Recipient Patient';
            document.getElementById('recipient-nic').value = '';
            document.getElementById('recipient-name').value = '';
            document.getElementById('recipient-organ').value = '';
            document.getElementById('surgery-date').value = '';
            document.getElementById('treatment-notes').value = '';
            
            const saveButton = document.querySelector('#recipient-modal button[onclick*="updateRecipient"]');
            if (saveButton) {
                saveButton.textContent = 'Save Recipient';
                saveButton.setAttribute('onclick', 'saveRecipient()');
            }
        }

        function saveRecipient() { 
            const nic = document.getElementById('recipient-nic').value;
            const name = document.getElementById('recipient-name').value;
            const organ = document.getElementById('recipient-organ').value;
            const date = document.getElementById('surgery-date').value;
            const notes = document.getElementById('treatment-notes').value;
            
            if (!nic || !name || !organ || !date) {
                showServerMessage('Error - Please fill all required fields', 'error');
                return;
            }
            
            const form = document.createElement('form');
            form.method = 'POST';
            form.style.display = 'none';
            
            const actionInput = document.createElement('input');
            actionInput.type = 'hidden';
            actionInput.name = 'action';
            actionInput.value = 'add_recipient';
            form.appendChild(actionInput);
            
            const nicInput = document.createElement('input');
            nicInput.type = 'hidden';
            nicInput.name = 'nic';
            nicInput.value = nic;
            form.appendChild(nicInput);
            
            const nameInput = document.createElement('input');
            nameInput.type = 'hidden';
            nameInput.name = 'name';
            nameInput.value = name;
            form.appendChild(nameInput);
            
            const organInput = document.createElement('input');
            organInput.type = 'hidden';
            organInput.name = 'organ_received';
            organInput.value = organ;
            form.appendChild(organInput);
            
            const dateInput = document.createElement('input');
            dateInput.type = 'hidden';
            dateInput.name = 'surgery_date';
            dateInput.value = date;
            form.appendChild(dateInput);
            
            const notesInput = document.createElement('input');
            notesInput.type = 'hidden';
            notesInput.name = 'treatment_notes';
            notesInput.value = notes;
            form.appendChild(notesInput);
            
            document.body.appendChild(form);
            form.submit();
        }

        function editRecipient(recipientId) { 
            const recipients = <?php echo json_encode($recipients); ?>;
            const recipient = recipients.find(r => r.recipient_id == recipientId);
            
            if (recipient) {
                document.querySelector('#recipient-modal .modal-header h3').textContent = 'Edit Recipient Patient';
                
                document.getElementById('recipient-nic').value = recipient.nic;
                document.getElementById('recipient-name').value = recipient.name;
                document.getElementById('recipient-organ').value = recipient.organ_received;
                document.getElementById('surgery-date').value = recipient.surgery_date;
                document.getElementById('treatment-notes').value = recipient.treatment_notes;
                
                const saveButton = document.querySelector('#recipient-modal button[onclick="saveRecipient()"]');
                if (saveButton) {
                    saveButton.textContent = 'Update Recipient';
                    saveButton.setAttribute('onclick', 'updateRecipient(' + recipientId + ')');
                }
                
                document.getElementById('recipient-modal').classList.add('show');
            }
        }

        function updateRecipient(recipientId) {
            const nic = document.getElementById('recipient-nic').value;
            const name = document.getElementById('recipient-name').value;
            const organ = document.getElementById('recipient-organ').value;
            const surgery_date = document.getElementById('surgery-date').value;
            const notes = document.getElementById('treatment-notes').value;
            
            if (!nic || !name || !organ || !surgery_date) {
                showServerMessage('Error - Please fill all required fields', 'error');
                return;
            }
            
            const form = document.createElement('form');
            form.method = 'POST';
            form.style.display = 'none';
            
            const actionInput = document.createElement('input');
            actionInput.type = 'hidden';
            actionInput.name = 'action';
            actionInput.value = 'update_recipient';
            form.appendChild(actionInput);
            
            const recipientIdInput = document.createElement('input');
            recipientIdInput.type = 'hidden';
            recipientIdInput.name = 'recipient_id';
            recipientIdInput.value = recipientId;
            form.appendChild(recipientIdInput);
            
            const nicInput = document.createElement('input');
            nicInput.type = 'hidden';
            nicInput.name = 'nic';
            nicInput.value = nic;
            form.appendChild(nicInput);
            
            const nameInput = document.createElement('input');
            nameInput.type = 'hidden';
            nameInput.name = 'name';
            nameInput.value = name;
            form.appendChild(nameInput);
            
            const organInput = document.createElement('input');
            organInput.type = 'hidden';
            organInput.name = 'organ_received';
            organInput.value = organ;
            form.appendChild(organInput);
            
            const dateInput = document.createElement('input');
            dateInput.type = 'hidden';
            dateInput.name = 'surgery_date';
            dateInput.value = surgery_date;
            form.appendChild(dateInput);
            
            const notesInput = document.createElement('input');
            notesInput.type = 'hidden';
            notesInput.name = 'treatment_notes';
            notesInput.value = notes;
            form.appendChild(notesInput);
            
            const statusInput = document.createElement('input');
            statusInput.type = 'hidden';
            statusInput.name = 'status';
            statusInput.value = 'Active';
            form.appendChild(statusInput);
            
            document.body.appendChild(form);
            form.submit();
        }
        
        function deleteRecipient(recipientId) {
            if (confirm('Are you sure you want to delete this recipient?')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.style.display = 'none';
                
                const actionInput = document.createElement('input');
                actionInput.type = 'hidden';
                actionInput.name = 'action';
                actionInput.value = 'delete_recipient';
                form.appendChild(actionInput);
                
                const recipientIdInput = document.createElement('input');
                recipientIdInput.type = 'hidden';
                recipientIdInput.name = 'recipient_id';
                recipientIdInput.value = recipientId;
                form.appendChild(recipientIdInput);
                
                document.body.appendChild(form);
                form.submit();
            }
        }

        function exportRecipients() { 
            document.getElementById('export-modal').classList.add('show'); 
        }

        function closeExportModal() {
            document.getElementById('export-modal').classList.remove('show');
        }

        function downloadExport() {
            const format = document.getElementById('export-format').value;
            if (!format) {
                showServerMessage('Please select an export format', 'error');
                return;
            }
            
            // Map format select values to backend format names
            const formatMap = {
                'pdf': 'pdf',
                'xlsx': 'xlsx',
                'csv': 'csv'
            };
            
            const backendFormat = formatMap[format] || format;
            
            // Trigger download by navigating to the export endpoint
            const downloadUrl = '/life-connect/hospital/export-recipients?format=' + encodeURIComponent(backendFormat);
            
            // Create a temporary anchor element to trigger download
            const link = document.createElement('a');
            link.href = downloadUrl;
            link.style.display = 'none';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
            
            // Show success message and close modal
            showServerMessage('Download started! File: Recipient_Report_' + new Date().toISOString().split('T')[0] + '.' + format, 'success');
            closeExportModal();
        }
        
        
        function loadRecipients() {
            const recipients = <?php echo json_encode($recipients); ?>;
            // Store globally for search filtering
            window.allRecipients = recipients || [];
            console.log('Loaded recipients:', window.allRecipients);
            updateRecipientsTable(window.allRecipients);
        }
        
        function updateRecipientsTable(recipients) {
            const tableContent = document.querySelector('#recipients-table');
            if (!tableContent) return;
            
            const existingRows = tableContent.querySelectorAll('.table-row:not(:first-child)');
            existingRows.forEach(row => row.remove());
            
            if (recipients.length === 0) {
                const emptyRow = document.createElement('div');
                emptyRow.className = 'table-row';
                emptyRow.innerHTML = '<div class="table-cell" style="text-align: center; color: #999;">No matching recipients found</div>';
                tableContent.appendChild(emptyRow);
                return;
            }
            
            recipients.forEach(recipient => {
                const row = document.createElement('div');
                row.className = 'table-row';
                row.innerHTML = `
                    <div class="table-cell name" data-label="NIC">${recipient.nic}</div>
                    <div class="table-cell" data-label="Name">${recipient.name}</div>
                    <div class="table-cell" data-label="Organ">${recipient.organ_received}</div>
                    <div class="table-cell" data-label="Surgery Date">${new Date(recipient.surgery_date).toLocaleDateString()}</div>
                    <div class="table-cell" data-label="Status">
                        <span class="status-badge ${recipient.status === 'Active' ? 'status-active' : recipient.status === 'Discharged' ? 'status-success' : 'status-pending'}">${recipient.status}</span>
                    </div>
                    <div class="table-cell" data-label="Actions">
                        <div style="display: flex; gap: 0.5rem; align-items: center; flex-wrap: nowrap;">
                            <button class="btn btn-secondary btn-small" onclick="editRecipient(${recipient.recipient_id})" style="white-space: nowrap;">Edit</button>
                            <button class="btn btn-danger btn-small" onclick="deleteRecipient(${recipient.recipient_id})" style="white-space: nowrap;">Delete</button>
                        </div>
                    </div>
                `;
                tableContent.appendChild(row);
            });
        }

                document.addEventListener('DOMContentLoaded', function() {
            console.log('Initializing recipients page');
            loadRecipients();
            
            // Setup search functionality
            const searchInput = document.querySelector('.search-input');
            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    const searchQuery = this.value.trim();
                    console.log('Search query:', searchQuery);
                    
                    if (searchQuery === '') {
                        // Load all recipients if search is empty
                        console.log('Search cleared, reloading all recipients');
                        updateRecipientsTable(window.allRecipients || []);
                    } else {
                        // Search locally first
                        searchRecipientsLocally(searchQuery);
                    }
                });
            }
        });
        
        /**
         * Search recipients locally in the allRecipients array
         */
        function searchRecipientsLocally(query) {
            if (!window.allRecipients) {
                console.log('No recipients loaded yet');
                return;
            }
            
            const searchQuery = query.toLowerCase().trim();
            console.log('Searching locally for:', searchQuery);
            
            // Filter recipients by NIC or name
            const filtered = window.allRecipients.filter(recipient => {
                const nic = (recipient.nic || '').toLowerCase();
                const name = (recipient.name || '').toLowerCase();
                
                const matches = nic.includes(searchQuery) || name.includes(searchQuery);
                
                if (matches) {
                    console.log(`✓ Match: NIC="${recipient.nic}" Name="${recipient.name}"`);
                }
                
                return matches;
            });
            
            console.log(`Found ${filtered.length} matching recipients`);
            updateRecipientsTable(filtered);
        }
        
        /**
         * Search recipients via API endpoint (fallback)
         */
        function searchRecipientsApi(query) {
            console.log('Searching via API:', query);
            fetch('/life-connect/hospital/search-recipients?q=' + encodeURIComponent(query), {
                method: 'GET',
                credentials: 'include'
            })
            .then(response => response.json())
            .then(data => {
                if (data.success && data.data) {
                    console.log('API returned', data.data.length, 'results');
                    updateRecipientsTable(data.data);
                } else {
                    console.error('Search failed:', data.message);
                    updateRecipientsTable([]);
                }
            })
            .catch(error => {
                console.error('Error searching recipients:', error);
                updateRecipientsTable([]);
            });
        }
    </script>

    <?php
        require_once __DIR__ . '/footer.php';
    ?>
</body>
</html>
