<div id="consents" class="content-section" style="<?php echo (isset($initialSection) && $initialSection === 'consents') ? 'display:block' : 'display:none'; ?>">
    <div class="content-header" style="background: white; border-bottom: 1px solid #e2e8f0; padding: 25px 30px; border-radius: 16px 16px 0 0;">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div style="display: flex; align-items: center; gap: 20px;">
                <button onclick="hcShowSection('overview', this)" style="background: white; border: 1.5px solid #e2e8f0; width: 42px; height: 42px; border-radius: 12px; color: #64748b; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: all 0.2s; box-shadow: 0 2px 4px rgba(0,0,0,0.02);" onmouseover="this.style.background='#f8fafc'; this.style.color='#1e293b';" onmouseout="this.style.background='white'; this.style.color='#64748b';">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <div style="display: flex; align-items: center; gap: 15px;">
                    <div style="width: 45px; height: 45px; background: #ebf5ff; border-radius: 10px; display: flex; align-items: center; justify-content: center; color: #2563eb;">
                        <i class="fas fa-file-signature" style="font-size: 1.2rem;"></i>
                    </div>
                    <div>
                        <h2 style="margin: 0; font-size: 1.5rem; color: #0f172a; font-weight: 800;">Consent Registry</h2>
                        <p style="margin: 4px 0 0; color: #64748b; font-size: 0.85rem; font-weight: 500;">Verify and maintain organ donation intent records.</p>
                    </div>
                </div>
            </div>
            <div>
                <span style="padding: 8px 16px; background: #f1f5f9; color: #475569; border-radius: 8px; font-weight: 700; font-size: 0.75rem; display: flex; align-items: center; gap: 8px;">
                    <i class="fas fa-database"></i> INSTITUTIONAL REGISTRY
                </span>
            </div>
        </div>
    </div>

    <div class="content-body" style="background: white; padding: 0 30px 30px; border-radius: 0 0 16px 16px; border: 1px solid #e2e8f0; border-top: none;">
        <!-- Premium Filter Bar -->
        <div style="display: flex; justify-content: flex-end; padding: 25px 0;">
            <div class="cp-filter-tabs" style="display: flex; background: #f8fafc; padding: 4px; border-radius: 12px; border: 1px solid #e2e8f0;">
                <button class="filter-btn active" onclick="filterConsents('ALL', this)" style="padding: 10px 20px; border: none; background: #2563eb; color: white; border-radius: 10px; font-weight: 700; font-size: 0.85rem; cursor: pointer; box-shadow: 0 4px 12px rgba(37, 99, 235, 0.2);">All Records</button>
                <button class="filter-btn" onclick="filterConsents('ACTIVE', this)" style="padding: 10px 20px; border: none; background: transparent; color: #64748b; border-radius: 10px; font-weight: 700; font-size: 0.85rem; cursor: pointer;">Active</button>
                <button class="filter-btn" onclick="filterConsents('WITHDRAWN', this)" style="padding: 10px 20px; border: none; background: transparent; color: #64748b; border-radius: 10px; font-weight: 700; font-size: 0.85rem; cursor: pointer;">Withdrawn</button>
            </div>
        </div>

        <div class="data-table-premium" style="width: 100%; overflow-x: auto;">
            <table style="width: 100%; border-collapse: separate; border-spacing: 0;">
                <thead>
                    <tr style="background: #f8fafc;">
                        <th style="padding: 15px; text-align: left; color: #64748b; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em; font-weight: 700; border-bottom: 2px solid #f1f5f9;">Donor Name</th>
                        <th style="padding: 15px; text-align: left; color: #64748b; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em; font-weight: 700; border-bottom: 2px solid #f1f5f9;">Organ</th>
                        <th style="padding: 15px; text-align: left; color: #64748b; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em; font-weight: 700; border-bottom: 2px solid #f1f5f9;">NIC Number</th>
                        <th style="padding: 15px; text-align: left; color: #64748b; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em; font-weight: 700; border-bottom: 2px solid #f1f5f9;">Consent Date</th>
                        <th style="padding: 15px; text-align: left; color: #64748b; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em; font-weight: 700; border-bottom: 2px solid #f1f5f9;">Status</th>
                        <th style="padding: 15px; text-align: right; color: #64748b; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em; font-weight: 700; border-bottom: 2px solid #f1f5f9;">Action</th>
                    </tr>
                </thead>
                <tbody id="consent-table-body">
                    <?php if (!empty($eligibility_pledges)): ?>
                        <?php foreach ($eligibility_pledges as $p): 
                            $status = strtoupper(trim((string)($p->status ?? '')));
                            $withdrawalStatus = strtoupper(trim((string)($p->withdrawal_status ?? '')));

                            // Map internal statuses to Active/Withdrawn if needed
                            $displayStatus = 'ACTIVE';
                            $statusClass = 'status-active-pill';

                            if ($status === 'WITHDRAWN') {
                                $displayStatus = 'WITHDRAWN';
                                $statusClass = 'status-withdrawn-pill';
                            } elseif ($withdrawalStatus === 'PENDING_UPLOAD') {
                                $displayStatus = 'WITHDRAWAL PENDING';
                                $statusClass = 'status-pending-pill'; // Using amber for warning
                            } elseif ($status === 'COMPLETED') {
                                $displayStatus = 'COMPLETED';
                                $statusClass = 'status-active-pill'; // Still counts as a valid history record
                            }
                        ?>
                            <tr class="consent-row" data-status="<?= ($displayStatus === 'WITHDRAWAL PENDING') ? 'WITHDRAWN' : $displayStatus ?>" style="transition: all 0.2s;">
                                <td style="padding: 15px; border-bottom: 1px solid #f1f5f9;">
                                    <div style="display: flex; align-items: center; gap: 12px;">
                                        <div style="width: 38px; height: 38px; background: #f1f5f9; color: #64748b; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 0.9rem;">
                                            <i class="fas fa-user"></i>
                                        </div>
                                        <div>
                                            <div style="font-weight: 700; color: #1e293b; font-size: 0.95rem;"><?= htmlspecialchars(($p->first_name ?? '') . ' ' . ($p->last_name ?? '')) ?></div>
                                            <div style="font-size: 0.75rem; color: #94a3b8; font-weight: 600;">ID #<?= htmlspecialchars($p->user_id ?? $p->donor_id ?? 'N/A') ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td style="padding: 15px; border-bottom: 1px solid #f1f5f9; color: #475569; font-weight: 600; font-size: 0.9rem;"><?= htmlspecialchars($p->organ_name ?? 'N/A') ?></td>
                                <td style="padding: 15px; border-bottom: 1px solid #f1f5f9;">
                                    <code style="background: #f1f5f9; color: #2563eb; padding: 4px 8px; border-radius: 6px; font-weight: 700; font-size: 0.85rem; font-family: inherit;"><?= htmlspecialchars($p->nic_number ?? 'N/A') ?></code>
                                </td>
                                <td style="padding: 15px; border-bottom: 1px solid #f1f5f9;">
                                    <div style="font-weight: 700; color: #1e293b; font-size: 0.85rem;"><?= htmlspecialchars(isset($p->pledge_date) ? date('d/m/Y', strtotime($p->pledge_date)) : 'N/A') ?></div>
                                    <div style="font-size: 0.75rem; color: #94a3b8; font-weight: 600;"><?= htmlspecialchars(isset($p->pledge_date) ? date('H:i', strtotime($p->pledge_date)) : '') ?></div>
                                </td>
                                <td style="padding: 15px; border-bottom: 1px solid #f1f5f9;">
                                    <span class="<?= $statusClass ?>" style="padding: 6px 12px; border-radius: 6px; font-weight: 800; font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.05em; display: inline-block;">
                                        <?= $displayStatus ?>
                                    </span>
                                </td>
                                <td style="padding: 15px; border-bottom: 1px solid #f1f5f9; text-align: right;">
                                    <button class="btn btn-secondary btn-small" style="background: white; border: 1.5px solid #e2e8f0; color: #1e293b; font-weight: 700; border-radius: 8px;" onclick="viewDonorLabData('<?= (int)($p->pledge_id ?? 0) ?>')">
                                        <i class="fas fa-eye" style="margin-right: 5px;"></i> Consent Details
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" style="padding: 40px; text-align: center; color: #94a3b8;">
                                <i class="fas fa-inbox" style="font-size: 2rem; margin-bottom: 10px; display: block; opacity: 0.5;"></i>
                                <div style="font-weight: 600;">No consent records found</div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
    .status-active-pill { background: #dcfce7; color: #166534; }
    .status-withdrawn-pill { background: #fee2e2; color: #991b1b; }
    .status-pending-pill { background: #fef3c7; color: #92400e; }
    
    .consent-row:hover { background: #f8fafc; }
    
    .filter-btn:not(.active):hover {
        background: #f1f5f9 !important;
        color: #1e293b !important;
    }
</style>

<script>
function filterConsents(status, btn) {
    // Update buttons
    document.querySelectorAll('.filter-btn').forEach(b => {
        b.classList.remove('active');
        b.style.background = 'transparent';
        b.style.color = '#64748b';
        b.style.boxShadow = 'none';
    });
    
    btn.classList.add('active');
    btn.style.background = '#2563eb';
    btn.style.color = 'white';
    btn.style.boxShadow = '0 4px 12px rgba(37, 99, 235, 0.2)';
    
    // Filter table
    const rows = document.querySelectorAll('.consent-row');
    rows.forEach(row => {
        if (status === 'ALL' || row.dataset.status === status) {
            row.style.display = 'table-row';
        } else {
            row.style.display = 'none';
        }
    });
}
</script>
