<div id="stories" class="content-section" style="display: none;">
                    <div class="content-header">
                        <h2>Success Stories Management</h2>
                        <p>Add and manage success stories with photos and media uploads.</p>
                    </div>
                    <div class="content-body">
                        <div class="action-section">
                            <h3>Story Actions</h3>
                            <div class="action-buttons">
                                <button class="btn btn-primary" onclick="openStoryModal()">Add Success Story</button>
                            </div>
                        </div>

                        <div class="data-table">
                            <div class="table-header">
                                <h4>Success Stories</h4>
                            </div>
                            <div class="table-content">
                                <div class="table-row" style="font-weight: 600; background: var(--gray-bg-color);">
                                    <div class="table-cell">Story Title</div>
                                    <div class="table-cell">Description</div>
                                    <div class="table-cell">Date</div>
                                    <div class="table-cell">Status</div>
                                    <div class="table-cell">Actions</div>
                                </div>

                                <div class="table-row">
                                    <div class="table-cell name" data-label="Story Title">A Life Saved - Kidney
                                        Transplant Success</div>
                                    <div class="table-cell" data-label="Description">Kidney transplant is successful</div>
                                    <div class="table-cell" data-label="Date">2025-09-15</div>
                                    <div class="table-cell" data-label="Status"><span class="status-badge status-pending">Pending Review</span></div>
                                    <div class="table-cell" data-label="Actions">
                                        <button class="btn btn-secondary btn-small" onclick="editStory()">Edit</button>
                                        <button class="btn btn-danger btn-small" onclick="deleteStory()">Delete</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>