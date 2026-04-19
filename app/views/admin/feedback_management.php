<div id="feedbacks" class="content-section" style="display: none; width: 100%; box-sizing: border-box; overflow-x: hidden !important;">
    <div class="content-header">
        <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 0.5rem;">
            <div style="padding: 10px; background: #fdf2f2; border: 1.5px solid #fee2e2; border-radius: 12px; color: #dc2626;">
                <i class="fa-solid fa-comments" style="font-size: 20px;"></i>
            </div>
            <div>
                <h2 style="margin: 0; font-size: 1.5rem; font-weight: 800; color: #0f172a;">Feedback Management</h2>
                <p style="margin: 0; color: #64748b; font-size: 0.95rem;">Monitor and respond to platform inquiries and user testimonials.</p>
            </div>
        </div>
    </div>

    <div class="content-body">
        <div class="search-bar" style="margin-bottom: 1.5rem;">
            <span class="search-icon"><i class="fa-solid fa-magnifying-glass"></i></span>
            <input type="text" class="search-input" placeholder="Search feedbacks by name, email or subject..." id="feedback-search" onkeyup="renderFeedbacksTable()">
        </div>

        <div class="data-table" style="background: white; border: 1px solid #e2e8f0; border-radius: 16px; overflow: hidden; width: 100%; box-sizing: border-box; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);">
            <div class="table-header" style="padding: 1.25rem 1.5rem; border-bottom: 1px solid #e2e8f0; background: #f8fafc;">
                <h4 style="margin: 0; font-size: 1rem; font-weight: 700; color: #1e293b;">Inquiry Stream</h4>
            </div>
            <div class="table-content" id="feedbacks-table">
                <div class="table-row" style="font-weight: 700; background: #f1f5f9; color: #475569; border-bottom: 1px solid #e2e8f0;">
                    <div class="table-cell">Sender Details</div>
                    <div class="table-cell">Subject</div>
                    <div class="table-cell">Status</div>
                    <div class="table-cell">Date</div>
                </div>
                <!-- Rows will be injected here -->
            </div>
        </div>
    </div>
</div>

<!-- Feedback Detail Modal (Premium View) -->
<div id="feedback-detail-modal" class="modal">
    <div class="modal-content">
        <div class="modal-scroll-area">
            <div style="display: flex; flex-direction: column; gap: 1.5rem; position: relative;">
                <button class="modal-close" style="position: absolute; top: -15px; right: -15px; width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; border-radius: 50%; background: #f1f5f9; border: none; cursor: pointer; color: #64748b; z-index: 10;" onclick="closeModal('feedback-detail-modal')">&times;</button>
            
                <div style="display: flex; align-items: center; gap: 1.25rem;">
                    <div style="flex-shrink: 0; width: 48px; height: 48px; background: #fee2e2; border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                        <i class="fa-solid fa-message" style="font-size: 20px; color: #dc2626;"></i>
                    </div>
                    <div>
                        <h2 style="margin: 0; font-size: 1.5rem; font-weight: 800; color: #0f172a;" id="fb-modal-subject">Feedback Subject</h2>
                        <div style="display: flex; align-items: center; gap: 0.5rem; margin-top: 4px;">
                            <span id="fb-modal-status-badge" class="status-badge" style="margin: 0;">NEW</span>
                            <span id="fb-modal-date" style="color: #64748b; font-size: 0.85rem; font-weight: 500;">-</span>
                        </div>
                    </div>
                </div>

                <!-- Sender Info Card -->
                <div style="background: #f0f7ff; border: 1px solid #e2e8f0; border-radius: 16px; padding: 1.5rem; display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div>
                        <span style="display: block; font-size: 0.65rem; font-weight: 800; color: #3b82f6; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 4px;">Sender Name</span>
                        <div id="fb-modal-name" style="font-size: 1.05rem; font-weight: 700; color: #1e293b;">-</div>
                    </div>
                    <div>
                        <span style="display: block; font-size: 0.65rem; font-weight: 800; color: #3b82f6; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 4px;">Contact Email</span>
                        <div id="fb-modal-email" style="font-size: 1.05rem; font-weight: 600; color: #1e293b; word-break: break-all;">-</div>
                    </div>
                </div>

                <!-- Message Content -->
                <div>
                    <span style="display: block; font-size: 0.7rem; font-weight: 800; color: #3b82f6; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 8px;">Message Content</span>
                    <div id="fb-modal-message" style="background: white; border: 1.5px solid #e2e8f0; border-radius: 12px; padding: 1.5rem; font-size: 1rem; color: #1e293b; line-height: 1.6; min-height: 120px; white-space: pre-wrap;">
                        -
                    </div>
                </div>

                <!-- Admin Action Section -->
                <div style="border-top: 1px solid #e2e8f0; padding-top: 1.5rem; display: flex; flex-direction: column; gap: 1rem;">
                    <div id="fb-main-actions" style="display: flex; flex-wrap: wrap; justify-content: space-between; gap: 1rem; align-items: center;">
                        <div style="display: flex; gap: 0.75rem; flex-wrap: wrap;">
                            <button class="btn btn-success" style="padding: 0.75rem 1.25rem; border-radius: 10px; font-weight: 700; display: none;" onclick="updateFeedbackStatus('RESOLVED')" id="fb-btn-resolve">Mark as Resolved</button>
                            <button class="btn btn-secondary" style="padding: 0.75rem 1.25rem; border-radius: 10px; font-weight: 700; display: none;" onclick="updateFeedbackStatus('PENDING')" id="fb-btn-pending">Mark as Pending</button>
                            <button class="btn btn-danger" style="padding: 0.75rem 1.25rem; border-radius: 10px; font-weight: 700;" onclick="showDeleteConfirm()" id="fb-btn-delete">Delete Record</button>
                        </div>
                        <button class="btn btn-primary" onclick="closeModal('feedback-detail-modal')" style="background: #0f172a; padding: 0.75rem 1.5rem; border-radius: 10px; font-weight: 700;">Close View</button>
                    </div>

                    <!-- Inline Delete Confirmation -->
                    <div id="fb-delete-confirm" style="display: none; background: #fff1f2; border: 1.5px solid #fee2e2; border-radius: 12px; padding: 1rem; align-items: center; justify-content: space-between; animation: fadeIn 0.2s ease;">
                        <div style="display: flex; align-items: center; gap: 0.75rem;">
                            <i class="fa-solid fa-triangle-exclamation" style="color: #dc2626; font-size: 1.2rem;"></i>
                            <span style="color: #991b1b; font-weight: 600; font-size: 0.95rem;">Are you absolutely sure? This cannot be undone.</span>
                        </div>
                        <div style="display: flex; gap: 0.5rem;">
                            <button class="btn btn-danger" style="padding: 0.5rem 1rem; border-radius: 8px; font-size: 0.85rem;" onclick="confirmDelete()">Yes, Delete</button>
                            <button class="btn btn-secondary" style="padding: 0.5rem 1rem; border-radius: 8px; font-size: 0.85rem; background: white; color: #64748b;" onclick="cancelDelete()">Cancel</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Specific state for Feedback Management
    let currentFeedbackId = null;

    async function fetchFeedbacks() {
        try {
            const response = await fetch(`${ROOT}/user-admin/getFeedbacks`);
            const data = await response.json();
            if (data.success) {
                appState.feedbacks = data.feedbacks;
                renderFeedbacksTable();
                updateFeedbackBadge();
            }
        } catch (error) {
            console.error('Error fetching feedbacks:', error);
        }
    }

    function renderFeedbacksTable() {
        const tableContent = document.getElementById('feedbacks-table');
        if (!tableContent) return;
        
        const headerRow = tableContent.querySelector('.table-row');
        const searchTerm = document.getElementById('feedback-search').value.toLowerCase();

        tableContent.innerHTML = '';
        if (headerRow) tableContent.appendChild(headerRow);

        // Delegation listener
        if (!tableContent.dataset.hasListener) {
            tableContent.addEventListener('click', (e) => {
                const row = e.target.closest('.clickable-row');
                if (row && row.dataset.fbId) {
                    openFeedbackDetail(row.dataset.fbId);
                }
            });
            tableContent.dataset.hasListener = 'true';
        }

        const filtered = appState.feedbacks.filter(fb => {
            const name = (fb.full_name || '').toLowerCase();
            const email = (fb.email || '').toLowerCase();
            const subject = (fb.subject || '').toLowerCase();
            return name.includes(searchTerm) || email.includes(searchTerm) || subject.includes(searchTerm);
        });

        if (filtered.length === 0) {
            const emptyState = document.createElement('div');
            emptyState.style.padding = '3rem';
            emptyState.style.textAlign = 'center';
            emptyState.style.color = '#64748b';
            emptyState.innerHTML = `
                <i class="fa-solid fa-inbox" style="font-size: 2.5rem; margin-bottom: 1rem; opacity: 0.5;"></i>
                <p style="font-size: 1.1rem; font-weight: 500; margin: 0;">No feedback records found</p>
                <small>Matching your current search criteria</small>
            `;
            tableContent.appendChild(emptyState);
        } else {
            filtered.forEach(fb => {
                const row = document.createElement('div');
                row.className = 'table-row clickable-row';
                row.style.cursor = 'pointer';
                row.dataset.fbId = fb.id;

                const status = (fb.status || 'NEW').toUpperCase();
                let statusColor = '#3b82f6';
                if (status === 'RESOLVED') statusColor = '#10b981';
                if (status === 'ARCHIVED') statusColor = '#64748b';
                if (status === 'PENDING') statusColor = '#f59e0b';

                row.innerHTML = `
                    <div class="table-cell">
                        <strong>${fb.full_name || 'Anonymous'}</strong><br>
                        <small style="color: #64748b;">${fb.email || 'No Email'}</small>
                    </div>
                    <div class="table-cell" style="font-weight: 600;">${fb.subject || 'Inquiry'}</div>
                    <div class="table-cell">
                        <span class="status-badge" style="background: ${statusColor}15; color: ${statusColor}; border: 1px solid ${statusColor}30;">${status}</span>
                    </div>
                    <div class="table-cell">${fb.created_at ? new Date(fb.created_at).toLocaleDateString() : 'Recently'}</div>
                `;
                tableContent.appendChild(row);
            });
        }
    }

    function openFeedbackDetail(id) {
        const feedback = appState.feedbacks.find(f => f.id == id);
        if (!feedback) return;

        currentFeedbackId = id;
        document.getElementById('fb-modal-subject').textContent = feedback.subject || 'No Subject';
        document.getElementById('fb-modal-name').textContent = feedback.full_name || 'Anonymous';
        document.getElementById('fb-modal-email').textContent = feedback.email || 'N/A';
        document.getElementById('fb-modal-message').textContent = feedback.message || 'No content provided.';
        document.getElementById('fb-modal-date').textContent = 'Received on: ' + (feedback.created_at || 'Unknown Date');

        const status = (feedback.status || 'NEW').toUpperCase();
        const badge = document.getElementById('fb-modal-status-badge');
        badge.textContent = status;
        
        let statusColor = '#3b82f6';
        if (status === 'RESOLVED') statusColor = '#10b981';
        if (status === 'ARCHIVED') statusColor = '#64748b';
        if (status === 'PENDING') statusColor = '#f59e0b';
        badge.style.background = statusColor + '15';
        badge.style.color = statusColor;
        badge.style.border = '1px solid ' + statusColor + '30';

        // Toggle Buttons based on status
        const resolveBtn = document.getElementById('fb-btn-resolve');
        const pendingBtn = document.getElementById('fb-btn-pending');
        
        if (status === 'RESOLVED') {
            resolveBtn.style.display = 'none';
            pendingBtn.style.display = 'block';
        } else {
            resolveBtn.style.display = 'block';
            pendingBtn.style.display = 'none';
        }

        openModal('feedback-detail-modal');
        cancelDelete(); // Ensure confirmation is hidden on open
    }

    async function updateFeedbackStatus(newStatus) {
        if (!currentFeedbackId || appState.isProcessingFeedback) return;
        
        appState.isProcessingFeedback = true;
        try {
            const response = await fetch(`${ROOT}/user-admin/updateFeedbackStatus`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ id: currentFeedbackId, status: newStatus })
            });
            const data = await response.json();
            if (data.success) {
                // Update local state
                const idx = appState.feedbacks.findIndex(f => f.id == currentFeedbackId);
                if (idx !== -1) appState.feedbacks[idx].status = newStatus;
                
                renderFeedbacksTable();
                updateFeedbackBadge();
                
                // Update modal badge without closing
                const badge = document.getElementById('fb-modal-status-badge');
                badge.textContent = newStatus;
                let statusColor = '#3b82f6';
                if (newStatus === 'RESOLVED') statusColor = '#10b981';
                if (newStatus === 'ARCHIVED') statusColor = '#64748b';
                if (newStatus === 'PENDING') statusColor = '#f59e0b';
                badge.style.background = statusColor + '15';
                badge.style.color = statusColor;
                badge.style.border = '1px solid ' + statusColor + '30';
                
                // Show brief success toast if available, or just visual feedback
                console.log('Feedback status updated to:', newStatus);

                // Update modal buttons immediately
                const resolveBtn = document.getElementById('fb-btn-resolve');
                const pendingBtn = document.getElementById('fb-btn-pending');
                if (newStatus === 'RESOLVED') {
                    resolveBtn.style.display = 'none';
                    pendingBtn.style.display = 'block';
                } else {
                    resolveBtn.style.display = 'block';
                    pendingBtn.style.display = 'none';
                }
            }
        } catch (error) {
            console.error('Error updating feedback status:', error);
        } finally {
            appState.isProcessingFeedback = false;
        }
    }

    function showDeleteConfirm() {
        document.getElementById('fb-main-actions').style.display = 'none';
        document.getElementById('fb-delete-confirm').style.display = 'flex';
    }

    function cancelDelete() {
        document.getElementById('fb-main-actions').style.display = 'flex';
        document.getElementById('fb-delete-confirm').style.display = 'none';
    }

    async function confirmDelete() {
        if (!currentFeedbackId) return;
        
        try {
            const response = await fetch(`${ROOT}/user-admin/deleteFeedback`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ id: currentFeedbackId })
            });
            const data = await response.json();
            if (data.success) {
                // Update local state
                appState.feedbacks = appState.feedbacks.filter(f => f.id != currentFeedbackId);
                renderFeedbacksTable();
                updateFeedbackBadge();
                closeModal('feedback-detail-modal');
                console.log('Feedback record deleted');
            }
        } catch (error) {
            console.error('Error deleting feedback:', error);
        }
    }

    function updateFeedbackBadge() {
        const badge = document.getElementById('nav-pending-feedbacks-badge');
        if (!badge) return;

        const pendingCount = appState.feedbacks.filter(fb => {
            const s = (fb.status || 'NEW').toUpperCase();
            return s === 'PENDING' || s === 'NEW';
        }).length;

        if (pendingCount > 0) {
            badge.textContent = '+' + pendingCount;
            badge.style.display = 'block';
        } else {
            badge.style.display = 'none';
        }
    }

    // Initialize fetching when navigating to feedbacks
    document.addEventListener('DOMContentLoaded', () => {
        // We override the showContent to pick up feedback fetching
        const originalShowContent = window.showContent;
        window.showContent = function(sectionId) {
            originalShowContent(sectionId);
            if (sectionId === 'feedbacks') {
                fetchFeedbacks();
            }
        };

        // Also fetch once on load to populate the badge
        fetchFeedbacks();
    });
</script>
