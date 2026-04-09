<?php
/**
 * Donor Portal — Family & Custodians Page
 */

// Initials Helper
function getWitnessInitials($fullName) {
    if (!$fullName) return '??';
    $names = explode(' ', trim($fullName));
    if (count($names) >= 2) return strtoupper(substr($names[0], 0, 1) . substr($names[count($names) - 1], 0, 1));
    return strtoupper(substr($fullName, 0, 2));
}

include __DIR__ . '/inc/header.view.php';
include __DIR__ . '/inc/sidebar.view.php';
?>

<main class="d-content">
    <div class="d-content__header">
        <h2><i class="fas fa-users-cog text-accent"></i> Family & Custodians</h2>
        <p>Manage your designated custodians and witnesses who will coordinate your final wishes.</p>
    </div>
    
    <div class="d-content__body">
        
        <!-- SECTION: CUSTODIANS -->
        <div class="d-widget" style="margin-bottom: 2rem;">
            <div class="d-widget__header" style="display: flex; justify-content: space-between; align-items: center;">
                <div class="d-widget__title"><i class="fas fa-shield-alt text-accent"></i> Registered Custodians</div>
                <div class="d-status d-status--success">
                    <?= $custodian_count ?> Registered
                </div>
            </div>
            <div class="d-widget__body">
                <p style="color: var(--g500); font-size: 0.9rem; margin-bottom: 1.5rem;">
                    Your custodians have the legal authority to execute your donation wishes. 
                </p>

                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 1.5rem; margin-bottom: 1.5rem;">
                    <?php if (!empty($custodians)): foreach ($custodians as $c): ?>
                        <div style="border: 1px solid var(--g200); border-radius: var(--r); padding: 1.5rem; background: var(--white); position: relative; transition: all 0.2s ease;" onmouseover="this.style.borderColor='var(--blue-300)'; this.style.boxShadow='0 4px 12px rgba(0,91,170,0.05)';" onmouseout="this.style.borderColor='var(--g200)'; this.style.boxShadow='none';">
                            <?php if ($c->user_status === 'SUSPENDED'): ?>
                                <span class="d-status d-status--danger" style="position: absolute; top: 1rem; right: 1rem; background: rgba(220, 38, 38, 0.1); color: #dc2626; border: 1px solid rgba(220, 38, 38, 0.2);"><i class="fas fa-exclamation-triangle"></i> Suspended</span>
                            <?php elseif ($c->status === 'ACTIVE'): ?>
                                <span class="d-status d-status--success" style="position: absolute; top: 1rem; right: 1rem;"><i class="fas fa-check-circle"></i> Active</span>
                            <?php else: ?>
                                <span class="d-status d-status--warning" style="position: absolute; top: 1rem; right: 1rem;"><i class="fas fa-clock"></i> Pending Approval</span>
                            <?php endif; ?>
                            
                            <div style="display: flex; gap: 1rem; margin-bottom: 1rem;">
                                <div style="width: 50px; height: 50px; border-radius: 50%; background: var(--blue-50); color: var(--blue-600); display: flex; align-items: center; justify-content: center; font-size: 1.25rem;">
                                    <i class="fas fa-user-shield"></i>
                                </div>
                                    <div style="font-weight: 700; color: var(--blue-900); font-size: 1rem;"><?= htmlspecialchars($c->name) ?></div>
                                    <div style="display: flex; flex-direction: column; gap: 0.2rem;">
                                        <div style="font-size: 0.85rem; color: var(--g600);"><?= htmlspecialchars($c->relationship) ?></div>
                                        <?php if ($c->organ_id): ?>
                                            <div style="font-size: 0.75rem; color: #059669; font-weight: 600;"><i class="fas fa-leaf"></i> For <?= htmlspecialchars($c->organ_name) ?></div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>

                            <div style="display: flex; flex-direction: column; gap: 0.4rem; padding-bottom: 1rem; border-bottom: 1px dashed var(--g200); margin-bottom: 1rem;">
                                <div style="font-size: 0.85rem; color: var(--g700);"><i class="fas fa-id-card" style="color: var(--g400); width: 20px;"></i> <?= htmlspecialchars($c->nic_number) ?></div>
                                <div style="font-size: 0.85rem; color: var(--g700);"><i class="fas fa-phone" style="color: var(--g400); width: 20px;"></i> <?= htmlspecialchars($c->phone ?? '—') ?></div>
                                <?php if (!empty($c->email)): ?>
                                    <div style="font-size: 0.85rem; color: var(--g700);"><i class="fas fa-envelope" style="color: var(--g400); width: 20px;"></i> <?= htmlspecialchars($c->email) ?></div>
                                <?php endif; ?>
                                <?php if ($c->user_status === 'SUSPENDED' && !empty($c->review_message)): ?>
                                    <div style="font-size: 0.8rem; color: #dc2626; background: rgba(220, 38, 38, 0.05); padding: 0.5rem; border-radius: 4px; border-left: 2px solid #dc2626; margin-top: 0.5rem;">
                                        <strong>Reason:</strong> <?= htmlspecialchars($c->review_message) ?>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <span class="d-status d-status--success" style="font-size: 0.7rem;"><i class="fas fa-check"></i> Linked Account</span>
                                    <div style="display: flex; gap: 0.5rem;">
                                        <button class="d-btn d-btn--sm d-btn--outline" onclick="openCustodianModal(<?= htmlspecialchars(json_encode($c)) ?>, 'edit')"><i class="fas fa-edit"></i> Edit</button>
                                        <button class="d-btn d-btn--sm d-btn--outline" style="color: var(--danger); border-color: var(--danger);" onclick="removeCustodian(<?= $c->id ?>, '<?= addslashes($c->name) ?>', <?= $custodian_count ?>)"><i class="fas fa-trash"></i></button>
                                    </div>
                            </div>
                        </div>
                    <?php endforeach; else: ?>
                        <div style="grid-column: 1 / -1; padding: 2rem; border: 1px dashed var(--g300); border-radius: var(--r); background: var(--g50); text-align: center;">
                            <i class="fas fa-user-shield" style="font-size: 2.5rem; color: var(--g300); margin-bottom: 1rem;"></i>
                            <h4 style="color: var(--slate); margin-bottom: 0.5rem;">No Custodians Assigned</h4>
                            <p style="color: var(--g500); font-size: 0.9rem;">Assign at least 2 custodians to ensure your wishes can be fulfilled seamlessly.</p>
                    <?php endif; ?>
                </div>

                <button class="d-btn d-btn--primary" onclick="openCustodianModal(null, 'add')"><i class="fas fa-plus-circle"></i> Add Custodian</button>
            </div>
        </div>
            </div>
        </div>

        <!-- SECTION: WITNESSES -->
        <div class="d-widget">
            <div class="d-widget__header" style="display: flex; justify-content: space-between; align-items: center;">
                <div class="d-widget__title"><i class="fas fa-user-pen text-accent"></i> Legal Witnesses</div>
                <div class="d-status <?= $witness_count >= 2 ? 'd-status--success' : 'd-status--warning' ?>">
                    <?= $witness_count ?> Registered
                </div>
            </div>
            <div class="d-widget__body">
                <p style="color: var(--g500); font-size: 0.9rem; margin-bottom: 1.5rem;">
                    Legally registered witnesses who verified your donation consent profile.
                </p>

                <div style="display: grid; grid-template-columns: 1fr; gap: 1rem; margin-bottom: 1rem;">
                    <?php if (!empty($witnesses)): foreach ($witnesses as $index => $w): ?>
                        <div style="border: 1px solid var(--g200); border-radius: var(--r); padding: 1.25rem 1.5rem; background: var(--white); display: flex; justify-content: space-between; align-items: center;">
                            <div style="display: flex; gap: 1.5rem; align-items: center;">
                                <div style="width: 50px; height: 50px; border-radius: 50%; background: var(--g50); color: var(--g500); display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 1.1rem; border: 2px solid var(--white); box-shadow: 0 0 0 1px var(--g200);">
                                    <?= getWitnessInitials($w->name ?? '') ?>
                                </div>
                                <div style="display: flex; gap: 3rem;">
                                    <div style="min-width: 200px;">
                                        <div style="font-weight: 700; color: var(--blue-900); font-size: 1rem; margin-bottom: 0.2rem;"><?= htmlspecialchars($w->name ?? '') ?></div>
                                        <?php if (!empty($w->organ_id)): ?>
                                            <div style="font-size: 0.8rem; color: #059669; font-weight:600;"><i class="fas fa-leaf"></i> Linked to <?= htmlspecialchars($w->organ_name ?? '') ?></div>
                                        <?php else: ?>
                                            <div style="font-size: 0.8rem; color: var(--g500);">Registered Witness #<?= $index+1 ?></div>
                                        <?php endif; ?>
                                    </div>
                                    <div>
                                        <div style="font-size: 0.75rem; color: var(--g400); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.25rem;">NIC Number</div>
                                        <div style="font-size: 0.9rem; color: var(--slate); font-weight: 500;"><?= htmlspecialchars($w->nic_number ?? '') ?></div>
                                    </div>
                                    <div>
                                        <div style="font-size: 0.75rem; color: var(--g400); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.25rem;">Contact</div>
                                        <div style="font-size: 0.9rem; color: var(--slate); font-weight: 500;"><?= htmlspecialchars($w->contact_number ?? '') ?></div>
                                    </div>
                                </div>
                            </div>
                            <div style="color: var(--g300); font-size: 0.85rem; display: flex; align-items: center; gap: 0.5rem; background: var(--g50); padding: 0.5rem 1rem; border-radius: 20px;">
                                <i class="fas fa-lock" style="font-size: 0.75rem;"></i> Verified Record
                            </div>
                        </div>
                    <?php endforeach; else: ?>
                        <div style="padding: 2rem; border: 1px dashed var(--g300); border-radius: var(--r); background: var(--g50); text-align: center;">
                            <i class="fas fa-users" style="font-size: 2.5rem; color: var(--g300); margin-bottom: 1rem;"></i>
                            <h4 style="color: var(--slate); margin-bottom: 0.5rem;">No Witnesses Assigned</h4>
                            <p style="color: var(--g500); font-size: 0.9rem;">Legal witnesses are required for your donation consent to be valid.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

    </div>
        </div>

    </div>
</main>

<!-- Modals -->

<!-- Custodian Modal -->
<div id="custodianModal" class="d-modal">
    <div class="d-modal__body">
        <div class="d-modal__header">
            <h3 class="d-modal__title" id="custodianModalTitle">Add Custodian</h3>
            <button class="d-modal__close" onclick="closeModal('custodianModal')"><i class="fas fa-times"></i></button>
        </div>
        <form id="custodianForm" method="POST" action="<?= ROOT ?>/donor/family-custodians" style="margin-top: 1rem;">
            <input type="hidden" name="action" id="custodianAction" value="add_custodian">
            <input type="hidden" name="custodian_id" id="custodianId">
            
            <div id="suspensionNotice" style="display: none; background: rgba(220, 38, 38, 0.1); color: #dc2626; padding: 1rem; border-radius: var(--r); margin-bottom: 1rem; font-size: 0.9rem;">
                <div style="font-weight: 700; margin-bottom: 0.25rem;"><i class="fas fa-exclamation-circle"></i> This account is currently suspended</div>
                <div id="suspensionReasonText"></div>
                <div style="margin-top: 0.5rem; font-size: 0.8rem; opacity: 0.8;">Saving your changes will clear the suspension and send this record for re-approval.</div>
            </div>
            <div class="form-group">
                <label>Full Name <span style="color:var(--danger)">*</span></label>
                <input type="text" class="form-control" name="name" id="cust_name" required placeholder="Enter full legal name">
            </div>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div class="form-group">
                    <label>Relationship <span style="color:var(--danger)">*</span></label>
                    <input type="text" class="form-control" name="relationship" id="cust_relationship" required placeholder="e.g. Spouse">
                </div>
                <div class="form-group">
                    <label>NIC Number <span style="color:var(--danger)">*</span></label>
                    <input type="text" class="form-control" name="nic" id="cust_nic" required placeholder="Used for login">
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div class="form-group">
                    <label>Contact Number</label>
                    <input type="text" class="form-control" name="contact" id="cust_contact" placeholder="07XXXXXXXX">
                </div>
                <div class="form-group">
                    <label>Email Address</label>
                    <input type="email" class="form-control" name="email" id="cust_email" placeholder="Optional">
                </div>
            </div>

            <div class="form-group">
                <label>Current Address</label>
                <textarea class="form-control" name="address" id="cust_address" rows="3" placeholder="Residential address"></textarea>
            </div>

            <div style="background: rgba(59, 130, 246, 0.1); border-left: 3px solid var(--blue-600); padding: 0.75rem; margin-bottom: 1.5rem; font-size: 0.85rem; color: var(--blue-800); border-radius: 4px;">
                <i class="fas fa-info-circle"></i> <span id="custodianModalInfo">An account will be automatically created or linked for this NIC.</span>
            </div>

            <div style="display: flex; gap: 1rem; justify-content: flex-end;">
                <button type="button" class="d-btn d-btn--outline" onclick="closeModal('custodianModal')">Cancel</button>
                <button type="submit" class="d-btn d-btn--primary">Save Custodian</button>
            </div>
        </form>
    </div>
</div>

<!-- Witness Modal -->
<div id="witnessModal" class="d-modal">
    <div class="d-modal__body">
        <div class="d-modal__header">
            <h3 class="d-modal__title" id="witnessModalTitle">Add Witness</h3>
            <button class="d-modal__close" onclick="closeModal('witnessModal')"><i class="fas fa-times"></i></button>
        </div>
        <form id="witnessForm" method="POST" action="<?= ROOT ?>/donor/family-custodians" style="margin-top: 1rem;">
            <input type="hidden" name="action" id="witnessAction" value="add_witness">
            <input type="hidden" name="witness_id" id="witnessId">
            
            <div class="form-group">
                <label>Full Name <span style="color:var(--danger)">*</span></label>
                <input type="text" class="form-control" name="name" id="witness_name" required placeholder="Enter witness full name">
            </div>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div class="form-group">
                    <label>NIC Number <span style="color:var(--danger)">*</span></label>
                    <input type="text" class="form-control" name="nic" id="witness_nic" required placeholder="e.g. 901234567V">
                </div>
                <div class="form-group">
                    <label>Contact Number <span style="color:var(--danger)">*</span></label>
                    <input type="text" class="form-control" name="contact" id="witness_contact" required placeholder="e.g. 0771234567">
                </div>
            </div>

            <div style="display: flex; gap: 1rem; justify-content: flex-end; margin-top: 1.5rem;">
                <button type="button" class="d-btn d-btn--outline" onclick="closeModal('witnessModal')">Cancel</button>
                <button type="submit" class="d-btn d-btn--primary">Save Witness</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openModal(id) {
        document.getElementById(id).classList.add('active');
    }
    function closeModal(id) {
        document.getElementById(id).classList.remove('active');
    }

    // Witness Functions
    function openAddModal() {
        document.getElementById('witnessModalTitle').textContent = 'Add Witness';
        document.getElementById('witnessAction').value = 'add_witness';
        document.getElementById('witnessForm').reset();
        openModal('witnessModal');
    }
    function openEditModal(w) {
        document.getElementById('witnessModalTitle').textContent = 'Edit Witness';
        document.getElementById('witnessAction').value = 'edit_witness';
        document.getElementById('witnessId').value = w.id || w.witness_id;
        document.getElementById('witness_name').value = w.name;
        document.getElementById('witness_nic').value = w.nic_number;
        document.getElementById('witness_contact').value = w.contact_number;
        openModal('witnessModal');
    }
    function attemptRemoveWitness(id, name, count) {
        if (count <= 2) { 
            alert('Minimum 2 witnesses required.'); 
            return; 
        }
        if (confirm('Remove witness ' + name + '?')) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '<?= ROOT ?>/donor/family-custodians';
            form.innerHTML = `<input type="hidden" name="action" value="remove_witness"><input type="hidden" name="witness_id" value="${id}">`;
            document.body.appendChild(form);
            form.submit();
        }
    }

    // Custodian Functions
    function openCustodianModal(custodian, mode) {
        const nameInput = document.getElementById('cust_name');
        const nicInput = document.getElementById('cust_nic');
        const suspensionNotice = document.getElementById('suspensionNotice');
        const modalInfo = document.getElementById('custodianModalInfo');

        // Reset locking
        nameInput.readOnly = false;
        nicInput.readOnly = false;
        suspensionNotice.style.display = 'none';
        modalInfo.textContent = 'An account will be automatically created or linked for this NIC.';

        if (mode === 'add') {
            document.getElementById('custodianModalTitle').textContent = 'Add Custodian';
            document.getElementById('custodianAction').value = 'add_custodian';
            document.getElementById('custodianForm').reset();
        } else {
            document.getElementById('custodianModalTitle').textContent = 'Edit Custodian';
            document.getElementById('custodianAction').value = 'edit_custodian';
            document.getElementById('custodianId').value = custodian.id;
            nameInput.value = custodian.name || '';
            document.getElementById('cust_relationship').value = custodian.relationship || '';
            nicInput.value = custodian.nic_number || '';
            document.getElementById('cust_contact').value = custodian.phone || '';
            document.getElementById('cust_email').value = custodian.email || '';
            document.getElementById('cust_address').value = custodian.address || '';

            // Locked fields for Active (Link Level Status)
            if (custodian.status === 'ACTIVE') {
                nameInput.readOnly = true;
                nicInput.readOnly = true;
                modalInfo.textContent = 'Fields for "Full Name" and "NIC" are locked for active custodians.';
            }

            // Suspension info
            if (custodian.user_status === 'SUSPENDED') {
                suspensionNotice.style.display = 'block';
                document.getElementById('suspensionReasonText').textContent = custodian.review_message || 'No reason provided.';
            }
        }
        openModal('custodianModal');
    }

    function removeCustodian(id, name, count) {
        if (count <= 2) {
            alert('Cannot remove custodian. You must maintain at least 2 custodians at all times.');
            return;
        }
        if (confirm('Are you sure you want to remove ' + name + ' as custodian? Their login access will remain but they will no longer be linked to this donor.')) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '<?= ROOT ?>/donor/family-custodians';
            form.innerHTML = `<input type="hidden" name="action" value="remove_custodian"><input type="hidden" name="custodian_id" value="${id}">`;
            document.body.appendChild(form);
            form.submit();
        }
    }
</script>

<?php include __DIR__ . '/inc/footer.view.php'; ?>
