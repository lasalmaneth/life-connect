<div id="stories" class="content-section" style="display: none;">
    <div class="stories-banner">
        <div class="stories-banner__content">
            <h2><i class="fas fa-star" style="color: #fbbf24;"></i> Success Stories Management</h2>
            <p>Showcase the impact of your clinical excellence and life-saving transplants.</p>
        </div>
        <div class="stories-banner__actions">
            <button class="btn btn-primary" onclick="openStoryModal()" style="padding: 10px 20px; border-radius: 8px; font-weight: 600; display: flex; align-items: center; gap: 8px; background: white; color: #003b6e;">
                <i class="fas fa-plus"></i> Share New Story
            </button>
        </div>
    </div>

    <div class="stories-filter-wrapper">
        <div class="stories-filter-group">
            <button class="stories-filter-btn active" onclick="filterHospitalStories('ALL', this)">All Stories</button>
            <button class="stories-filter-btn" onclick="filterHospitalStories('IMPACT', this)">Impact</button>
            <button class="stories-filter-btn" onclick="filterHospitalStories('INSPIRATIONAL', this)">Inspirational</button>
            <button class="stories-filter-btn" onclick="filterHospitalStories('CASE', this)">Transplant Cases</button>
        </div>
    </div>

    <div class="stories-card">
        <div class="table-container">
            <table class="stories-table">
                <thead>
                    <tr>
                        <th style="width: 45%;">Story Details</th>
                        <th style="width: 15%;">Type</th>
                        <th style="width: 15%;">Date</th>
                        <th style="width: 15%;">Status</th>
                        <th style="width: 10%; text-align: right;">Actions</th>
                    </tr>
                </thead>
                <tbody id="hospital-stories-body">
                    <?php if (empty($success_stories)): ?>
                        <tr>
                            <td colspan="5" style="text-align: center; padding: 4rem 2rem;">
                                <div style="display: flex; flex-direction: column; align-items: center; gap: 1rem; color: #94a3b8;">
                                    <i class="fas fa-quote-left" style="font-size: 3rem; opacity: 0.3;"></i>
                                    <h3 style="margin: 0; color: #64748b;">No stories shared yet</h3>
                                    <p style="margin: 0;">Start documenting your hospital's success milestones.</p>
                                </div>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($success_stories as $index => $story): ?>
                            <tr class="story-row" data-type="<?= htmlspecialchars($story->story_type ?? 'CASE') ?>" style="animation-delay: <?= $index * 0.05 ?>s">
                                <td>
                                    <div class="story-title-main"><?= htmlspecialchars($story->title) ?></div>
                                    <div class="story-desc-preview cp-text-truncate">
                                        <?= htmlspecialchars(substr($story->description, 0, 100)) ?><?= strlen($story->description) > 100 ? '...' : '' ?>
                                    </div>
                                    <?php if (($story->story_type ?? '') === 'IMPACT'): ?>
                                        <div class="story-stats-preview">
                                            <span class="story-stats-item"><i class="fas fa-user-check"></i> <?= $story->donors_count ?? 0 ?> Donors</span>
                                            <span class="story-stats-item"><i class="fas fa-heartbeat"></i> <?= $story->students_helped ?? 0 ?> Lives Impacted</span>
                                        </div>
                                    <?php elseif (($story->story_type ?? '') === 'INSPIRATIONAL'): ?>
                                        <div style="font-size: 0.8rem; font-style: italic; color: #94a3b8; margin-top: 0.25rem;">
                                            — By <?= htmlspecialchars($story->author_name ?? 'Hospital Staff') ?>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php 
                                        $type = $story->story_type ?? 'CASE';
                                        if ($type === 'IMPACT') echo '<span class="story-type-badge badge--impact"><i class="fas fa-chart-line"></i> Impact</span>';
                                        elseif ($type === 'INSPIRATIONAL') echo '<span class="story-type-badge badge--inspo"><i class="fas fa-message"></i> Inspo</span>';
                                        else echo '<span class="story-type-badge badge--case"><i class="fas fa-hospital"></i> Case</span>';
                                    ?>
                                </td>
                                <td><?= date('M d, Y', strtotime($story->success_date)) ?></td>
                                <td>
                                    <?php 
                                        $status = strtolower($story->status ?? 'pending');
                                        $class = ($status === 'approved') ? 'status-approved' : (($status === 'rejected' || $status === 'cancelled') ? 'status-rejected' : 'status-pending');
                                    ?>
                                    <span class="status-badge <?= $class ?>"><?= $story->status ?? 'Pending' ?></span>
                                </td>
                                <td style="text-align: right;">
                                    <div class="action-btns">
                                        <button class="btn-view-details" onclick='viewHospitalStory(<?= json_encode($story) ?>)'>
                                            <i class="fas fa-eye"></i> Details
                                        </button>
                                        <button class="btn-delete-story" onclick="deleteHospitalStory(<?= $story->story_id ?>)" title="Delete Story">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
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

<script>
    function filterHospitalStories(type, btn) {
        document.querySelectorAll('.stories-filter-btn').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');

        const rows = document.querySelectorAll('.story-row');
        rows.forEach(row => {
            if (type === 'ALL' || row.dataset.type === type) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }

    function viewHospitalStory(story) {
        // Implement a drawer or another modal for full view if needed
        // For now, let's just log it or we could reuse the add modal in read-only mode
        console.log("Viewing story:", story);
        
        // Simple alert as fallback, ideally should be a nice modal
        hcConfirm(`${story.title}\n\n${story.description}\n\nDate: ${story.success_date}`, { title: 'Success Story Details' });
    }

    function deleteHospitalStory(id) {
        if (confirm('Are you sure you want to delete this success story?')) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '<?= ROOT ?>/hospital';
            
            const actionInput = document.createElement('input');
            actionInput.type = 'hidden';
            actionInput.name = 'action';
            actionInput.value = 'delete_success_story';
            form.appendChild(actionInput);
            
            const idInput = document.createElement('input');
            idInput.type = 'hidden';
            idInput.name = 'story_id';
            idInput.value = id;
            form.appendChild(idInput);
            
            document.body.appendChild(form);
            form.submit();
        }
    }

    // Refresh function called when switching to stories section
    function loadStories() {
        // Any data fetching if needed, but currently PHP renders it.
    }
</script>