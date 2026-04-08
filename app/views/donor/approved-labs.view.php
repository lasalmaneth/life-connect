<?php
/**
 * Donor Portal — Approved Labs Page
 * 
 * Expected variables from controller:
 *   $donor_data, $donor_full_name, $donor_id_display, $donor_role
 *   $hospitals, $filteredLabs, $allDistrictsSet, $selectedDistrict, $districts
 *   $active_page, $page_title, $page_css
 */

include __DIR__ . '/inc/header.view.php';
include __DIR__ . '/inc/sidebar.view.php';
?>

<main class="d-content">
    <div class="d-content__header">
        <h2><i class="fas fa-hospital text-accent"></i> Approved Medical Labs</h2>
        <p>Find approved laboratories in your district for medical testing and validation.</p>
    </div>
    
    <div class="d-content__body">
        
        <div class="d-widget">
            <div class="d-widget__header" style="flex-wrap: wrap; gap: 1rem;">
                <div class="d-widget__title"><i class="fas fa-filter text-accent"></i> Filter Hospitals</div>
                <form method="GET" action="" style="display: flex; align-items: center; gap: 1rem;">
                    <label for="district" style="font-size: 0.85rem; font-weight: 600; color: var(--g600);">District:</label>
                    <select id="district" name="district" class="form-control" style="width: auto; padding: 6px 12px; font-weight: 500;" onchange="this.form.submit()">
                        <option value="All" <?php echo ($selectedDistrict === 'All') ? 'selected' : ''; ?>>All Districts</option>
                        <?php foreach ($allDistrictsSet as $districtName): ?> 
                            <option value="<?php echo htmlspecialchars($districtName); ?>" <?php echo ($selectedDistrict === $districtName) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($districtName); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </form>
            </div>
            
            <div class="d-widget__body">
                <div style="margin-bottom: 1.5rem; font-size: 0.9rem; color: var(--g600);">
                    <?php 
                    $totalCount = count($filteredLabs);
                    if ($selectedDistrict === 'All') {
                        echo "Showing all <strong style='color:var(--blue-600);'>$totalCount</strong> approved hospitals and labs.";
                    } else {
                        echo "Showing <strong style='color:var(--blue-600);'>$totalCount</strong> hospital(s) in <strong style='color:var(--slate);'>" . htmlspecialchars($selectedDistrict) . "</strong>.";
                    }
                    ?>
                </div>

                <?php if (count($filteredLabs) > 0): ?>
                    <div class="d-table-wrap">
                        <table class="d-table">
                            <thead>
                                <tr>
                                    <th>Hospital Name</th>
                                    <th>Contact Info</th>
                                    <th>Location / District</th>
                                    <th style="text-align: right;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($filteredLabs as $lab): ?>
                                    <tr>
                                        <td>
                                            <div style="font-weight: 600; color: var(--blue-800); margin-bottom: 0.25rem; font-size: 0.9rem;">
                                                <?php echo htmlspecialchars($lab['name']); ?>
                                            </div>
                                            <div style="font-size: 0.75rem; color: var(--g500);">
                                                Reg: <?php echo htmlspecialchars($lab['registration_no'] ?? 'N/A'); ?> &bull; <span class="d-status d-status--neutral" style="font-size: 0.65rem; padding: 0.1rem 0.4rem;"><?php echo htmlspecialchars($lab['type'] ?? 'General'); ?></span>
                                            </div>
                                        </td>
                                        <td>
                                            <div style="font-size: 0.85rem; color: var(--slate); margin-bottom: 0.25rem;"><i class="fas fa-envelope" style="color: var(--g400); width: 16px;"></i> <?php echo htmlspecialchars($lab['email'] ?? '-'); ?></div>
                                            <div style="font-size: 0.85rem; color: var(--g600);"><i class="fas fa-phone" style="color: var(--g400); width: 16px;"></i> <?php echo htmlspecialchars($lab['contact'] ?? '-'); ?></div>
                                        </td>
                                        <td>
                                            <div style="font-size: 0.85rem; font-weight: 600; color: var(--blue-700); margin-bottom: 0.25rem;"><?php echo htmlspecialchars($lab['district'] ?? $lab['location']); ?></div>
                                            <div style="font-size: 0.75rem; color: var(--g500);"><i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($lab['location'] ?? ''); ?></div>
                                        </td>
                                        <td style="text-align: right;">
                                            <button type="button" class="d-btn d-btn--outline d-btn--sm open-lab" data-lab='<?php echo htmlspecialchars(json_encode($lab), ENT_QUOTES, "UTF-8"); ?>'>
                                                <i class="fas fa-info-circle"></i> Details
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div style="padding: 3rem 1rem; text-align: center; border: 1px dashed var(--g300); border-radius: var(--r); background: var(--g50);">
                        <i class="fas fa-search" style="font-size: 2.5rem; color: var(--g300); margin-bottom: 1rem;"></i>
                        <h4 style="color: var(--slate); margin-bottom: 0.5rem;">No Hospitals Found</h4>
                        <p style="color: var(--g500); font-size: 0.9rem; margin-bottom: 1rem;">
                            <?php 
                            if ($selectedDistrict && $selectedDistrict !== 'All') {
                                echo 'Sorry, no hospitals found in <strong style="color:var(--slate);">' . htmlspecialchars($selectedDistrict) . '</strong> district.';
                            } else {
                                echo 'Sorry, no hospitals found.';
                            }
                            ?>
                        </p>
                        <a href="?district=All" class="d-btn d-btn--primary d-btn--sm"><i class="fas fa-arrow-left"></i> Show All Districts</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>

    </div>
</main>

<!-- Lab Details Modal -->
<div id="labModal" class="d-modal">
    <div class="d-modal__body">
        <div class="d-modal__header">
            <h3 class="d-modal__title" id="modalLabName">Hospital Details</h3>
            <button class="d-modal__close" onclick="closeLabModal()"><i class="fas fa-times"></i></button>
        </div>
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-top: 1rem;">
            <div class="d-info-row">
                <div class="d-info-label">Registration No</div>
                <div class="d-info-value" id="modalRegistration">-</div>
            </div>
            <div class="d-info-row">
                <div class="d-info-label">Verification Status</div>
                <div class="d-info-value">
                    <span class="d-status d-status--success" id="modalVerification">-</span>
                </div>
            </div>
            
            <div class="d-info-row">
                <div class="d-info-label">Email</div>
                <div class="d-info-value" id="modalEmail">-</div>
            </div>
            <div class="d-info-row">
                <div class="d-info-label">Contact Number</div>
                <div class="d-info-value" id="modalContact">-</div>
            </div>
            
            <div class="d-info-row">
                <div class="d-info-label">Location (Address)</div>
                <div class="d-info-value" id="modalLocation">-</div>
            </div>
            <div class="d-info-row">
                <div class="d-info-label">District</div>
                <div class="d-info-value" id="modalDistrict">-</div>
            </div>
            
            <div class="d-info-row" style="grid-column: 1 / -1; background: var(--blue-50); padding: 1rem; border-radius: 8px; border: 1px dashed var(--blue-200);">
                <div style="font-size: 0.85rem; font-weight: 700; color: var(--blue-800); margin-bottom: 0.5rem;"><i class="fas fa-user-md"></i> Chief Medical Officer</div>
                <div style="display: flex; gap: 2rem;">
                    <div>
                        <div class="d-info-label" style="margin-bottom: 0;">Name</div>
                        <div class="d-info-value" id="modalCMO">-</div>
                    </div>
                    <div>
                        <div class="d-info-label" style="margin-bottom: 0;">NIC</div>
                        <div class="d-info-value" id="modalCMONIC">-</div>
                    </div>
                </div>
            </div>
        </div>
        
        <div style="display: flex; gap: 1rem; justify-content: flex-end; margin-top: 1.5rem;">
            <button class="d-btn d-btn--outline" onclick="closeLabModal()">Close</button>
            <button class="d-btn d-btn--primary" onclick="bookTest()"><i class="fas fa-calendar-plus"></i> Book Test</button>
        </div>
    </div>
</div>

<script>
    function openLabModal(lab) {
        try {
            if (typeof lab === 'string') {
                lab = JSON.parse(lab);
            }
        } catch(e) { 
            console.error('Failed to parse lab JSON:', e, lab); 
            return; 
        }

        document.getElementById('modalLabName').textContent = lab.name || 'Hospital Details';
        document.getElementById('modalRegistration').textContent = lab.registration_no || '-';
        document.getElementById('modalEmail').textContent = lab.email || '-';
        document.getElementById('modalContact').textContent = lab.contact || '-';
        document.getElementById('modalLocation').textContent = lab.location || '-';
        document.getElementById('modalDistrict').textContent = lab.district || '-';
        document.getElementById('modalVerification').textContent = lab.verification_status || 'Verified';
        document.getElementById('modalCMO').textContent = lab.cmo_name || '-';
        document.getElementById('modalCMONIC').textContent = lab.cmo_nic || '-';

        document.getElementById('labModal').classList.add('active');
    }

    function closeLabModal() {
        document.getElementById('labModal').classList.remove('active');
    }

    function bookTest() {
        alert('Booking flow will be added later.');
        closeLabModal();
    }

    // Attach click handlers to open modal
    document.addEventListener('DOMContentLoaded', function(){
        document.querySelectorAll('.open-lab').forEach(function(el) {
            el.addEventListener('click', function(e){
                e.preventDefault();
                var raw = this.getAttribute('data-lab');
                if (raw) {
                    try {
                        var labObj = JSON.parse(raw);
                        openLabModal(labObj);
                    } catch (err) {
                        console.error('Failed to parse data-lab JSON on click', err, raw);
                    }
                }
            });
        });
    });
</script>

<?php include __DIR__ . '/inc/footer.view.php'; ?>