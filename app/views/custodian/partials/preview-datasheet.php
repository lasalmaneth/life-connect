<?php
/**
 * Custodian Portal — Cadaver Data Sheet Preview Template
 */
?>
<div class="paper-document datasheet-preview">
    <div class="paper-header text-center">
        <div class="paper-title" style="font-size: 1.2rem; font-weight: 800; border-bottom: 2px solid #000; display: inline-block; padding-bottom: 5px;">DEPARTMENT OF ANATOMY</div>
        <div class="paper-subtitle" style="font-size: 1rem; font-weight: 700; margin-top: 5px;">FACULTY OF MEDICINE, UNIVERSITY OF RUHUNA</div>
        <div class="paper-doc-label" style="margin-top: 15px; font-weight: 600; text-transform: uppercase;">Cadaver Data Sheet</div>
    </div>

    <div class="paper-content mt-4" style="font-size: 0.85rem;">
        <div class="data-section mb-3">
            <h6 style="font-weight: 800; border-bottom: 1px solid #ccc; padding-bottom: 3px;">A. DONOR INFORMATION</h6>
            <div class="grid grid-cols-2 gap-2 mt-2">
                <div><strong>Full Name:</strong> <?= htmlspecialchars($donor->first_name . ' ' . $donor->last_name) ?></div>
                <div><strong>NIC Number:</strong> <?= htmlspecialchars($donor->nic_number) ?></div>
                <div><strong>Occupation:</strong> <span class="p-field" data-f="occupation"><?= htmlspecialchars($formData['occupation'] ?? '................') ?></span></div>
                <div><strong>Race:</strong> <span class="p-field" data-f="race"><?= htmlspecialchars($formData['race'] ?? '................') ?></span></div>
                <div><strong>Religion:</strong> <span class="p-field" data-f="donor_religion"><?= htmlspecialchars($formData['donor_religion'] ?? '................') ?></span></div>
                <div><strong>Place of Birth:</strong> <span class="p-field" data-f="birth_place"><?= htmlspecialchars($formData['birth_place'] ?? '................') ?></span></div>
            </div>
        </div>

        <div class="data-section mb-3">
            <h6 style="font-weight: 800; border-bottom: 1px solid #ccc; padding-bottom: 3px;">B. DEATH DETAILS</h6>
            <div class="grid grid-cols-1 gap-2 mt-2">
                <div><strong>Place of Death:</strong> <span class="p-field" data-f="place_of_death"><?= htmlspecialchars($formData['place_of_death'] ?? '................') ?></span></div>
                <div><strong>Cause of Death:</strong> <?= htmlspecialchars($death_declaration->cause_of_death ?? 'As per medical certificate') ?></div>
                <div><strong>Date of Death:</strong> <?= htmlspecialchars($death_declaration->date_of_death ?? '....-..-..') ?></div>
            </div>
        </div>

        <div class="data-section mb-3">
            <h6 style="font-weight: 800; border-bottom: 1px solid #ccc; padding-bottom: 3px;">C. MEDICAL HISTORY</h6>
            <div class="mt-2">
                <div class="mb-2">
                    <strong>Past Medical History:</strong><br>
                    <div class="p-block" data-f="past_medical_history"><?= nl2br(htmlspecialchars($formData['past_medical_history'] ?? 'No significant medical history reported.')) ?></div>
                </div>
                <div class="mb-2">
                    <strong>Past Surgical History:</strong><br>
                    <div class="p-block" data-f="past_surgical_history"><?= nl2br(htmlspecialchars($formData['past_surgical_history'] ?? 'No significant surgical history reported.')) ?></div>
                </div>
                <div>
                    <strong>Other Known Diseases:</strong><br>
                    <div class="p-block" data-f="other_diseases"><?= nl2br(htmlspecialchars($formData['other_diseases'] ?? 'None.')) ?></div>
                </div>
            </div>
        </div>

        <div class="data-section">
            <h6 style="font-weight: 800; border-bottom: 1px solid #ccc; padding-bottom: 3px;">D. HANDOVER INFORMATION</h6>
            <div class="grid grid-cols-1 gap-1 mt-2">
                <div><strong>Person Handing Over:</strong> <span class="p-field" data-f="custodian_name"><?= htmlspecialchars($formData['custodian_name'] ?? '................') ?></span></div>
                <div><strong>Relationship:</strong> <span class="p-field" data-f="custodian_relationship"><?= htmlspecialchars($formData['custodian_relationship'] ?? '................') ?></span></div>
                <div><strong>NIC Number:</strong> <span class="p-field" data-f="custodian_nic"><?= htmlspecialchars($formData['custodian_nic'] ?? '................') ?></span></div>
            </div>
        </div>

        <div class="paper-footer mt-5 pt-4" style="border-top: 1px dashed #eee;">
            <div class="signature-line" style="border-bottom: 1px solid #000; width: 200px; margin-bottom: 5px;"></div>
            <div style="font-weight: 700;">Head / Anatomy Department</div>
            <div style="font-size: 0.75rem;">Faculty of Medicine</div>
        </div>
    </div>
</div>
