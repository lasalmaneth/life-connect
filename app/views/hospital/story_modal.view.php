<div class="modal" id="story-modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Add Success Story</h3>
                <button class="modal-close" onclick="closeStoryModal()">×</button>
            </div>
            <div>
                <div class="form-group">
                    <label class="form-label">Story Title</label>
                    <input type="text" class="form-input" id="story-title" placeholder="Enter story title">
                </div>
                <div class="form-group">
                    <label class="form-label">Story Description</label>
                    <textarea class="form-textarea" id="story-description"
                        placeholder="Describe the success story..."></textarea>
                </div>
                <div class="form-group">
                    <label class="form-label">Date of Success</label>
                    <input type="date" class="form-input" id="success-date">
                </div>
                <button class="btn btn-primary" onclick="saveStory()">Save Story</button>
            </div>
        </div>
    </div>