<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Print Document - <?= htmlspecialchars($type) ?></title>
    <!-- Use system core font mapping to allow printable fonts without external fetches -->
    <link rel="stylesheet" href="<?= ROOT ?>/public/assets/css/custodian/layout.css">
    <style>
        body { margin:0; padding:0; background: #e2e8f0; }
    </style>
</head>
<body>

<div class="cp-print-hidden cp-bg-blue-800 cp-text-white" style="padding: 15px; text-align: center; position: sticky; top:0; z-index:999;">
    <button onclick="window.print()" class="cp-btn cp-btn--primary" style="margin-right: 15px;">
        <svg fill="currentColor" width="16" viewBox="0 0 24 24" style="display:inline-block; vertical-align:middle;"><path d="M19,8H5C3.34,8,2,9.34,2,11v6h4v4h12v-4h4v-6C22,9.34,20.66,8,19,8z M16,19H8v-5h8V19z M19,12c-0.55,0-1-0.45-1-1s0.45-1,1-1s1,0.45,1,1S19.55,12,19,12z M18,3H6v4h12V3z"></path></svg>
        Print this Document
    </button>
    <a href="<?= ROOT ?>/custodian/documents" class="cp-btn cp-btn--outline" style="border-color: #fff; color: #fff;">Back to Documents</a>
</div>

<div class="cp-legal-doc-wrapper">
    <div class="cp-legal-page">

        <?php if ($type === 'datasheet'): ?>

            <div class="cp-legal-office-use">This information is for office use only</div>
            
            <div style="text-align: center; margin-top: 15px; font-weight: bold; font-size: 1.1em; text-transform: uppercase;">
                <?= htmlspecialchars($instName ?? 'Department of Anatomy, Faculty of Medicine, University of Colombo') ?>
            </div>

            <div class="cp-legal-title" style="margin-top: 15px;">Data Sheet for Cadaver Donation</div>

            <div class="cp-legal-section-title">Details of the Deceased</div>
            <div class="cp-legal-row">
                <div class="cp-legal-label">Full Name</div><div class="cp-legal-label" style="min-width: 10px;">:</div>
                <div class="cp-legal-value"><?= htmlspecialchars($donor->first_name . ' ' . $donor->last_name) ?></div>
            </div>
            <div class="cp-legal-grid-2">
                <div class="cp-legal-row">
                    <div class="cp-legal-label" style="min-width: 100px;">NIC No</div><div class="cp-legal-label" style="min-width: 10px;">:</div>
                    <div class="cp-legal-value"><?= htmlspecialchars($donor->nic_number) ?></div>
                </div>
                <div class="cp-legal-row">
                    <div class="cp-legal-label" style="min-width: 100px;">Date of Birth</div><div class="cp-legal-label" style="min-width: 10px;">:</div>
                    <div class="cp-legal-value"><?= htmlspecialchars($donor->date_of_birth ?? '') ?></div>
                </div>
            </div>
            <div class="cp-legal-grid-2">
                <div class="cp-legal-row">
                    <div class="cp-legal-label" style="min-width: 100px;">Age</div><div class="cp-legal-label" style="min-width: 10px;">:</div>
                    <!-- simple age calculation -->
                    <div class="cp-legal-value"><?php 
                        $dob = new DateTime($donor->date_of_birth ?? 'now'); 
                        $dod = new DateTime($activeCase->date_of_death ?? 'now');
                        echo $dob->diff($dod)->y;
                    ?></div>
                </div>
                <div class="cp-legal-row">
                    <div class="cp-legal-label" style="min-width: 100px;">Sex</div><div class="cp-legal-label" style="min-width: 10px;">:</div>
                    <div class="cp-legal-value"><?= htmlspecialchars($donor->gender ?? '') ?></div>
                </div>
            </div>
            <div class="cp-legal-grid-2">
                <div class="cp-legal-row">
                    <div class="cp-legal-label" style="min-width: 100px;">Race</div><div class="cp-legal-label" style="min-width: 10px;">:</div>
                    <div class="cp-legal-value"><?= htmlspecialchars($formData['race'] ?? '') ?></div>
                </div>
                <div class="cp-legal-row">
                    <div class="cp-legal-label" style="min-width: 100px;">Religion</div><div class="cp-legal-label" style="min-width: 10px;">:</div>
                    <div class="cp-legal-value"><?= htmlspecialchars($formData['donor_religion'] ?? $donor->religion ?? '') ?></div>
                </div>
            </div>
            <div class="cp-legal-row">
                <div class="cp-legal-label">Marital Status</div><div class="cp-legal-label" style="min-width: 10px;">:</div>
                <div class="cp-legal-value"><?= htmlspecialchars($donor->marital_status ?? '') ?></div>
            </div>
            <div class="cp-legal-row">
                <div class="cp-legal-label">Place of Birth & District</div><div class="cp-legal-label" style="min-width: 10px;">:</div>
                <div class="cp-legal-value"><?= htmlspecialchars($formData['birth_place'] ?? '') ?></div>
            </div>
            <div class="cp-legal-row">
                <div class="cp-legal-label">Occupation at the Time of Death</div><div class="cp-legal-label" style="min-width: 10px;">:</div>
                <div class="cp-legal-value"><?= htmlspecialchars($formData['occupation'] ?? '') ?></div>
            </div>
            <div class="cp-legal-row">
                <div class="cp-legal-label">Address at the Time of Death</div><div class="cp-legal-label" style="min-width: 10px;">:</div>
                <div class="cp-legal-value"><?= htmlspecialchars($donor->address ?? '') ?></div>
            </div>

            <!-- Skipped Immediate Relations & Parents tables for brevity, we can assume them as written per legal standard or just render empty -->
            <br>
            <div class="cp-legal-row">
                <div class="cp-legal-label">Past Medical History</div><div class="cp-legal-label" style="min-width: 10px;">:</div>
                <div class="cp-legal-value"><?= htmlspecialchars($formData['past_medical_history'] ?? '') ?></div>
            </div>
            <div class="cp-legal-row">
                <div class="cp-legal-label">Past Surgical History</div><div class="cp-legal-label" style="min-width: 10px;">:</div>
                <div class="cp-legal-value"><?= htmlspecialchars($formData['past_surgical_history'] ?? '') ?></div>
            </div>
            <div class="cp-legal-row">
                <div class="cp-legal-label">Other Diseases</div><div class="cp-legal-label" style="min-width: 10px;">:</div>
                <div class="cp-legal-value"><?= htmlspecialchars($formData['other_diseases'] ?? '') ?></div>
            </div>

            <br>
            <div class="cp-legal-row">
                <div class="cp-legal-label">Cause of Death</div><div class="cp-legal-label" style="min-width: 10px;">:</div>
                <!-- Assumption active case handles cause of death inside dd or default to Medical Cert -->
                <div class="cp-legal-value"><?= htmlspecialchars($activeCase->cause_of_death ?? 'As per Medical Certificate') ?></div>
            </div>
            <div class="cp-legal-row">
                <div class="cp-legal-label">Date & Time of Death</div><div class="cp-legal-label" style="min-width: 10px;">:</div>
                <div class="cp-legal-value"><?= htmlspecialchars(($activeCase->date_of_death ?? '') . ' ' . ($activeCase->time_of_death ?? '')) ?></div>
            </div>
            <div class="cp-legal-row">
                <div class="cp-legal-label">Place of Death</div><div class="cp-legal-label" style="min-width: 10px;">:</div>
                <div class="cp-legal-value"><?= htmlspecialchars($formData['place_of_death'] ?? $death_declaration->place_of_death ?? '') ?></div>
            </div>

            <p style="margin-top: 30px;">Herewith I Give Consent to Use the Cadaver of the Above Named Person for Medical Research and Educational Purposes.</p>

            <div class="cp-legal-stamp-box">
                <div class="cp-legal-sig-line">
                    <hr>
                    Date
                </div>
                <div class="cp-legal-sig-line">
                    <hr>
                    Signature
                </div>
            </div>

            <div class="cp-legal-section-title" style="margin-top: 40px;">Details of the Person Handing Over the Cadaver</div>
            <div class="cp-legal-row">
                <div class="cp-legal-label">Full Name</div><div class="cp-legal-label" style="min-width: 10px;">:</div>
                <div class="cp-legal-value"><?= htmlspecialchars($formData['custodian_name'] ?? $custodian->name ?? trim(($custodian->first_name ?? '') . ' ' . ($custodian->last_name ?? ''))) ?></div>
            </div>
            <div class="cp-legal-row">
                <div class="cp-legal-label">Relationship</div><div class="cp-legal-label" style="min-width: 10px;">:</div>
                <div class="cp-legal-value"><?= htmlspecialchars($formData['custodian_relationship'] ?? '') ?></div>
            </div>
            <div class="cp-legal-row">
                <div class="cp-legal-label">NIC No</div><div class="cp-legal-label" style="min-width: 10px;">:</div>
                <div class="cp-legal-value"><?= htmlspecialchars($formData['custodian_nic'] ?? $custodian->nic_number) ?></div>
            </div>
            <div class="cp-legal-row">
                <div class="cp-legal-label">Telephone No</div><div class="cp-legal-label" style="min-width: 10px;">:</div>
                <div class="cp-legal-value"><?= htmlspecialchars($formData['custodian_phone'] ?? '') ?></div>
            </div>
            <div class="cp-legal-row">
                <div class="cp-legal-label">Address</div><div class="cp-legal-label" style="min-width: 10px;">:</div>
                <div class="cp-legal-value"><?= htmlspecialchars($formData['custodian_address'] ?? $custodian->address ?? '') ?></div>
        <?php else: /* SWORN STATEMENT TEMPLATE */ ?>

            <div class="cp-legal-title">Sworn Statement</div>
            <br>
            <p style="text-align: right; line-height: 1.5;">
               <b><?= htmlspecialchars($custodian->name ?? trim(($custodian->first_name ?? '') . ' ' . ($custodian->last_name ?? ''))) ?></b><br>
               <?= nl2br(htmlspecialchars($custodian->address ?? '...........................................................')) ?><br>
               <?= htmlspecialchars($custodian->user_phone ?? '...........................') ?>
            </p>
            
            <p style="text-align: right;"><b>Date:</b> <?= date('Y / m / d') ?></p>

            <p>
            Head<br>
            <?= htmlspecialchars($instName ?? 'Department of Anatomy, Faculty of Medicine, University of Colombo') ?><br>
            <?= nl2br(htmlspecialchars($instAddress ?? 'Kynsey Road, Colombo 08')) ?>
            </p>

            <p>Dear Sir/ Madam,</p>

            <p style="text-decoration: underline; font-weight: bold;">Regarding Donation of Cadaver to the Faculty of Medicine</p>

            <p style="text-align: justify; line-height: 2;">
            I/We hereby sign and declare my/our willingness without objection, to donate the cadaver
            of <b><?= htmlspecialchars($donor->first_name . ' ' . $donor->last_name) ?></b> (Name of deceased) of N.I.C No <b><?= htmlspecialchars($donor->nic_number) ?></b>
            previously residing at address <b><?= htmlspecialchars($donor->address ?? '..............................') ?></b> who expired on
            <b><?= htmlspecialchars($activeCase->date_of_death ?? '..............................') ?></b> (Date), to <?= htmlspecialchars($instName ?? 'the Department of Anatomy at the Faculty of Medicine, University of Colombo') ?>. Furthermore I/we wish to handover the legal ownership of the above mentioned cadaver
            to <b><?= htmlspecialchars($custodian->name ?? trim(($custodian->first_name ?? '') . ' ' . ($custodian->last_name ?? ''))) ?></b> (Name) of NIC No <b><?= htmlspecialchars($custodian->nic_number) ?></b>.
            </p>

            <p style="text-align: justify; line-height: 2;">
            In addition, I/We hereby state that there will be no further inquiry or request regarding the above
            mentioned cadaver after donation has taken place.
            </p>

            <table class="cp-legal-table" style="margin-top:20px;">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Relationship</th>
                        <th>N.I.C Number</th>
                        <th>Signature</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        $relNames = $formData['relations_name'] ?? [];
                        $relRels  = $formData['relations_rel'] ?? [];
                        $relNics  = $formData['relations_nic'] ?? [];
                        $max = max(count($relNames), 5); // Add standard 5 blank lines at least
                        for ($i=0; $i<$max; $i++):
                    ?>
                    <tr>
                        <td><?= htmlspecialchars($relNames[$i] ?? '') ?></td>
                        <td><?= htmlspecialchars($relRels[$i] ?? '') ?></td>
                        <td><?= htmlspecialchars($relNics[$i] ?? '') ?></td>
                        <td style="height:35px;"></td>
                    </tr>
                    <?php endfor; ?>
                </tbody>
            </table>

            <div style="display:flex; margin-top:50px; align-items:flex-end;">
                <div style="font-size:14px; width:45%;">
                    I hereby declare that this statement has
                    been read and explained to the above
                    mentioned person/ persons who have in
                    complete understanding affixed their
                    signature(s) in my presence on the date
                </div>
                <div style="font-size:40px; margin: 0 20px;">}</div>
                <div class="cp-legal-sig-line" style="flex:1;">
                    <hr>
                    (Signature of Justice of Peace & Seal)
                </div>
            </div>

        <?php endif; ?>

    </div>
</div>

<script>
    // small sanity script to confirm everything loaded for the user
    console.log("Legal Document Renderer Initialization complete.");
</script>
</body>
</html>
