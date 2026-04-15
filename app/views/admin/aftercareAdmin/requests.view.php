<?php if (!empty($requests)): ?>
    <?php foreach ($requests as $request): ?>
        <div class="table-row support-row" 
             style="cursor: pointer; transition: all 0.2s ease; border-bottom: 1px solid #f1f5f9; align-items: center; display: flex; width: 100%;" 
             onclick='openSupportDetails(<?= json_encode($request) ?>)'
             onmouseover="this.style.background='#f8fafc'; this.style.transform='translateY(-1px)'; this.style.boxShadow='0 4px 6px -1px rgba(0,0,0,0.05)';" 
             onmouseout="this.style.background='white'; this.style.transform='none'; this.style.boxShadow='none';">
            
            <div class="table-cell" style="padding: 1.25rem 1.5rem; flex: 2;">
                <div style="font-weight: 600; color: #1e293b;"><?= htmlspecialchars($request->patient_name) ?></div>
                <div style="font-size: 0.75rem; color: #94a3b8; font-weight: 500;"><?= htmlspecialchars($request->patient_nic) ?></div>
            </div>

            <div class="table-cell" style="padding: 1.25rem 1.5rem; flex: 2;">
                <div style="font-weight: 500; color: #334155; line-height: 1.4;">
                    <?= htmlspecialchars($request->reason) ?>
                </div>
            </div>

            <div class="table-cell" style="padding: 1.25rem 1.5rem; flex: 1;">
                <div style="font-weight: 700; color: #0f172a; font-size: 1rem;">
                    LKR <?= number_format($request->amount ?? 0, 2) ?>
                </div>
            </div>

            <div class="table-cell" style="padding: 1.25rem 1.5rem; flex: 1;">
                <div style="color: #64748b; font-size: 0.875rem; font-weight: 500;">
                    <?= date('M d, Y', strtotime($request->submitted_date)) ?>
                </div>
            </div>

            <div class="table-cell" style="padding: 1.25rem 1.5rem; flex: 1.5;">
                <?php 
                    $status = strtoupper($request->status);
                    $colors = [
                        'PENDING'  => ['bg' => '#fef9c3', 'text' => '#854d0e', 'label' => 'Pending Verification'],
                        'VERIFIED' => ['bg' => '#dcfce7', 'text' => '#166534', 'label' => 'Verified (To Finance)'],
                        'APPROVED' => ['bg' => '#dbeafe', 'text' => '#1e40af', 'label' => 'Approved'],
                        'REJECTED' => ['bg' => '#fee2e2', 'text' => '#991b1b', 'label' => 'Rejected']
                    ];
                    $c = $colors[$status] ?? ['bg' => '#f1f5f9', 'text' => '#475569', 'label' => $status];
                ?>
                <span style="display: inline-block; padding: 0.4rem 0.8rem; border-radius: 50px; font-size: 0.75rem; font-weight: 700; background: <?= $c['bg'] ?>; color: <?= $c['text'] ?>; white-space: nowrap;">
                    <?= $c['label'] ?>
                </span>
            </div>
        </div>
    <?php endforeach; ?>
<?php else: ?>
    <div style="padding: 4rem 2rem; text-align: center; background: white; border-radius: 0 0 24px 24px;">
        <i class="fa-solid fa-folder-open" style="font-size: 3rem; color: #e2e8f0; margin-bottom: 1rem; display: block;"></i>
        <div style="color: #94a3b8; font-weight: 500;">No support requests found in the database.</div>
    </div>
<?php endif; ?>
