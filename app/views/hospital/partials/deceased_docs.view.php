<!-- Deceased Documents & Success Stories (Combined View) -->
<div id="deceased-documents" class="content-section" style="display: none;">
    <div class="cp-content-header">
        <div class="cp-content-header__content">
            <h1 class="cp-content-header__title">
                <i class="fas fa-file-medical"></i> Documents & Progress
            </h1>
            <p class="cp-content-header__subtitle">
                Manage mandatory document bundles and record institutional success stories for organ donations.
            </p>
        </div>
    </div>

    <div class="cp-content-body">
        <!-- Sub-navigation for Docs vs Stories -->
        <div class="ms-filter-wrapper" style="margin-bottom: 2rem; border-bottom: 2px solid #e2e8f0; padding-bottom: 0.5rem;">
            <div class="ms-filter-group">
                <button class="ms-filter-btn active" onclick="switchDeceasedSubTab('docs', this)">
                    <i class="fas fa-folder-open cp-mr-2"></i> Document Submissions
                </button>
                <button class="ms-filter-btn" onclick="switchDeceasedSubTab('stories', this)">
                    <i class="fas fa-star cp-mr-2"></i> Success Stories
                </button>
            </div>
        </div>

        <!-- Document Submissions Tab -->
        <div id="deceased-subtab-docs" class="deceased-subtab">
            <div class="cp-table-container">
                <table class="cp-table">
                    <thead>
                        <tr>
                            <th>Organs</th>
                            <th>NIC</th>
                            <th>Last Update</th>
                            <th style="text-align: center;">Document Status</th>
                            <th style="text-align: right;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($deceased_submissions)): ?>
                            <tr>
                                <td colspan="5">
                                    <div class="cp-empty-state">
                                        <i class="fas fa-file-invoice cp-empty-state__icon"></i>
                                        <div class="cp-empty-state__msg">No Active Submissions</div>
                                        <div class="cp-empty-state__sub">When custodians submit document bundles for review, they will appear here.</div>
                                    </div>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($deceased_submissions as $sub): ?>
                                <tr>
                                    <td>
                                        <div style="font-weight: 600; color: #1e293b;"><?= htmlspecialchars($sub->requested_organs ?: 'Organs Pending') ?></div>
                                        <div style="font-size: 0.8rem; color: #64748b;">
                                            <?= htmlspecialchars($sub->first_name . ' ' . $sub->last_name) ?>
                                        </div>
                                    </td>
                                    <td><code class="cp-nic-badge"><?= htmlspecialchars($sub->nic_number) ?></code></td>
                                    <td><?= date('M d, Y', strtotime($sub->document_action_at ?: $sub->created_at)) ?></td>
                                    <td style="text-align: center;">
                                        <?php 
                                            $ds = strtoupper($sub->document_status);
                                            $dsClass = $ds === 'ACCEPTED' ? 'success' : ($ds === 'PENDING_REVIEW' ? 'warning' : 'danger');
                                        ?>
                                        <span class="cp-badge cp-badge--<?= $dsClass ?>">
                                            <?= htmlspecialchars($sub->document_status) ?>
                                        </span>
                                    </td>
                                    <td style="text-align: right;">
                                        <button class="cp-btn cp-btn--secondary cp-btn--sm" onclick="openDeceasedSubmissionDrawer(<?= $sub->cis_id ?>)">
                                            <i class="fas fa-file-signature"></i> Review Bundle
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Success Stories Tab -->
        <div id="deceased-subtab-stories" class="deceased-subtab" style="display: none;">
            <div style="display: flex; justify-content: flex-end; margin-bottom: 1rem;">
                <button class="cp-btn cp-btn--primary" onclick="openAddStoryModal()">
                    <i class="fas fa-plus cp-mr-2"></i> Share New Story
                </button>
            </div>
            <div class="cp-table-container">
                <table class="cp-table" id="deceased-stories-table">
                    <thead>
                        <tr>
                            <th>Story Details</th>
                            <th>Type</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th style="text-align: right;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($deceased_stories)): ?>
                            <tr>
                                <td colspan="5">
                                    <div class="cp-empty-state">
                                        <i class="fas fa-quote-left cp-empty-state__icon"></i>
                                        <div class="cp-empty-state__msg">No stories found</div>
                                        <div class="cp-empty-state__sub">Start by adding your first institutional success story for organ retrieval.</div>
                                    </div>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($deceased_stories as $story): ?>
                                <tr class="deceased-story-row">
                                    <td>
                                        <div style="font-weight: 600; color: #1e293b;"><?= htmlspecialchars($story->title) ?></div>
                                        <div style="font-size: 0.85rem; color: #64748b;" class="cp-text-truncate">
                                            <?= htmlspecialchars(substr($story->description, 0, 70)) ?>...
                                        </div>
                                    </td>
                                    <td>
                                        <span class="cp-badge cp-badge--info"><?= htmlspecialchars($story->story_type) ?></span>
                                    </td>
                                    <td><?= date('M d, Y', strtotime($story->success_date)) ?></td>
                                    <td>
                                        <span class="cp-badge cp-badge--<?= strtolower($story->status) === 'approved' ? 'success' : 'pending' ?>">
                                            <?= htmlspecialchars($story->status) ?>
                                        </span>
                                    </td>
                                    <td style="text-align: right;">
                                        <button class="cp-btn cp-btn--secondary cp-btn--sm" onclick='viewDeceasedStory(<?= json_encode($story) ?>)'>
                                            <i class="fas fa-eye"></i> View
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
function switchDeceasedSubTab(tabId, btn) {
    document.querySelectorAll('.deceased-subtab').forEach(tab => tab.style.display = 'none');
    document.getElementById('deceased-subtab-' + tabId).style.display = 'block';
    
    document.querySelectorAll('#deceased-documents .ms-filter-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
}

function openDeceasedSubmissionDrawer(id) {
    const titleEl = document.getElementById('drawerTitle');
    const bodyEl  = document.getElementById('drawerBody');
    if (!titleEl || !bodyEl) return;

    titleEl.innerText = 'Document Bundle Verification';
    bodyEl.innerHTML  = '<div style="text-align:center; padding:2rem;"><i class="fas fa-circle-notch fa-spin fa-2x"></i></div>';
    
    if (window.CaseDrawer) window.CaseDrawer.open();
    else toggleDrawer('case-details-drawer');

    fetch('<?= ROOT ?>/hospital/deceased-submissions/view?id=' + id)
        .then(r => r.text())
        .then(html => { bodyEl.innerHTML = html; })
        .catch(() => { bodyEl.innerHTML = '<div class="cp-alert cp-alert--danger">Failed to load bundle.</div>'; });
}

function viewDeceasedStory(story) {
    // Reuse the story preview logic if possible or show a simple alert/modal
    alert("Title: " + story.title + "\n\n" + story.description);
}

function openAddStoryModal() {
    // Reuse existing story modal if available or redirect to stories section
    showContent('stories');
    document.querySelector('#stories button.primary-btn')?.click();
}
</script>
