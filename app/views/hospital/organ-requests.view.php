<?php
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?php echo ROOT ?? '/life-connect'; ?>/public/assets/css/hospital/hospital.css">
    <title>Organ Requests - Hospital Management - LifeConnect</title>
</head>
<body>
    <?php
        $current_page = 'organ-requests';
    
        require_once __DIR__ . '/header.php';
    ?>

    <div class="container">
        <div class="main-content">
            <?php require_once __DIR__ . '/sidebar.php'; ?>

            <div class="content-area">
                <div class="content-section" style="display: block;">
                    <div class="content-header">
                        <h2>Organ Requests Management</h2>
                        <p>Create, edit, and delete organ requests with urgency selection.</p>
                    </div>
                    <div class="content-body">
                        <div class="action-section">
                            <h3>Request Actions</h3>
                            <div class="action-buttons">
                                <button class="btn btn-primary" onclick="openRequestModal()">Add New Request</button>
                            </div>
                        </div>

                        
                        <div class="organ-request-options">
                            <h3 style="text-align: center; margin-bottom: 2rem; color: #2c3e50; font-size: 1.5rem;">Select Organ Type</h3>
                            <div class="organ-options-grid">
                                <?php foreach (($organs ?? []) as $organ): ?>
                                    <div class="organ-option-card" onclick='selectOrganType(<?= (int)$organ->id ?>, <?= json_encode($organ->name, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>)' style="cursor: pointer; transition: all 0.3s ease;">
                                        <h4 style="margin: 0.5rem 0; color: #1f2937;"><?= htmlspecialchars($organ->name) ?></h4>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <div class="search-bar">
                            <input type="text" class="search-input" placeholder="Search by organ type or Urgency">
                        </div>

                        <div class="filter-section">
                            <select class="filter-select">
                                <option value="">All Organs</option>
                                <?php foreach (($organs ?? []) as $organ): ?>
                                    <option value="<?= (int)$organ->id ?>"><?= htmlspecialchars($organ->name) ?></option>
                                <?php endforeach; ?>
                            </select>
                            <select class="filter-select">
                                <option value="">All Urgency</option>
                                <option value="high">High</option>
                                <option value="medium">Medium</option>
                                <option value="low">Low</option>
                                <option value="emergency">Emergency</option>
                            </select>
                        </div>

                        <div class="data-table">
                            <div class="table-header">
                                <h4>Organ Requests</h4>
                            </div>
                            <div class="table-content" id="organ-requests-table">
                                <div class="table-row" style="font-weight: 600; background: var(--gray-bg-color);">
                                    <div class="table-cell">Organ Type</div>
                                    <div class="table-cell">Urgency</div>
                                    <div class="table-cell">Created Date</div>
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

    
    <div class="modal" id="request-modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Add Organ Request</h3>
                <button class="modal-close" onclick="closeRequestModal()">×</button>
            </div>
            <div>
                <div class="form-group">
                    <label class="form-label">Organ Type</label>
                    <select class="form-select" id="organ-type">
                        <option value="">Select Organ Type</option>
                        <?php foreach (($organs ?? []) as $organ): ?>
                            <option value="<?= (int)$organ->id ?>"><?= htmlspecialchars($organ->name) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Urgency Level</label>
                    <select class="form-select" id="urgency-level">
                        <option value="">Select Urgency</option>
                        <option value="low">Low</option>
                        <option value="medium">Medium</option>
                        <option value="high">High</option>
                        <option value="emergency">Emergency</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Recipient Age</label>
                    <input class="form-input" id="recipient-age" type="number" min="18" max="80" placeholder="18 - 80">
                </div>
                <div class="form-group">
                    <label class="form-label">Recipient Blood Group</label>
                    <select class="form-select" id="recipient-blood-group">
                        <option value="">Select Blood Group</option>
                        <option value="A+">A+</option>
                        <option value="A-">A-</option>
                        <option value="B+">B+</option>
                        <option value="B-">B-</option>
                        <option value="AB+">AB+</option>
                        <option value="AB-">AB-</option>
                        <option value="O+">O+</option>
                        <option value="O-">O-</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Recipient Gender</label>
                    <select class="form-select" id="recipient-gender">
                        <option value="">Select Gender</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">HLA-typing</label>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: .75rem;">
                        <div>
                            <div style="font-weight: 800; margin-bottom: .35rem;">HLA-A (A1)</div>
                            <select class="form-select" id="recipient-hla-a1">
                                <option value="">Select Allele</option>
                                <option value="A*01">A*01</option>
                                <option value="A*02">A*02</option>
                                <option value="A*03">A*03</option>
                                <option value="A*11">A*11</option>
                                <option value="A*24">A*24</option>
                                <option value="A*33">A*33</option>
                                <option value="A*68">A*68</option>
                            </select>
                        </div>
                        <div>
                            <div style="font-weight: 800; margin-bottom: .35rem;">HLA-A (A2)</div>
                            <select class="form-select" id="recipient-hla-a2">
                                <option value="">Select Allele</option>
                                <option value="A*01">A*01</option>
                                <option value="A*02">A*02</option>
                                <option value="A*03">A*03</option>
                                <option value="A*11">A*11</option>
                                <option value="A*24">A*24</option>
                                <option value="A*33">A*33</option>
                                <option value="A*68">A*68</option>
                            </select>
                        </div>
                        <div>
                            <div style="font-weight: 800; margin-bottom: .35rem;">HLA-B (B1)</div>
                            <select class="form-select" id="recipient-hla-b1">
                                <option value="">Select Allele</option>
                                <option value="B*07">B*07</option>
                                <option value="B*08">B*08</option>
                                <option value="B*15">B*15</option>
                                <option value="B*35">B*35</option>
                                <option value="B*38">B*38</option>
                                <option value="B*44">B*44</option>
                                <option value="B*51">B*51</option>
                                <option value="B*52">B*52</option>
                                <option value="B*57">B*57</option>
                                <option value="B*58">B*58</option>
                            </select>
                        </div>
                        <div>
                            <div style="font-weight: 800; margin-bottom: .35rem;">HLA-B (B2)</div>
                            <select class="form-select" id="recipient-hla-b2">
                                <option value="">Select Allele</option>
                                <option value="B*07">B*07</option>
                                <option value="B*08">B*08</option>
                                <option value="B*15">B*15</option>
                                <option value="B*35">B*35</option>
                                <option value="B*38">B*38</option>
                                <option value="B*44">B*44</option>
                                <option value="B*51">B*51</option>
                                <option value="B*52">B*52</option>
                                <option value="B*57">B*57</option>
                                <option value="B*58">B*58</option>
                            </select>
                        </div>
                        <div>
                            <div style="font-weight: 800; margin-bottom: .35rem;">HLA-DRB1 (DR1)</div>
                            <select class="form-select" id="recipient-hla-dr1">
                                <option value="">Select Allele</option>
                                <option value="DRB1*01">DRB1*01</option>
                                <option value="DRB1*03">DRB1*03</option>
                                <option value="DRB1*04">DRB1*04</option>
                                <option value="DRB1*07">DRB1*07</option>
                                <option value="DRB1*11">DRB1*11</option>
                                <option value="DRB1*13">DRB1*13</option>
                                <option value="DRB1*14">DRB1*14</option>
                                <option value="DRB1*15">DRB1*15</option>
                            </select>
                        </div>
                        <div>
                            <div style="font-weight: 800; margin-bottom: .35rem;">HLA-DRB1 (DR2)</div>
                            <select class="form-select" id="recipient-hla-dr2">
                                <option value="">Select Allele</option>
                                <option value="DRB1*01">DRB1*01</option>
                                <option value="DRB1*03">DRB1*03</option>
                                <option value="DRB1*04">DRB1*04</option>
                                <option value="DRB1*07">DRB1*07</option>
                                <option value="DRB1*11">DRB1*11</option>
                                <option value="DRB1*13">DRB1*13</option>
                                <option value="DRB1*14">DRB1*14</option>
                                <option value="DRB1*15">DRB1*15</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Reason for Transplant</label>
                    <textarea class="form-textarea" id="transplant-reason" placeholder="e.g., End-stage renal disease" ></textarea>
                </div>
                <button class="btn btn-primary" onclick="saveRequest()">Save Request</button>
            </div>
        </div>
    </div>

    <!-- More Details Modal -->
    <div class="modal" id="request-details-modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Organ Request Details</h3>
                <button class="modal-close" onclick="closeDetailsModal()">×</button>
            </div>
            <div style="display: grid; gap: 0.75rem;">
                <div><strong>Organ Type:</strong> <span id="details-organ"></span></div>
                <div><strong>Urgency:</strong> <span id="details-urgency"></span></div>
                <div><strong>Status:</strong> <span id="details-status"></span></div>
                <div><strong>Edited:</strong> <span id="details-edited"></span></div>
                <div><strong>Edit Reason:</strong> <span id="details-edit-reason"></span></div>
                <div><strong>Recipient Age:</strong> <span id="details-age"></span></div>
                <div><strong>Blood Group:</strong> <span id="details-blood"></span></div>
                <div><strong>Gender:</strong> <span id="details-gender"></span></div>
                <div><strong>HLA-typing:</strong> <span id="details-hla"></span></div>
                <div><strong>Reason for Transplant:</strong> <span id="details-reason"></span></div>
            </div>
        </div>
    </div>

    <footer style="background: linear-gradient(135deg, #005baa 0%, #003b6e 100%); color: white; text-align: center; padding: 20px; margin-top: 40px; box-shadow: 0 -4px 20px rgba(0, 91, 170, 0.2);">
        <p style="margin: 0; font-size: 14px;">Copyright © 2025 Ministry of Health - LifeConnect Sri Lanka</p>
    </footer>

    <script>
        function openRequestModal() {
            document.getElementById('request-modal').classList.add('show');
        }
        function closeRequestModal() {
            document.getElementById('request-modal').classList.remove('show');
            clearHlaAllelesUi();
        }

        function clearHlaAllelesUi() {
            ['recipient-hla-a1','recipient-hla-a2','recipient-hla-b1','recipient-hla-b2','recipient-hla-dr1','recipient-hla-dr2']
                .forEach(id => {
                    const el = document.getElementById(id);
                    if (el) el.value = '';
                });
        }

        function getHlaAllelesUiValue() {
            const a1 = document.getElementById('recipient-hla-a1');
            const a2 = document.getElementById('recipient-hla-a2');
            const b1 = document.getElementById('recipient-hla-b1');
            const b2 = document.getElementById('recipient-hla-b2');
            const dr1 = document.getElementById('recipient-hla-dr1');
            const dr2 = document.getElementById('recipient-hla-dr2');
            const v = (el) => String(el ? el.value : '').trim();

            const parts = [
                `A1=${v(a1)}`,
                `A2=${v(a2)}`,
                `B1=${v(b1)}`,
                `B2=${v(b2)}`,
                `DR1=${v(dr1)}`,
                `DR2=${v(dr2)}`,
            ];

            if (parts.every(p => p.endsWith('='))) return '';
            return parts.join('; ');
        }
        
                function selectOrganType(organId, organName) {
            document.querySelectorAll('.organ-option-card').forEach(card => {
                card.classList.remove('selected');
            });
            event.currentTarget.classList.add('selected');
            
            const organSelect = document.getElementById('organ-type');
            if (organSelect) {
                        organSelect.value = String(organId);
            }
            
                    showServerMessage(`Selected organ type: ${organName}`, 'success');
            openRequestModal();
        }

        function saveRequest() { 
            const organId = document.getElementById('organ-type').value;
            const urgency = document.getElementById('urgency-level').value;
            const age = document.getElementById('recipient-age') ? document.getElementById('recipient-age').value : '';
            const bloodGroup = document.getElementById('recipient-blood-group') ? document.getElementById('recipient-blood-group').value : '';
            const gender = document.getElementById('recipient-gender') ? document.getElementById('recipient-gender').value : '';
            const hlaTyping = getHlaAllelesUiValue();
            const transplantReason = document.getElementById('transplant-reason') ? document.getElementById('transplant-reason').value : '';
            
            if (!organId || !urgency) {
                showServerMessage('Error - Please fill all required fields', 'error');
                return;
            }

            const ageNum = parseInt(age, 10);
            if (!age || isNaN(ageNum) || ageNum < 18 || ageNum > 80) {
                showServerMessage('Error - Recipient age must be between 18 and 80', 'error');
                return;
            }

            if (!bloodGroup) {
                showServerMessage('Error - Please select a blood group', 'error');
                return;
            }

            if (!gender) {
                showServerMessage('Error - Please select a gender', 'error');
                return;
            }

            if (!transplantReason || !transplantReason.trim()) {
                showServerMessage('Error - Reason for transplant is required', 'error');
                return;
            }
            
            const form = document.createElement('form');
            form.method = 'POST';
            form.style.display = 'none';
            
            const actionInput = document.createElement('input');
            actionInput.type = 'hidden';
            actionInput.name = 'action';
            actionInput.value = 'add_organ_request';
            form.appendChild(actionInput);
            
            const organInput = document.createElement('input');
            organInput.type = 'hidden';
            organInput.name = 'organ_id';
            organInput.value = organId;
            form.appendChild(organInput);
            
            const urgencyInput = document.createElement('input');
            urgencyInput.type = 'hidden';
            urgencyInput.name = 'urgency';
            urgencyInput.value = urgency;
            form.appendChild(urgencyInput);

            const ageInput = document.createElement('input');
            ageInput.type = 'hidden';
            ageInput.name = 'recipient_age';
            ageInput.value = ageNum;
            form.appendChild(ageInput);

            const bgInput = document.createElement('input');
            bgInput.type = 'hidden';
            bgInput.name = 'blood_group';
            bgInput.value = bloodGroup;
            form.appendChild(bgInput);

            const hlaInput = document.createElement('input');
            hlaInput.type = 'hidden';
            hlaInput.name = 'hla_typing';
            hlaInput.value = hlaTyping;
            form.appendChild(hlaInput);

            const genderInput = document.createElement('input');
            genderInput.type = 'hidden';
            genderInput.name = 'gender';
            genderInput.value = gender;
            form.appendChild(genderInput);
            
            const reasonInput = document.createElement('input');
            reasonInput.type = 'hidden';
            reasonInput.name = 'transplant_reason';
            reasonInput.value = transplantReason;
            form.appendChild(reasonInput);
            
            document.body.appendChild(form);
            form.submit();
        }

        function editRequest(requestId) { 
            showServerMessage('Opening edit form for organ request ID: ' + requestId, 'info'); 
        }

        async function deleteRequest(requestId) { 
            const ok = await hcConfirm('Are you sure you want to delete this organ request?', { danger: true });
            if (!ok) return;

            const form = document.createElement('form');
            form.method = 'POST';
            form.style.display = 'none';
            
            const actionInput = document.createElement('input');
            actionInput.type = 'hidden';
            actionInput.name = 'action';
            actionInput.value = 'delete_organ_request';
            form.appendChild(actionInput);
            
            const requestIdInput = document.createElement('input');
            requestIdInput.type = 'hidden';
            requestIdInput.name = 'request_id';
            requestIdInput.value = requestId;
            form.appendChild(requestIdInput);
            
            document.body.appendChild(form);
            form.submit();
        }
        
        function loadOrganRequests() {
            const requests = <?php echo json_encode($organ_requests); ?>;
            // Store original requests globally for filtering
            window.allOrganRequests = requests || [];
            console.log('Loaded organ requests:', window.allOrganRequests);
            
            // Initial display of all requests
            updateOrganRequestsTable(window.allOrganRequests);
        }
        
        function updateOrganRequestsTable(requests) {
            const tableContent = document.querySelector('#organ-requests-table');
            if (!tableContent) return;
            
            const existingRows = tableContent.querySelectorAll('.table-row:not(:first-child)');
            existingRows.forEach(row => row.remove());
            
            if (requests.length === 0) {
                const emptyRow = document.createElement('div');
                emptyRow.className = 'table-row';
                emptyRow.innerHTML = '<div class="table-cell" style="text-align: center; color: #999;">No matching requests found</div>';
                tableContent.appendChild(emptyRow);
                return;
            }
            
            requests.forEach(request => {
                const row = document.createElement('div');
                row.className = 'table-row';
                if (request.edited_reason && String(request.edited_reason).trim() !== '') {
                    row.style.fontStyle = 'italic';
                }
                row.innerHTML = `
                    <div class="table-cell name" data-label="Organ Type">${request.organ_name}</div>
                    <div class="table-cell" data-label="Urgency">
                        <span class="status-badge ${request.priority_level === 'URGENT' || request.priority_level === 'CRITICAL' ? 'status-danger' : request.priority_level === 'HIGH' ? 'status-active' : 'status-pending'}">${request.priority_level}</span>
                    </div>
                    <div class="table-cell" data-label="Created Date">${new Date(request.created_at).toLocaleDateString('en-GB')}</div>
                    <div class="table-cell" data-label="Status">${request.status || 'PENDING'}</div>
                    <div class="table-cell" data-label="Actions">
                        <div style="display: flex; gap: 0.5rem; align-items: center; flex-wrap: nowrap;">
                            <button class="btn btn-secondary btn-small" onclick="viewDetails(${request.id})" style="white-space: nowrap;">More Details</button>
                            <button class="btn btn-secondary btn-small" onclick="editRequest(${request.id})" style="white-space: nowrap;">Edit</button>
                            <button class="btn btn-danger btn-small" onclick="deleteRequest(${request.id})" style="white-space: nowrap;">Delete</button>
                        </div>
                    </div>
                `;
                tableContent.appendChild(row);
            });
        }

        function viewDetails(requestId) {
            const request = (window.allOrganRequests || []).find(r => r.id == requestId);
            if (!request) return;

            document.getElementById('details-organ').textContent = request.organ_name || '';
            document.getElementById('details-urgency').textContent = request.priority_level || '';
            document.getElementById('details-status').textContent = request.status || 'PENDING';
            const editedText = (request.edited_reason && String(request.edited_reason).trim() !== '') ? 'Yes' : 'No';
            document.getElementById('details-edited').textContent = editedText;
            document.getElementById('details-edit-reason').textContent = (request.edited_reason && String(request.edited_reason).trim() !== '') ? request.edited_reason : 'N/A';
            document.getElementById('details-age').textContent = request.recipient_age ?? 'N/A';
            document.getElementById('details-blood').textContent = request.blood_group || 'N/A';
            document.getElementById('details-gender').textContent = request.gender || 'N/A';
            document.getElementById('details-hla').textContent = request.hla_typing || 'N/A';
            document.getElementById('details-reason').textContent = request.transplant_reason || 'N/A';

            document.getElementById('request-details-modal').classList.add('show');
        }

        function closeDetailsModal() {
            document.getElementById('request-details-modal').classList.remove('show');
        }

        /**
         * Reset all filters to show all requests
         */
        function resetOrganFilters() {
            // Reset search input
            const searchInput = document.querySelector('.search-input');
            if (searchInput) {
                searchInput.value = '';
            }
            
            // Reset filter selects to "All"
            const filterSelects = document.querySelectorAll('.filter-select');
            filterSelects.forEach(select => {
                select.value = '';
            });
            
            // Update table with all requests
            if (window.allOrganRequests) {
                updateOrganRequestsTable(window.allOrganRequests);
            }
        }

        /**
         * Filter organ requests - FIXED VERSION
         */
        function filterOrganRequests() {
            if (!window.allOrganRequests || window.allOrganRequests.length === 0) {
                console.log('No requests available to filter');
                return;
            }
            
            // Get the actual filter elements
            const searchInput = document.querySelector('.search-input');
            const allFilterSelects = document.querySelectorAll('.filter-select');
            
            if (!searchInput || allFilterSelects.length < 2) {
                console.log('Filter elements not found');
                return;
            }
            
            // Get current filter values
            const searchQuery = (searchInput.value || '').toLowerCase().trim();
            const organFilterValue = (allFilterSelects[0].value || '').trim();
            const urgencyFilterValue = (allFilterSelects[1].value || '').toLowerCase().trim();
            
            console.log('=== FILTER STATE ===');
            console.log('Search:', searchQuery);
            console.log('Organ Filter:', organFilterValue);
            console.log('Urgency Filter:', urgencyFilterValue);
            console.log('Total requests:', window.allOrganRequests.length);
            
            // Filter the requests
            const filtered = window.allOrganRequests.filter(request => {
                const organName = (request.organ_name || '').toLowerCase().trim();
                const urgency = (request.priority_level || '').toLowerCase().trim();
                const priority = (request.priority_level || '').toUpperCase().trim();
                
                // PRIORITY 1: If search query exists, use ONLY search (ignore other filters)
                // This gives intuitive search behavior: typing "kidney" shows ONLY matching results
                if (searchQuery) {
                    const q = searchQuery;

                    const matchesOrgan = organName.includes(q);

                    // Allow searching by urgency keywords too
                    // DB values: NORMAL / URGENT / CRITICAL
                    // UI labels: low/medium -> NORMAL, high -> URGENT, emergency -> CRITICAL
                    let matchesUrgency = urgency.includes(q) || priority.toLowerCase().includes(q);
                    if (!matchesUrgency) {
                        if (q === 'emergency' || q === 'critical') {
                            matchesUrgency = (priority === 'CRITICAL');
                        } else if (q === 'high' || q === 'urgent') {
                            matchesUrgency = (priority === 'URGENT');
                        } else if (q === 'low' || q === 'medium' || q === 'normal') {
                            matchesUrgency = (priority === 'NORMAL');
                        }
                    }

                    const matches = matchesOrgan || matchesUrgency;
                    if (matches) {
                        console.log(`✓ SEARCH MATCH: "${organName}"`);
                    }
                    return matches;
                }
                
                // PRIORITY 2: If NO search, apply organ and urgency filters with AND logic
                
                // FILTER 1: Organ type (exact match)
                let matchesOrgan = true;
                if (organFilterValue && organFilterValue !== '') {
                    matchesOrgan = (String(request.organ_id) === String(organFilterValue));
                    console.log(`  Organ check: request.organ_id="${request.organ_id}" vs "${organFilterValue}" => ${matchesOrgan}`);
                }
                
                // FILTER 2: Urgency level
                let matchesUrgency = true;
                if (urgencyFilterValue && urgencyFilterValue !== '') {
                    const selected = urgencyFilterValue;

                    if (selected === 'emergency') {
                        matchesUrgency = (priority === 'CRITICAL');
                    } else if (selected === 'high') {
                        matchesUrgency = (priority === 'URGENT');
                    } else if (selected === 'medium' || selected === 'low') {
                        matchesUrgency = (priority === 'NORMAL');
                    } else {
                        matchesUrgency = true;
                    }

                    console.log(`  Urgency check: priority="${priority}" selected="${selected}" => ${matchesUrgency}`);
                }
                
                const passes = matchesOrgan && matchesUrgency;
                if (passes) {
                    console.log(`✓ FILTER MATCH: "${organName}" | "${urgency}"`);
                }
                
                return passes;
            });
            
            console.log(`Final Results: ${filtered.length} requests match filters`);
            console.log('===================');
            
            updateOrganRequestsTable(filtered);
        }

        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM Content Loaded - initializing organ requests');
            
            // Load initial data
            loadOrganRequests();
            
            // Attach event listeners with a small delay to ensure elements are ready
            setTimeout(function() {
                console.log('Attaching event listeners...');
                
                // Setup search input listener
                const searchInput = document.querySelector('.search-input');
                console.log('Search input found:', !!searchInput);
                if (searchInput) {
                    searchInput.addEventListener('input', function(e) {
                        console.log('Search input changed:', e.target.value);
                        filterOrganRequests();
                    });
                }
                
                // Setup filter select listeners
                const filterSelects = document.querySelectorAll('.filter-select');
                console.log('Filter selects found:', filterSelects.length);
                filterSelects.forEach((select, index) => {
                    select.addEventListener('change', function(e) {
                        console.log(`Filter ${index} changed:`, e.target.value);
                        filterOrganRequests();
                    });
                });
            }, 100);
        });
    </script>

    <?php
        require_once __DIR__ . '/footer.php';
    ?>
</body>
</html>
