<?php
/**
 * Medical School Portal — Versatile Success Stories
 */

$page_title    = 'Success Stories';
$active_page   = 'stories';

ob_start();
?>

<style>
    .story-type-badge {
        padding: 0.25rem 0.75rem;
        border-radius: 50px;
        font-size: 0.8rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }
    .badge--impact { background: #e0f2fe; color: #0369a1; }
    .badge--inspo { background: #fef3c7; color: #92400e; }
    .badge--case { background: #f3e8ff; color: #6b21a8; }
    
    .story-stats-preview {
        display: flex;
        gap: 1rem;
        margin-top: 0.5rem;
        font-size: 0.85rem;
        color: #64748b;
    }
    .story-stats-item {
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }
</style>

<div class="cp-content-header">
    <div class="cp-content-header__content">
        <h1 class="cp-content-header__title"><i class="fas fa-star" style="color: #f59e0b;"></i> Success Stories</h1>
        <p class="cp-content-header__subtitle">Manage Impact, Inspirational, and Case stories for the institutional wall.</p>
    </div>
    <div class="cp-content-header__actions">
        <button class="cp-btn cp-btn--primary" onclick="openAddModal()">
            <i class="fas fa-plus cp-mr-2"></i> Add New Story
        </button>
    </div>
</div>

<div class="cp-content-body">
    <div class="ms-filter-wrapper">
        <div class="ms-filter-group">
            <button class="ms-filter-btn active" onclick="filterStories('ALL', this)">All Stories</button>
            <button class="ms-filter-btn" onclick="filterStories('IMPACT', this)">Impact</button>
            <button class="ms-filter-btn" onclick="filterStories('INSPIRATIONAL', this)">Inspirational</button>
            <button class="ms-filter-btn" onclick="filterStories('CASE', this)">Case/Event</button>
        </div>
    </div>

    <!-- Stories Table -->
    <div class="cp-card">
        <div class="cp-table-wrapper">
            <table class="cp-table" id="stories-table">
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
                    <?php if (empty($stories)): ?>
                        <tr>
                            <td colspan="5" style="text-align: center; padding: 4rem 2rem;">
                                <div class="cp-empty">
                                    <i class="fas fa-quote-left cp-empty__icon"></i>
                                    <h3>No stories found</h3>
                                    <p>Start by adding your first institutional success story.</p>
                                </div>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($stories as $story): ?>
                            <tr class="story-row" data-type="<?= $story->story_type ?>">
                                <td>
                                    <div style="font-weight: 600; color: #1e293b;"><?= htmlspecialchars($story->title) ?></div>
                                    <div style="font-size: 0.85rem; color: #64748b; max-width: 400px;" class="cp-text-truncate">
                                        <?= htmlspecialchars(substr($story->description, 0, 100)) ?>...
                                    </div>
                                    <?php if ($story->story_type !== 'INSPIRATIONAL'): ?>
                                        <div class="story-stats-preview">
                                            <span class="story-stats-item"><i class="fas fa-user-check"></i> <?= $story->donors_count ?> Donors</span>
                                            <span class="story-stats-item"><i class="fas fa-graduation-cap"></i> <?= $story->students_helped ?> Students</span>
                                        </div>
                                    <?php else: ?>
                                        <div style="font-size: 0.8rem; font-style: italic; color: #94a3b8; margin-top: 0.25rem;">
                                            — By <?= htmlspecialchars($story->author_name) ?>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($story->story_type === 'IMPACT'): ?>
                                        <span class="story-type-badge badge--impact"><i class="fas fa-chart-line"></i> Impact</span>
                                    <?php elseif ($story->story_type === 'INSPIRATIONAL'): ?>
                                        <span class="story-type-badge badge--inspo"><i class="fas fa-comment-dots"></i> Inspo</span>
                                    <?php else: ?>
                                        <span class="story-type-badge badge--case"><i class="fas fa-hospital-user"></i> Case</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= date('M d, Y', strtotime($story->success_date)) ?></td>
                                <td>
                                    <?php 
                                        $statusClass = strtolower($story->status) === 'approved' ? 'cp-badge--success' : (strtolower($story->status) === 'rejected' ? 'cp-badge--danger' : 'cp-badge--warning');
                                    ?>
                                    <span class="cp-badge <?= $statusClass ?>"><?= $story->status ?></span>
                                </td>
                                <td style="text-align: right;">
                                    <div class="cp-table-actions">
                                        <a href="javascript:void(0)" class="ms-btn-details" onclick='viewStory(<?= json_encode($story) ?>)'>
                                            <i class="fas fa-eye"></i> Details
                                        </a>
                                        <form method="POST" action="<?= ROOT ?>/medical-school/deleteStory" onsubmit="return confirm('Are you sure you want to delete this story?');" style="display: inline;">
                                            <input type="hidden" name="story_id" value="<?= $story->story_id ?>">
                                            <button type="submit" class="ms-btn-delete" title="Delete">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add Story Modal (Premium Style) -->
<div class="ms-modal-overlay" id="add-story-modal-overlay" onclick="if(event.target == this) closeAddModal()">
    <div class="ms-modal">
        <div class="ms-modal__header">
            <h2 class="ms-modal__title"><i class="fas fa-magic"></i> Share a New Success Story</h2>
            <button class="ms-modal__close" onclick="closeAddModal()">&times;</button>
        </div>
        <form method="POST" action="<?= ROOT ?>/medical-school/createStory">
            <div class="ms-modal__body">
                <div class="cp-form-grid">
                    
                    <div style="grid-column: span 2;">
                        <label class="cp-label">Story Type <span style="color: #ef4444;">*</span></label>
                        <select name="story_type" id="story_type_select" class="cp-input" onchange="handleTypeChange()" required>
                            <option value="IMPACT">Impact (Stats-based)</option>
                            <option value="INSPIRATIONAL">Inspirational (Quote/Message)</option>
                            <option value="CASE">Case / Event (Narrative)</option>
                        </select>
                        <p class="cp-help-text" id="type-help"><i class="fas fa-info-circle"></i> Showcase combined impact numbers (e.g. 50 donors helped 200 students).</p>
                    </div>

                    <div style="grid-column: span 2;">
                        <label class="cp-label">Story Title <span style="color: #ef4444;">*</span></label>
                        <input type="text" name="title" class="cp-input" placeholder="e.g. A Legacy of Learning" required>
                    </div>

                    <div style="grid-column: span 2;">
                        <label class="cp-label">Message / Description <span style="color: #ef4444;">*</span></label>
                        <textarea name="description" class="cp-input cp-textarea" rows="3" placeholder="Tell the institutional success story..." required style="min-height: 80px;"></textarea>
                    </div>

                    <!-- Dynamic Fields: Inspo (Compact Row) -->
                    <div id="author-row" style="display: none; grid-column: span 2; grid-template-columns: 1fr 1fr; gap: 1rem;">
                        <div>
                            <label class="cp-label">Author / Designation</label>
                            <input type="text" name="author_name" class="cp-input" placeholder="e.g. Prof. Aruna">
                        </div>
                        <div>
                            <label class="cp-label">Reference Date <span style="color: #ef4444;">*</span></label>
                            <input type="date" name="success_date_inspo" class="cp-input" value="<?= date('Y-m-d') ?>">
                        </div>
                    </div>

                    <!-- Dynamic Fields: Stats (Row) -->
                    <div id="stats-row" style="grid-column: span 2; display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                        <div>
                            <label class="cp-label">Donors Involved</label>
                            <input type="number" name="donors_count" class="cp-input" value="0">
                        </div>
                        <div>
                            <label class="cp-label">Students Impacted</label>
                            <input type="number" name="students_helped" class="cp-input" value="0">
                        </div>
                    </div>

                    <div id="date-row" style="grid-column: span 2;">
                        <label class="cp-label">Reference Date <span style="color: #ef4444;">*</span></label>
                        <input type="date" name="success_date" class="cp-input" value="<?= date('Y-m-d') ?>" required>
                    </div>

                </div>
            </div>

            <div class="ms-modal__footer">
                <button type="button" class="cp-btn--discard" onclick="closeAddModal()">Discard</button>
                <button type="submit" class="cp-btn--save">Share Story</button>
            </div>
        </form>
    </div>
</div>

<!-- View Story Drawer (Preview) -->
<div class="cp-drawer-overlay" id="view-story-drawer-overlay" onclick="toggleDrawer('view-story-drawer')"></div>
<div class="cp-drawer" id="view-story-drawer">
    <div class="cp-drawer__header">
        <h2 class="cp-drawer__title">Story Preview</h2>
        <button class="cp-drawer__close" onclick="toggleDrawer('view-story-drawer')">&times;</button>
    </div>
    <div class="cp-drawer__body">
        <div id="story-preview-content">
            <!-- Populated by JS -->
        </div>
    </div>
</div>

<script>
    function openAddModal() {
        document.getElementById('add-story-modal-overlay').classList.add('active');
    }

    function closeAddModal() {
        document.getElementById('add-story-modal-overlay').classList.remove('active');
    }

    function toggleDrawer(id) {
        const drawer = document.getElementById(id);
        const overlay = document.getElementById(id + '-overlay') || document.getElementById(id + '-drawer-overlay');
        if (drawer) drawer.classList.toggle('active');
        if (overlay) overlay.classList.toggle('active');
    }

    function handleTypeChange() {
        const type = document.getElementById('story_type_select').value;
        const authorRow = document.getElementById('author-row');
        const statsRow = document.getElementById('stats-row');
        const dateRow = document.getElementById('date-row');
        const helpText = document.getElementById('type-help');

        // Reset visibility
        authorRow.style.display = 'none';
        statsRow.style.display = 'none';
        dateRow.style.display = 'none';

        if (type === 'INSPIRATIONAL') {
            authorRow.style.display = 'grid';
            helpText.innerHTML = '<i class="fas fa-quote-left"></i> Share a message of gratitude from faculty or students.';
        } else if (type === 'IMPACT') {
            statsRow.style.display = 'grid';
            dateRow.style.display = 'block';
            helpText.innerHTML = '<i class="fas fa-info-circle"></i> Showcase combined impact numbers (e.g. 50 donors helped 200 students).';
        } else {
            dateRow.style.display = 'block';
            helpText.innerHTML = '<i class="fas fa-calendar-alt"></i> Record a specific donor event or institutional milestone.';
        }
    }

    function filterStories(type, btn) {
        // Simple UI filter
        const rows = document.querySelectorAll('.story-row');
        const btns = document.querySelectorAll('.ms-filter-btn');
        
        // Update tabs UI
        btns.forEach(b => b.classList.remove('active'));
        if(btn) btn.classList.add('active');

        rows.forEach(row => {
            if (type === 'ALL' || row.dataset.type === type) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }

    function viewStory(story) {
        const container = document.getElementById('story-preview-content');
        let statsHtml = '';
        
        if (story.story_type !== 'INSPIRATIONAL') {
            statsHtml = `
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin: 2rem 0; text-align: center;">
                    <div class="cp-card" style="padding: 1rem; background: #f8fafc; border: 1px solid #e2e8f0;">
                        <i class="fas fa-heart" style="color: #ef4444; font-size: 1.5rem; margin-bottom: 0.5rem;"></i>
                        <div style="font-size: 1.25rem; font-weight: 700; color: #1e293b;">${story.donors_count}</div>
                        <div style="font-size: 0.75rem; color: #64748b; text-transform: uppercase;">Donors</div>
                    </div>
                    <div class="cp-card" style="padding: 1rem; background: #f8fafc; border: 1px solid #e2e8f0;">
                        <i class="fas fa-graduation-cap" style="color: #3b82f6; font-size: 1.5rem; margin-bottom: 0.5rem;"></i>
                        <div style="font-size: 1.25rem; font-weight: 700; color: #1e293b;">${story.students_helped}</div>
                        <div style="font-size: 0.75rem; color: #64748b; text-transform: uppercase;">Students</div>
                    </div>
                </div>
            `;
        }

        const authorHtml = story.story_type === 'INSPIRATIONAL' ? 
            `<div style="margin-top: 2rem; border-top: 1px solid #e2e8f0; padding-top: 1rem; text-align: right;">
                <div style="font-weight: 600; color: #1e293b;">${story.author_name}</div>
                <div style="font-size: 0.85rem; color: #64748b;">Medical Faculty Story</div>
            </div>` : '';

        container.innerHTML = `
            <div style="background: #fff; border-radius: 12px; overflow: hidden;">
                <div style="margin-bottom: 1rem;">
                    <span class="story-type-badge badge--${story.story_type.toLowerCase()}">${story.story_type}</span>
                </div>
                <h2 style="font-size: 1.5rem; font-weight: 700; color: #0f172a; margin-bottom: 1rem;">${story.title}</h2>
                <div style="color: #475569; line-height: 1.7; font-size: 1rem;">
                    ${story.description}
                </div>
                ${statsHtml}
                ${authorHtml}
                <div style="margin-top: 2rem; color: #94a3b8; font-size: 0.85rem;">
                    <i class="far fa-calendar-alt"></i> Recorded on ${new Date(story.success_date).toLocaleDateString()}
                </div>
            </div>
        `;
        toggleDrawer('view-story-drawer');
    }

    // Initialize
    handleTypeChange();
</script>

<?php
$page_content = ob_get_clean();
require dirname(__DIR__) . '/layouts/medical_schools.layout.php';
?>
