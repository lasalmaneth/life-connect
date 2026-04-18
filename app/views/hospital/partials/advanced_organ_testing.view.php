<div id="advanced-testing" class="content-section" style="display: none;">
    <div class="section-header" style="margin-bottom: 25px; display: flex; justify-content: space-between; align-items: center;">
        <div>
            <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 8px;">
                <div style="width: 40px; height: 40px; background: #f0fdf4; border-radius: 12px; display: flex; align-items: center; justify-content: center; color: #10b981;">
                    <i class="fas fa-microscope" style="font-size: 1.25rem;"></i>
                </div>
                <h2 style="font-size: 1.75rem; font-weight: 800; color: #0f172a; margin: 0;">Advanced Organ Testing</h2>
            </div>
            <p style="color: #64748b; font-size: 0.95rem; margin: 0;">Manage compatibility results and final clearance for verified donors.</p>
        </div>
    </div>

    <!-- Main Table Container -->
    <div style="background: white; border-radius: 20px; border: 1px solid #e2e8f0; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05); overflow: hidden;">
        <div style="padding: 20px 25px; border-bottom: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center; background: #f8fafc;">
            <div style="display: flex; align-items: center; gap: 15px;">
                <span style="font-weight: 800; color: #1e293b; font-size: 1rem;">Eligible Donors List</span>
                <span style="background: #10b981; color: white; padding: 2px 10px; border-radius: 999px; font-size: 0.75rem; font-weight: 800;" id="advanced-total-count">0</span>
            </div>
            <div style="position: relative; width: 300px;">
                <i class="fas fa-search" style="position: absolute; left: 15px; top: 50%; transform: translateY(-50%); color: #94a3b8; font-size: 0.9rem;"></i>
                <input type="text" id="advanced-main-search" placeholder="Search by name or NIC..." 
                       style="width: 100%; padding: 10px 15px 10px 45px; border-radius: 12px; border: 1.5px solid #e2e8f0; font-size: 0.9rem; outline: none; transition: all 0.2s;"
                       oninput="populateAdvancedTestingTable()">
            </div>
        </div>

        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background: #f8fafc; border-bottom: 2px solid #f1f5f9;">
                    <th style="padding: 18px 25px; text-align: left; font-size: 0.75rem; font-weight: 800; color: #64748b; text-transform: uppercase; letter-spacing: 0.025em;">Eligible Donor</th>
                    <th style="padding: 18px 25px; text-align: left; font-size: 0.75rem; font-weight: 800; color: #64748b; text-transform: uppercase; letter-spacing: 0.025em;">NIC Number</th>
                    <th style="padding: 18px 25px; text-align: left; font-size: 0.75rem; font-weight: 800; color: #64748b; text-transform: uppercase; letter-spacing: 0.025em;">Pledged Organs</th>
                    <th style="padding: 18px 25px; text-align: left; font-size: 0.75rem; font-weight: 800; color: #64748b; text-transform: uppercase; letter-spacing: 0.025em;">Blood Group</th>
                    <th style="padding: 18px 25px; text-align: right; font-size: 0.75rem; font-weight: 800; color: #64748b; text-transform: uppercase; letter-spacing: 0.025em;">Compatibility</th>
                </tr>
            </thead>
            <tbody id="advanced-testing-body">
                <!-- Populated by JS -->
            </tbody>
        </table>

        <!-- Empty State -->
        <div id="advanced-empty-state" style="padding: 60px; text-align: center; display: none;">
            <div style="width: 60px; height: 60px; background: #f1f5f9; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px; color: #cbd5e1;">
                <i class="fas fa-check-circle" style="font-size: 1.5rem;"></i>
            </div>
            <h3 style="color: #1e293b; font-size: 1.1rem; font-weight: 800; margin-bottom: 8px;">No Approved Donors</h3>
            <p style="color: #64748b; font-size: 0.85rem; max-width: 400px; margin: 0 auto;">Approved donors from the basic screening stage will appear here for advanced testing.</p>
        </div>
    </div>
</div>

<script>
    function populateAdvancedTestingTable() {
        const body = document.getElementById('advanced-testing-body');
        const emptyState = document.getElementById('advanced-empty-state');
        const searchInput = document.getElementById('advanced-main-search');
        const countBadge = document.getElementById('advanced-total-count');
        const q = searchInput ? searchInput.value.toLowerCase().trim() : '';
        
        if (!body) return;
        
        const data = window.eligibleDonorsData || [];
        const filtered = data.filter(d => {
            const name = ((d.first_name || '') + ' ' + (d.last_name || '')).toLowerCase();
            const nic = (d.nic_number || '').toLowerCase();
            if (!q) return true;
            return name.includes(q) || nic.includes(q);
        });

        body.innerHTML = '';
        if (countBadge) countBadge.textContent = filtered.length;
        
        if (filtered.length === 0) {
            if (emptyState) emptyState.style.display = 'block';
            return;
        }
        if (emptyState) emptyState.style.display = 'none';
        
        filtered.forEach(d => {
            const tr = document.createElement('tr');
            tr.style.cssText = "border-bottom: 1px solid #f1f5f9; transition: all 0.2s; cursor: pointer;";
            tr.onmouseover = () => tr.style.background = "#f8fafc";
            tr.onmouseout = () => tr.style.background = "transparent";
            
            tr.innerHTML = `
                <td style="padding: 18px 25px;">
                    <div style="display: flex; align-items: center; gap: 12px;">
                        <div style="width: 38px; height: 38px; background: #f0fdf4; color: #16a34a; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.85rem;">
                            ${String(d.first_name || 'D').charAt(0)}
                        </div>
                        <div>
                            <div style="font-weight: 700; color: #1e293b; font-size: 0.95rem;">${d.first_name} ${d.last_name}</div>
                            <div style="font-size: 0.75rem; color: #16a34a; font-weight: 700; display: flex; align-items: center; gap: 4px;">
                                <i class="fas fa-check-circle"></i> Basic Approved
                            </div>
                        </div>
                    </div>
                </td>
                <td style="padding: 18px 25px;">
                    <code style="background: #f1f5f9; color: #475569; padding: 4px 8px; border-radius: 6px; font-weight: 700; font-size: 0.8rem;">${d.nic_number}</code>
                </td>
                <td style="padding: 18px 25px; font-weight: 700; color: #475569; font-size: 0.9rem;">
                    ${d.organs}
                </td>
                <td style="padding: 18px 25px;">
                    <span style="background: #eff6ff; color: #2563eb; padding: 4px 10px; border-radius: 6px; font-weight: 800; font-size: 0.8rem;">${d.blood_group}</span>
                </td>
                <td style="padding: 18px 25px; text-align: right;">
                    <button class="btn btn-primary" 
                            style="background: #10b981; color: white; border: none; padding: 10px 20px; border-radius: 10px; font-weight: 800; font-size: 0.75rem; cursor: pointer; display: inline-flex; align-items: center; gap: 8px; transition: all 0.2s;"
                            onclick="hcShowSection('matching', null)">
                        <i class="fas fa-dna"></i> Match Recipient
                    </button>
                </td>
            `;
            body.appendChild(tr);
        });
    }

    // Initialize if data exists
    document.addEventListener('DOMContentLoaded', () => {
        if (window.eligibleDonorsData) populateAdvancedTestingTable();
    });
</script>
