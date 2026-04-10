<?php
/**
 * Custodian Portal — Custodians Profile View
 */
$page_icon     = 'fa-users-line';
$page_heading  = 'Custodians Detail';
$page_subtitle = 'Manage your contact details and view co-custodians assigned to this donor.';

ob_start();
?>

<?php include __DIR__ . '/partials/page-header.php'; ?>



<div class="cp-content__body">

    <?php if (!empty($_SESSION['success'])): ?>
        <div class="cp-alert cp-alert--success" style="margin-bottom:1.5rem;">
            <i class="fas fa-check-circle"></i> <?= htmlspecialchars($_SESSION['success']); ?>
            <?php unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($_SESSION['error'])): ?>
        <div class="cp-alert cp-alert--danger" style="margin-bottom:1.5rem;">
            <i class="fas fa-times-circle"></i> <?= htmlspecialchars($_SESSION['error']); ?>
            <?php unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>

    <div class="cp-profile-grid">

    <?php if(!empty($custodians)): ?>
        <?php foreach ($custodians as $c): ?>
            <?php $isMe = ($c->id == $custodian->id); ?>
            <div class="cp-section-card <?= $isMe ? 'cp-card-highlight' : '' ?>">
                <div class="cp-section-card__header">
                    <div class="cp-section-card__title">
                        <i class="fas <?= $isMe ? 'fa-user cp-text-blue-700' : 'fa-user-group' ?>"></i> 
                        Custodian <?= $c->relationship ? '(' . htmlspecialchars($c->relationship) . ')' : '' ?>
                        <?php if($isMe): ?>
                            <span class="cp-badge cp-badge--blue" >You</span>
                        <?php endif; ?>
                    </div>
                    <?php if ($isMe): ?>
                        <button type="button" class="cp-btn cp-btn--sm cp-btn--outline" id="btn-enable-edit">
                            <i class="fas fa-pencil-alt"></i> Edit
                        </button>
                    <?php endif; ?>
                </div>
                <div class="cp-section-card__body">
                    
                    <?php if ($isMe): ?>
                        <!-- Form for the logged in custodian -->
                        <form action="<?= ROOT ?>/custodian/profile" method="POST" id="custodian-profile-form">
                            
                            <div class="cp-profile-form-group">
                                <label class="cp-label">NIC Number</label>
                                <input type="text" class="cp-profile-input" value="<?= htmlspecialchars($c->nic_number ?? '') ?>" disabled />
                            </div>

                            <div class="cp-profile-form-group">
                                <label for="name" class="cp-label">Full Name</label>
                                <input type="text" class="cp-profile-input editable-field" name="name" id="name" value="<?= htmlspecialchars($c->name ?? '') ?>" readonly required />
                            </div>

                            <div class="cp-profile-form-group">
                                <label for="relationship" class="cp-label">Relationship to Donor</label>
                                <input type="text" class="cp-profile-input editable-field" name="relationship" id="relationship" value="<?= htmlspecialchars($c->relationship ?? '') ?>" readonly required />
                            </div>

                            <div class="cp-profile-form-group">
                                <label for="phone" class="cp-label">Phone Number</label>
                                <input type="tel" class="cp-profile-input editable-field" name="phone" id="phone" value="<?= htmlspecialchars($c->phone ?? '') ?>" readonly required />
                            </div>

                            <div class="cp-profile-form-group">
                                <label for="email" class="cp-label">Email</label>
                                <input type="email" class="cp-profile-input editable-field" name="email" id="email" value="<?= htmlspecialchars($c->email ?? '') ?>" readonly />
                            </div>

                            <div class="cp-profile-form-group">
                                <label for="address" class="cp-label">Address</label>
                                <textarea name="address" class="cp-profile-input editable-field" style="min-height:80px; resize:vertical;" id="address" rows="3" readonly><?= htmlspecialchars($c->address ?? '') ?></textarea>
                            </div>

                            <div id="form-actions" style="display: none; justify-content: flex-end; gap: 0.75rem; margin-top: 1rem; border-top: 1px solid var(--g200); padding-top: 1rem;">
                                <button type="button" class="cp-btn cp-btn--ghost" id="btn-cancel-edit">Cancel</button>
                                <button type="submit" class="cp-btn cp-btn--primary">
                                    <i class="fas fa-save"></i> Save Changes
                                </button>
                            </div>

                        </form>
                    <?php else: ?>
                        <!-- Readonly view for other custodians (styled same as forms) -->
                        <div class="cp-profile-form-group">
                            <label class="cp-label">NIC Number</label>
                            <input type="text" class="cp-profile-input" value="<?= htmlspecialchars($c->nic_number ?? '') ?>" disabled />
                        </div>

                        <div class="cp-profile-form-group">
                            <label class="cp-label">Full Name</label>
                            <input type="text" class="cp-profile-input" value="<?= htmlspecialchars($c->name ?? '') ?>" disabled />
                        </div>

                        <div class="cp-profile-form-group">
                            <label class="cp-label">Relationship to Donor</label>
                            <input type="text" class="cp-profile-input" value="<?= htmlspecialchars($c->relationship ?? '') ?>" disabled />
                        </div>

                        <div class="cp-profile-form-group">
                            <label class="cp-label">Phone Number</label>
                            <input type="text" class="cp-profile-input" value="<?= htmlspecialchars($c->phone ?? '') ?>" disabled />
                        </div>

                        <div class="cp-profile-form-group">
                            <label class="cp-label">Email</label>
                            <input type="text" class="cp-profile-input" value="<?= htmlspecialchars($c->email ?? '') ?>" disabled />
                        </div>

                        <div class="cp-profile-form-group">
                            <label class="cp-label">Address</label>
                            <textarea class="cp-profile-input" style="min-height:80px; resize:none;" rows="3" disabled><?= htmlspecialchars($c->address ?? '') ?></textarea>
                        </div>
                    <?php endif; ?>

                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>

    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const editBtn = document.getElementById('btn-enable-edit');
    const cancelBtn = document.getElementById('btn-cancel-edit');
    const formActions = document.getElementById('form-actions');
    const editableFields = document.querySelectorAll('.editable-field');
    const originalValues = {};

    if (editBtn) {
        editBtn.addEventListener('click', function() {
            editableFields.forEach(field => {
                originalValues[field.id] = field.value;
                field.removeAttribute('readonly');
                field.classList.add('editable-active');
            });
            
            editBtn.style.display = 'none';
            formActions.style.display = 'flex';
            
            if (editableFields.length > 0) {
                editableFields[0].focus();
            }
        });
    }

    if (cancelBtn) {
        cancelBtn.addEventListener('click', function() {
            editableFields.forEach(field => {
                field.value = originalValues[field.id];
                field.setAttribute('readonly', 'readonly');
                field.classList.remove('editable-active');
            });
            
            formActions.style.display = 'none';
            editBtn.style.display = 'inline-flex';
        });
    }
});
</script>

<?php 
$page_content = ob_get_clean(); 
require dirname(__DIR__) . '/layouts/custodian.layout.php';
?>
