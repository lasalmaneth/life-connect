<?php
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?php echo ROOT ?? '/life-connect'; ?>/public/assets/css/hospital/hospital.css">
    <title>Success Stories - Hospital Management - LifeConnect</title>
</head>
<body>
    <?php
        $current_page = 'stories';
    
        require_once __DIR__ . '/header.php';
    ?>

    <div class="container">
        <div class="main-content">
            <?php require_once __DIR__ . '/sidebar.php'; ?>

            <div class="content-area">
                <div class="content-section" style="display: block;">
                    <div class="content-header">
                        <h2>Success Stories Management</h2>
                        <p>Share inspiring stories of successful organ transplants and patient recoveries.</p>
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
                                <h4>Recent Success Stories</h4>
                            </div>
                            <div class="table-content" id="stories-table">
                                <div class="table-row" style="font-weight: 600; background: var(--gray-bg-color);">
                                    <div class="table-cell">Story Title</div>
                                    <div class="table-cell">Description</div>
                                    <div class="table-cell">Date</div>
                                    <div class="table-cell">Status</div>
                                    <div class="table-cell">Actions</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
    <div class="modal" id="story-modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Add Success Story</h3>
                <button class="modal-close" onclick="closeStoryModal()">×</button>
            </div>
            <div>
                <div class="form-group">
                    <label class="form-label">Story Title</label>
                    <input type="text" class="form-input" id="story-title" placeholder="Enter an inspiring story title...">
                </div>
                <div class="form-group">
                    <label class="form-label">Story Description</label>
                    <textarea class="form-textarea" id="story-description" placeholder="Share the inspiring details of this success story..."></textarea>
                </div>
                <div class="form-group">
                    <label class="form-label">Date of Success</label>
                    <input type="date" class="form-input" id="success-date">
                </div>
                <button class="btn btn-primary" onclick="saveStory()">Save Story</button>
            </div>
        </div>
    </div>

    <footer style="background: linear-gradient(135deg, #005baa 0%, #003b6e 100%); color: white; text-align: center; padding: 20px; margin-top: 40px; box-shadow: 0 -4px 20px rgba(0, 91, 170, 0.2);">
        <p style="margin: 0; font-size: 14px;">Copyright © 2025 Ministry of Health - LifeConnect Sri Lanka</p>
    </footer>

    <script>
                function openStoryModal() { document.getElementById('story-modal').classList.add('show'); }
        function closeStoryModal() { 
            document.getElementById('story-modal').classList.remove('show');
            document.querySelector('#story-modal .modal-header h3').textContent = 'Add Success Story';
            document.getElementById('story-title').value = '';
            document.getElementById('story-description').value = '';
            document.getElementById('success-date').value = '';
            
            const saveButton = document.querySelector('#story-modal button[onclick*="updateStory"]');
            if (saveButton) {
                saveButton.textContent = 'Save Story';
                saveButton.setAttribute('onclick', 'saveStory()');
            }
        }

        function saveStory() { 
            const title = document.getElementById('story-title').value;
            const description = document.getElementById('story-description').value;
            const date = document.getElementById('success-date').value;
            
            if (!title || !description || !date) {
                showServerMessage('Error - Please fill all required fields', 'error');
                return;
            }
            
            const form = document.createElement('form');
            form.method = 'POST';
            form.style.display = 'none';
            
            const actionInput = document.createElement('input');
            actionInput.type = 'hidden';
            actionInput.name = 'action';
            actionInput.value = 'add_success_story';
            form.appendChild(actionInput);
            
            const titleInput = document.createElement('input');
            titleInput.type = 'hidden';
            titleInput.name = 'title';
            titleInput.value = title;
            form.appendChild(titleInput);
            
            const descriptionInput = document.createElement('input');
            descriptionInput.type = 'hidden';
            descriptionInput.name = 'description';
            descriptionInput.value = description;
            form.appendChild(descriptionInput);
            
            const dateInput = document.createElement('input');
            dateInput.type = 'hidden';
            dateInput.name = 'success_date';
            dateInput.value = date;
            form.appendChild(dateInput);
            
            document.body.appendChild(form);
            form.submit();
        }

        function editStory(storyId) { 
            const stories = <?php echo json_encode($success_stories); ?>;
            const story = stories.find(s => s.story_id == storyId);
            
            if (story) {
                document.querySelector('#story-modal .modal-header h3').textContent = 'Edit Success Story';
                
                document.getElementById('story-title').value = story.title;
                document.getElementById('story-description').value = story.description;
                document.getElementById('success-date').value = story.success_date;
                
                const saveButton = document.querySelector('#story-modal button[onclick="saveStory()"]');
                if (saveButton) {
                    saveButton.textContent = 'Update Story';
                    saveButton.setAttribute('onclick', 'updateStory(' + storyId + ')');
                }
                
                document.getElementById('story-modal').classList.add('show');
            }
        }
        
        function updateStory(storyId) {
            const title = document.getElementById('story-title').value;
            const description = document.getElementById('story-description').value;
            const success_date = document.getElementById('success-date').value;
            
            if (!title || !description || !success_date) {
                showServerMessage('Error - Please fill all required fields', 'error');
                return;
            }
            
            const form = document.createElement('form');
            form.method = 'POST';
            form.style.display = 'none';
            
            const actionInput = document.createElement('input');
            actionInput.type = 'hidden';
            actionInput.name = 'action';
            actionInput.value = 'update_success_story';
            form.appendChild(actionInput);
            
            const storyIdInput = document.createElement('input');
            storyIdInput.type = 'hidden';
            storyIdInput.name = 'story_id';
            storyIdInput.value = storyId;
            form.appendChild(storyIdInput);
            
            const titleInput = document.createElement('input');
            titleInput.type = 'hidden';
            titleInput.name = 'title';
            titleInput.value = title;
            form.appendChild(titleInput);
            
            const descriptionInput = document.createElement('input');
            descriptionInput.type = 'hidden';
            descriptionInput.name = 'description';
            descriptionInput.value = description;
            form.appendChild(descriptionInput);
            
            const dateInput = document.createElement('input');
            dateInput.type = 'hidden';
            dateInput.name = 'success_date';
            dateInput.value = success_date;
            form.appendChild(dateInput);
            
            const statusInput = document.createElement('input');
            statusInput.type = 'hidden';
            statusInput.name = 'status';
            statusInput.value = 'Pending';
            form.appendChild(statusInput);
            
            document.body.appendChild(form);
            form.submit();
        }
        
        function deleteStory(storyId) {
            if (confirm('Are you sure you want to delete this success story?')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.style.display = 'none';
                
                const actionInput = document.createElement('input');
                actionInput.type = 'hidden';
                actionInput.name = 'action';
                actionInput.value = 'delete_success_story';
                form.appendChild(actionInput);
                
                const storyIdInput = document.createElement('input');
                storyIdInput.type = 'hidden';
                storyIdInput.name = 'story_id';
                storyIdInput.value = storyId;
                form.appendChild(storyIdInput);
                
                document.body.appendChild(form);
                form.submit();
            }
        }
        
        function loadStories() {
            const stories = <?php echo json_encode($success_stories); ?>;
            updateStoriesTable(stories);
        }
        
        function updateStoriesTable(stories) {
            const tableContent = document.querySelector('#stories-table');
            if (!tableContent) return;
            
            const existingRows = tableContent.querySelectorAll('.table-row:not(:first-child)');
            existingRows.forEach(row => row.remove());
            
            stories.forEach(story => {
                const row = document.createElement('div');
                row.className = 'table-row';
                
                let statusColor = '#f59e0b';
                if (story.status === 'Approved') {
                    statusColor = '#10b981';
                } else if (story.status === 'Rejected') {
                    statusColor = '#ef4444';
                }
                
                row.innerHTML = `
                    <div class="table-cell name" data-label="Story Title">${story.title}</div>
                    <div class="table-cell" data-label="Description">"${story.description.substring(0, 55)}${story.description.length > 55 ? '...' : ''}"</div>
                    <div class="table-cell" data-label="Date">${new Date(story.success_date).toLocaleDateString('en-GB')}</div>
                    <div class="table-cell" data-label="Status">
                        <span style="background: ${statusColor}; color: white; padding: 0.4rem 0.8rem; border-radius: 20px; font-size: 0.85rem; font-weight: 600; display: inline-flex; align-items: center; gap: 0.4rem;">
                            ${story.status}
                        </span>
                    </div>
                    <div class="table-cell" data-label="Actions">
                        <div style="display: flex; gap: 0.5rem; align-items: center; flex-wrap: nowrap;">
                            <button class="btn btn-secondary btn-small" onclick="editStory(${story.story_id})" style="white-space: nowrap;">Edit</button>
                            <button class="btn btn-danger btn-small" onclick="deleteStory(${story.story_id})" style="white-space: nowrap;">Delete</button>
                        </div>
                    </div>
                `;
                tableContent.appendChild(row);
            });
        }

                document.addEventListener('DOMContentLoaded', function() {
            loadStories();
        });
    </script>

    <?php
        require_once __DIR__ . '/footer.php';
    ?>
</body>
</html>
