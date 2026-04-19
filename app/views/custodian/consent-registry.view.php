<?php
/**
 * Custodian Portal — Refined Consent Registry (Table Overhaul)
 * 
 * Clinical focus: Enforcing strict Brain-Dead vs Post-Mortem tissue track logic.
 */
$page_icon     = 'fa-file-contract';
$page_heading  = 'Clinical Consent Registry';
$page_subtitle = 'Formal legal documentation and clinical priority timeline.';
$extra_css     = ['custodian/registry.css'];

// Group timelines into Active Intents vs Outcomes
$activeIntents = array_filter($consent_registry ?? [], fn($t) => !$t->is_outcome);
$outcomes      = array_filter($consent_registry ?? [], fn($t) => $t->is_outcome);

ob_start();
?>

<?php include __DIR__ . '/partials/page-header.php'; ?>

<div class="cp-content__body cp-registry-container">

    <!-- 1. ACTIVE LEGAL REGISTRY -->
    <section class="cp-timeline-section">
        <div class="cp-timeline-header">
            <div>
                <h2>Active Post-Death Track</h2>
                <p class="cp-text-xs cp-text-g500 mt-1">Legally binding post-death donation intents prioritized by clinical window.</p>
            </div>
            <div class="cp-badge cp-badge--info">Priority Sequential</div>
        </div>

        <div class="cp-registry-table-container">
            <?php if (empty($activeIntents)): ?>
                <div class="p-5 text-center cp-text-g400 italic">No active post-death intents found for this donor.</div>
            <?php else: ?>
                <table class="cp-registry-table">
                    <thead>
                        <tr>
                            <th>Registration Date</th>
                            <th>Legal Intent</th>
                            <th>Category</th>
                            <th>Holding Entity / Organ</th>
                            <th>Status</th>
                            <th class="text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($activeIntents as $t): ?>
                            <?php 
                                $isSuperseded = ($t->status === 'SUPERSEDED');
                                $isWithdrawn  = ($t->status === 'WITHDRAWN');
                                
                                $rowClass = $isSuperseded ? 'opacity-60' : '';
                                if ($isWithdrawn) $rowClass = 'cp-row-withdrawn';
                            ?>
                            <tr class="<?= $rowClass ?>">
                                <td class="cp-text-xs font-weight-bold cp-text-g500">
                                    <?= date('M j, Y', strtotime($t->date)) ?>
                                </td>
                                <td>
                                    <div class="cp-intent-title">
                                        <?php 
                                            $icon = 'fa-hand-holding-medical';
                                            $color = 'cp-text-blue-500';
                                            if ($t->type === 'BODY_CONSENT') $icon = 'fa-university';
                                            if ($t->category === 'Living Donation Case') {
                                                $icon = 'fa-heart';
                                                $color = 'cp-text-rose-500';
                                            }
                                            if ($isWithdrawn) $color = 'cp-text-g400';
                                        ?>
                                        <i class="fas <?= $icon ?> <?= $color ?>"></i>
                                        <?= htmlspecialchars($t->item_name) ?>
                                    </div>
                                </td>
                                <td>
                                    <span class="cp-category-tag"><?= $t->category ?? 'General' ?></span>
                                </td>
                                <td>
                                    <span class="cp-entity-lbl"><?= htmlspecialchars($t->holding_entity ?? 'System Resolution') ?></span>
                                </td>
                                <td>
                                    <?php if ($isWithdrawn): ?>
                                        <span class="cp-badge cp-badge--danger">WITHDRAWN</span>
                                    <?php elseif ($isSuperseded): ?>
                                        <span class="cp-badge cp-badge--secondary">SUPERSEDED</span>
                                    <?php else: ?>
                                        <span class="cp-badge cp-badge--success">ACTIVE</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-right">
                                    <button class="cp-view-details-btn" onclick="openRegistryDrawer('<?= $t->type ?>', '<?= $t->id ?>')">
                                        View Details <i class="fas fa-chevron-right"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </section>

    <!-- 2. ACHIEVEMENT REGISTRY -->
    <section class="cp-timeline-section">
        <div class="cp-timeline-header">
            <div>
                <h2>Donation Achievement Registry</h2>
                <p class="cp-text-xs cp-text-g500 mt-1">Confirmed historical outcomes and living donation successes.</p>
            </div>
        </div>

        <div class="cp-registry-table-container">
            <?php if (empty($outcomes)): ?>
                <div class="p-5 text-center cp-text-g400 italic">No historical donation outcomes recorded.</div>
            <?php else: ?>
                <table class="cp-registry-table">
                    <thead>
                        <tr>
                            <th>Donation Date</th>
                            <th>Achievement</th>
                            <th>Hospital / Point of Care</th>
                            <th>Clinical Notes</th>
                            <th class="text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($outcomes as $o): ?>
                            <tr>
                                <td class="cp-text-xs font-weight-bold cp-text-g500">
                                    <?= date('M j, Y', strtotime($o->date)) ?>
                                </td>
                                <td>
                                    <div class="cp-intent-title">
                                        <i class="fas fa-certificate cp-text-amber-500"></i>
                                        <?= htmlspecialchars($o->item_name) ?>
                                    </div>
                                </td>
                                <td>
                                    <span class="cp-entity-lbl"><?= htmlspecialchars($o->hospital_name ?? 'Specified Facility') ?></span>
                                </td>
                                <td>
                                    <span class="cp-category-tag" style="background:var(--amber-50); color:var(--amber-700);">Donation Success</span>
                                </td>
                                <td class="text-right">
                                    <button class="cp-view-details-btn" onclick="openRegistryDrawer('SUCCESSFUL_DONATION', '<?= $o->id ?>')">
                                        Details <i class="fas fa-search"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </section>

</div>

<!-- Registry Details Drawer -->
<div id="registryDrawer" class="cp-drawer">
    <div class="cp-drawer__overlay" onclick="closeDrawer()"></div>
    <div class="cp-drawer__content cp-drawer__content--right">
        <div class="cp-drawer__header">
            <div>
                <h3 id="drawerTitle" class="cp-drawer__title">Item Details</h3>
                <p id="drawerSubtitle" class="cp-drawer__subtitle">Legal and Clinical Specifications</p>
            </div>
            <button class="cp-drawer__close" onclick="closeDrawer()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="cp-drawer__body" id="drawerBody">
            <!-- Dynamic Content -->
        </div>
    </div>
</div>

<script>
async function openRegistryDrawer(type, id) {
    const drawer = document.getElementById('registryDrawer');
    const body   = document.getElementById('drawerBody');
    const title  = document.getElementById('drawerTitle');
    
    body.innerHTML = `
        <div class="text-center p-5">
            <i class="fas fa-circle-notch fa-spin fa-2x cp-text-g300"></i>
            <p class="cp-text-xs cp-text-g500 mt-3">Accessing clinical vault...</p>
        </div>
    `;
    drawer.classList.add('active');

    try {
        const response = await fetch(`<?= ROOT ?>/api/custodian/get-registry-details?type=${type}&id=${id}`);
        const result = await response.json();
        
        if (result.success) {
            const d = result.data;
            title.innerText = d.item_name || 'Item Details';
            
            const isWithdrawn = (d.status === 'WITHDRAWN');
            const docLabel = isWithdrawn ? 'Download Withdrawal Form' : 'Download Signed Consent Form';
            
            let html = `
                <div class="mb-5">
                    <div class="cp-label-mini mb-3">Legal Document Access</div>
                    <a href="${d.form_path ? '<?= ROOT ?>/' + d.form_path : '#'}" 
                       target="_blank" 
                       class="cp-doc-btn ${d.form_path ? '' : 'disabled'} ${isWithdrawn ? 'cp-doc-btn--withdrawn' : ''}"
                       ${d.form_path ? '' : 'onclick="return false;"'}>
                        <i class="fas ${isWithdrawn ? 'fa-file-excel' : 'fa-file-signature'}"></i> 
                        ${d.form_path ? docLabel : 'Document Not Available'}
                    </a>
                </div>

                <div class="mb-5">
                    <div class="cp-label-mini mb-3">Legal Witnesses</div>
                    <div class="cp-witness-grid">
                        ${d.witnesses && d.witnesses.length > 0 ? d.witnesses.map(w => `
                            <div class="cp-witness-card">
                                <div class="cp-witness-avatar"><i class="fas fa-user-shield"></i></div>
                                <div class="cp-witness-info">
                                    <h5 class="mb-1 font-bold cp-text-g800">${w.name}</h5>
                                    <div class="cp-witness-meta">NIC: ${w.nic_number || 'N/A'}</div>
                                    <div class="cp-witness-meta">Tel: ${w.phone || 'N/A'}</div>
                                    <div class="cp-witness-meta mt-1 pt-1 border-t italic cp-text-g400" style="font-size: 0.65rem;">${w.address || ''}</div>
                                </div>
                            </div>
                        `).join('') : '<div class="cp-text-xs cp-text-g400 italic p-3">No witness details found in this clinical flow.</div>'}
                    </div>
                </div>

                <div class="mb-5">
                    <div class="cp-label-mini mb-2">Clinical Details</div>
                    <div class="bg-gray-50 rounded-lg p-4 border border-gray-100">
                        ${Object.entries(d.dates || {}).map(([lbl, val]) => `
                            <div class="flex justify-between items-center mb-2">
                                <span class="cp-text-xs font-weight-bold cp-text-g500 uppercase">${lbl}</span>
                                <span class="cp-text-sm font-weight-bold cp-text-g800">${val}</span>
                            </div>
                        `).join('')}
                        ${d.description ? `<p class="mt-3 pt-3 border-t cp-text-xs cp-text-g600">${d.description}</p>` : ''}
                    </div>
                </div>
            `;
            body.innerHTML = html;
        } else {
            body.innerHTML = `<div class="p-5 text-center cp-text-red-500">${result.error || 'Failed to load details'}</div>`;
        }
    } catch (e) {
        body.innerHTML = `<div class="p-5 text-center cp-text-red-500">Network error accessing registry vault.</div>`;
    }
}

function closeDrawer() {
    document.getElementById('registryDrawer').classList.remove('active');
}
</script>

<?php
$page_content = ob_get_clean();
require dirname(__DIR__) . '/layouts/custodian.layout.php';
?>
