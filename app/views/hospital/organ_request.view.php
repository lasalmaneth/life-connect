<div id="organ-requests" class="content-section" style="display: none;">
    <div class="content-header">
        <h2>Organ Requests Management</h2>
        <p>Create, edit, and delete organ requests with urgency selection.</p>
    </div>
    <div class="content-body">
        <div class="action-section">
            <h3>Request Actions</h3>
            <div class="action-buttons">
                <button class="btn btn-primary" onclick="openRequestModal()">Add New Request</button>
            </div>
        </div>

        <!-- Organ Request Options with Emojis -->
        <div class="organ-request-options">
            <h3 style="text-align: center; margin-bottom: 2rem; color: #2c3e50; font-size: 1.5rem;">Organ Request Types</h3>
            <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 1.5rem; margin-top: 1rem;">
                <?php
                    $organsList = $organs ?? [];

                    $iconForOrgan = function($organName) {
                        $n = strtolower(trim((string)$organName));
                        if ($n === 'kidney') {
                            return '<img src="' . ROOT . '/public/assets/icons/kidneys.png" style="width: 48px; height: 48px; object-fit: contain;">';
                        }
                        if ($n === 'bone marrow') {
                            return '<img src="' . ROOT . '/public/assets/icons/bone_marrow.png" style="width: 48px; height: 48px; object-fit: contain;">';
                        }
                        if ($n === 'part of liver') {
                            return '<img src="' . ROOT . '/public/assets/icons/liver.png" style="width: 48px; height: 48px; object-fit: contain;">';
                        }
                        if ($n === 'cornea') {
                            return '<i class="fas fa-eye" style="font-size: 40px; color: #3b82f6;"></i>';
                        }
                        if ($n === 'skin') {
                            return '<i class="fas fa-bandage" style="font-size: 40px; color: #16a34a;"></i>';
                        }
                        if ($n === 'bones') {
                            return '<i class="fas fa-bone" style="font-size: 40px; color: #64748b;"></i>';
                        }
                        if ($n === 'heart valves') {
                            return '<img src="' . ROOT . '/public/assets/icons/heart.png" style="width: 48px; height: 48px; object-fit: contain;">';
                        }
                        if ($n === 'tendons') {
                            return '🦵';
                        }
                        return '';
                    };
                ?>

                <?php foreach ($organsList as $organ): ?>
                    <div class="organ-option-card" onclick='selectOrganType(<?= (int)$organ->id ?>, <?= json_encode($organ->name, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>)'
                        style="cursor: pointer; transition: all 0.3s ease;">
                        <div class="option-emoji" style="margin-bottom: 1rem; display: flex; justify-content: center; background: #f8fafc; padding: 15px; border-radius: 20px;">
                            <?= $iconForOrgan($organ->name) ?>
                        </div>
                        <h4 style="margin: 0.5rem 0; color: #1f2937; font-weight: 600;">
                            <?= htmlspecialchars($organ->name) ?>
                        </h4>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="search-bar">
            <input type="text" id="organ-search" class="search-input" placeholder="Search by organ type or Urgency" onkeyup="applyOrganFilters()">
        </div>

        <div class="filter-section">
            <select id="organ-type-filter" class="filter-select" onchange="applyOrganFilters()">
                <option value="">All Organs</option>
                <?php foreach (($organs ?? []) as $organ): ?>
                    <option value="<?= (int)$organ->id ?>"><?= htmlspecialchars($organ->name) ?></option>
                <?php endforeach; ?>
            </select>
            <select id="urgency-filter" class="filter-select" onchange="applyOrganFilters()">
                <option value="">All Urgency</option>
                <option value="CRITICAL">Critical</option>
                <option value="URGENT">Urgent</option>
                <option value="NORMAL">Normal</option>
            </select>
        </div>

        <div class="data-table">
            <div class="table-header">
                <h4>Organ Requests</h4>
            </div>
            <div class="table-content">
                <div class="table-row" style="font-weight: 600; background: var(--gray-bg-color);">
                    <div class="table-cell">Organ Type</div>
                    <div class="table-cell">Urgency</div>
                    <div class="table-cell">Created Date</div>
                    <div class="table-cell">Status</div>
                    <div class="table-cell">Actions</div>
                </div>
            </div>
        </div>
    </div>
</div>