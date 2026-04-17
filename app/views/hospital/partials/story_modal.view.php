<div class="modal" id="story-modal">
    <div class="modal-content premium-modal">
        <div class="modal-header premium-modal-header">
            <h3 style="display: flex; align-items: center; gap: 10px; color: #003b6e; font-weight: 700;">
                <i class="fas fa-magic" style="color: #005baa;"></i> Share a New Success Story
            </h3>
            <button class="modal-close" onclick="closeStoryModal()">×</button>
        </div>
        <form id="hospital-story-form" method="POST" action="<?= ROOT ?>/hospital">
            <div class="modal-body premium-modal-body">
                <input type="hidden" name="action" value="add_success_story">
                
                <div class="form-group">
                    <label class="form-label">Story Type <span style="color: #ef4444;">*</span></label>
                    <select name="story_type" id="story-type-select" class="form-input" onchange="handleHospitalStoryTypeChange()" required style="background: #f8fafc; font-weight: 500;">
                        <option value="IMPACT">Impact (Stats-based)</option>
                        <option value="INSPIRATIONAL">Inspirational (Quote/Message)</option>
                        <option value="CASE">Transplant Case (Narrative)</option>
                    </select>
                    <p class="help-text" id="hospital-type-help"><i class="fas fa-info-circle"></i> Showcase combined impact numbers (e.g. 10 transplants saved 10 lives).</p>
                </div>

                <div class="form-group">
                    <label class="form-label">Story Title <span style="color: #ef4444;">*</span></label>
                    <input type="text" name="title" class="form-input" placeholder="e.g. A New Lease on Life" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Message / Description <span style="color: #ef4444;">*</span></label>
                    <textarea name="description" class="form-textarea" rows="4" placeholder="Tell the hospital's success story..." required style="min-height: 100px;"></textarea>
                </div>

                <!-- Dynamic Fields: Inspo -->
                <div id="hospital-author-row" style="display: none;" class="form-grid-2">
                    <div class="form-group">
                        <label class="form-label">Author / Designation</label>
                        <input type="text" name="author_name" class="form-input" placeholder="e.g. Dr. Sunil">
                    </div>
                </div>

                <!-- Dynamic Fields: Stats -->
                <div id="hospital-stats-row" class="form-grid-2">
                    <div class="form-group">
                        <label class="form-label">Donors Involved</label>
                        <input type="number" name="donors_count" class="form-input" value="0">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Lives Impacted</label>
                        <input type="number" name="students_helped" class="form-input" value="0">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Reference Date <span style="color: #ef4444;">*</span></label>
                    <input type="date" name="success_date" class="form-input" value="<?= date('Y-m-d') ?>" required>
                </div>
            </div>

            <div class="modal-footer premium-modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeStoryModal()" style="border-radius: 8px; padding: 10px 20px;">Discard</button>
                <button type="submit" class="btn btn-primary" style="background: #003b6e; border-radius: 8px; padding: 10px 25px; font-weight: 600;">Share Story</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openStoryModal() {
        document.getElementById('story-modal').classList.add('show');
        handleHospitalStoryTypeChange(); // Initialize visibility
    }

    function closeStoryModal() {
        document.getElementById('story-modal').classList.remove('show');
    }

    function handleHospitalStoryTypeChange() {
        const type = document.getElementById('story-type-select').value;
        const authorRow = document.getElementById('hospital-author-row');
        const statsRow = document.getElementById('hospital-stats-row');
        const helpText = document.getElementById('hospital-type-help');

        // Reset
        authorRow.style.display = 'none';
        statsRow.style.display = 'none';

        if (type === 'INSPIRATIONAL') {
            authorRow.style.display = 'grid';
            helpText.innerHTML = '<i class="fas fa-quote-left"></i> Share a message of gratitude from patients or consultants.';
        } else if (type === 'IMPACT') {
            statsRow.style.display = 'grid';
            helpText.innerHTML = '<i class="fas fa-info-circle"></i> Showcase combined impact numbers (e.g. 10 transplants saved 10 lives).';
        } else {
            helpText.innerHTML = '<i class="fas fa-calendar-alt"></i> record a specific transplant success or hospital milestone.';
        }
    }
</script>