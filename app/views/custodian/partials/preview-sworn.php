<?php
/**
 * Custodian Portal — Sworn Statement Preview Template
 */
?>
<div class="paper-document sworn-preview">
    <div class="paper-header">
        <div class="paper-title">AFIDAVIT / SWORN STATEMENT</div>
        <div class="paper-subtitle">Department of Anatomy, Faculty of Medicine</div>
    </div>

    <div class="paper-content">
        <p class="legal-paragraph">
            I, <span class="p-field"
                data-f="custodian_name"><?= htmlspecialchars($formData['custodian_name'] ?? '.........................................................') ?></span>,
            bearing National Identity Card No. <span class="p-field"
                data-f="custodian_nic"><?= htmlspecialchars($formData['custodian_nic'] ?? '.........................') ?></span>,
            residing at <span class="p-field"
                data-f="custodian_address"><?= htmlspecialchars($formData['custodian_address'] ?? '.........................................................') ?></span>,
            being a <span class="p-field"
                data-f="custodian_relationship"><?= htmlspecialchars($formData['custodian_relationship'] ?? '....................') ?></span>
            to the deceased <strong><?= htmlspecialchars($donor->first_name . ' ' . $donor->last_name) ?></strong> (the
            Donor),
            do hereby solemnly and sincerely declare and affirm as follows:
        </p>

        <p class="legal-paragraph">
            That the following listed individuals are the immediate relations of the deceased, and they have been
            informed of the donation as per the conditions of the LifeConnect program:
        </p>

        <div class="preview-table-wrapper">
            <table class="preview-table">
                <thead>
                    <tr>
                        <th>Full Name</th>
                        <th>Relationship</th>
                        <th>NIC Number</th>
                    </tr>
                </thead>
                <tbody id="p-relations-tbody">
                    <?php
                    $relNames = $formData['relations_name'] ?? [];
                    $relRels = $formData['relations_rel'] ?? [];
                    $relNics = $formData['relations_nic'] ?? [];
                    for ($i = 0; $i < max(1, count($relNames)); $i++):
                        ?>
                        <tr>
                            <td data-f="relations_name[]"><?= htmlspecialchars($relNames[$i] ?? '....................') ?>
                            </td>
                            <td data-f="relations_rel[]"><?= htmlspecialchars($relRels[$i] ?? '....................') ?>
                            </td>
                            <td data-f="relations_nic[]"><?= htmlspecialchars($relNics[$i] ?? '....................') ?>
                            </td>
                        </tr>
                    <?php endfor; ?>
                </tbody>
            </table>
        </div>

        <p class="legal-paragraph mt-4">
            I also declare that the occupation of the deceased at the time of death was
            <span class="p-field"
                data-f="occupation"><?= htmlspecialchars($formData['occupation'] ?? '....................') ?></span>.
        </p>

        <div class="paper-footer mt-5">
            <div class="d-flex justify-between">
                <div class="signature-box">
                    <div class="sig-line"></div>
                    <div class="sig-label">Signature of the Custodian</div>
                </div>
                <div class="date-box">
                    <div>Date: <?= date('Y-m-d') ?></div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
</div>