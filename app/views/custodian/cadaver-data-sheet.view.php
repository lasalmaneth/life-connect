<?php
/**
 * Custodian Portal — Cadaver Data Sheet View
 * Route: GET /custodian/cadaver-data-sheet
 * Active page key: cadaver-data-sheet
 */

$page_icon     = 'fa-file-lines';
$page_heading  = 'Cadaver Data Sheet';
$page_subtitle = 'Fill out the details below. This generates the official data sheet required by the receiving institution.';

ob_start();
?>

<!-- Page Header (won't print) -->
<?php include __DIR__ . '/partials/page-header.php'; ?>



<div class="cp-content__body">

    <!-- Screen UI (Dashboard Inputs) -->
    <div class="cp-form-container">

        <form id="cadaver-entry-form" action="#" method="POST">
            
            <!-- Deceased Details -->
            <div class="cp-section-card">
                <div class="cp-section-card__header">
                    <div class="cp-section-card__title">
                        <i class="fas fa-bed-pulse"></i> Details of the Deceased
                    </div>
                </div>
                <div class="cp-section-card__body">
                    <div class="cp-form-group">
                        <label class="cp-form-label">Full Name</label>
                        <input type="text" class="cp-form-control" name="dec_full_name">
                    </div>
                    <div class="cp-form-row-2">
                        <div class="cp-form-group">
                            <label class="cp-form-label">NIC No</label>
                            <input type="text" class="cp-form-control" name="dec_nic">
                        </div>
                        <div class="cp-form-group">
                            <label class="cp-form-label">Date of Birth</label>
                            <input type="date" class="cp-form-control" name="dec_dob">
                        </div>
                    </div>
                    <div class="cp-form-row-2">
                        <div class="cp-form-group">
                            <label class="cp-form-label">Age</label>
                            <input type="number" class="cp-form-control" name="dec_age">
                        </div>
                        <div class="cp-form-group">
                            <label class="cp-form-label">Sex</label>
                            <select class="cp-form-control" name="dec_sex">
                                <option value="">Select...</option>
                                <option>Male</option>
                                <option>Female</option>
                            </select>
                        </div>
                    </div>
                    <div class="cp-form-row-2">
                        <div class="cp-form-group">
                            <label class="cp-form-label">Race</label>
                            <input type="text" class="cp-form-control" name="dec_race">
                        </div>
                        <div class="cp-form-group">
                            <label class="cp-form-label">Religion</label>
                            <input type="text" class="cp-form-control" name="dec_religion">
                        </div>
                    </div>
                    <div class="cp-form-group">
                        <label class="cp-form-label">Marital Status</label>
                        <input type="text" class="cp-form-control" name="dec_marital">
                    </div>
                    <div class="cp-form-group">
                        <label class="cp-form-label">Place of Birth &amp; District</label>
                        <input type="text" class="cp-form-control" name="dec_birthplace">
                    </div>
                    <div class="cp-form-group">
                        <label class="cp-form-label">Occupation at the Time of Death</label>
                        <input type="text" class="cp-form-control" name="dec_occupation">
                    </div>
                    <div class="cp-form-group">
                        <label class="cp-form-label">Address at the Time of Death</label>
                        <textarea class="cp-form-control" name="dec_address" rows="2"></textarea>
                    </div>
                </div>
            </div>

            <!-- Immediate Relations -->
            <div class="cp-section-card">
                <div class="cp-section-card__header">
                    <div class="cp-section-card__title">
                        <i class="fas fa-users"></i> Details of Immediate Relations
                    </div>
                </div>
                <!-- Clean, Open Body area blending the unstructured table seamlessly -->
                <div class="cp-section-card__body">
                    <div class="cp-helper-text">Add immediate family members (Spouse / Children / Siblings).</div>
                    
                    <div class="cp-table-wrap">
                        <table class="cp-table" id="ui_rel_table">
                            <thead>
                                <tr>
                                    <th style="width: 35%;">Name</th>
                                    <th style="width: 25%;">Relationship</th>
                                    <th style="width: 30%;">NIC No</th>
                                    <th style="width: 10%;"></th>
                                </tr>
                            </thead>
                            <tbody id="rel_table_body">
                                <tr class="rel-row">
                                    <td><input type="text" class="cp-form-control" name="rel_name[]" placeholder="Enter name"></td>
                                    <td>
                                        <select class="cp-form-control" name="rel_rel[]">
                                            <option value="">Select...</option>
                                            <option>Spouse</option>
                                            <option>Child</option>
                                            <option>Sibling</option>
                                            <option>Other</option>
                                        </select>
                                    </td>
                                    <td><input type="text" class="cp-form-control" name="rel_nic[]" placeholder="Enter NIC"></td>
                                    <td style="text-align: center;">
                                        <button type="button" class="btn-remove" onclick="this.closest('tr').remove()" title="Remove row">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <button type="button" class="btn-add-row" onclick="addRelRow()">
                            <i class="fas fa-plus"></i> Add Family Member
                        </button>
                    </div>

                </div>
            </div>

            <!-- Parents (<18) -->
            <div class="cp-section-card">
                <div class="cp-section-card__header">
                    <div class="cp-section-card__title">
                        <i class="fas fa-children"></i> Parents (If less than 18 years old)
                    </div>
                </div>
                <div class="cp-section-card__body">
                    <div class="cp-table-wrap">
                        <table class="cp-table">
                            <thead>
                                <tr>
                                    <th style="width: 15%">Title</th>
                                    <th style="width: 40%">Full Name</th>
                                    <th style="width: 30%">NIC No</th>
                                    <th style="width: 15%">Age</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td style="font-weight: 500; font-size: 0.85rem; color: var(--text-main);">Mother</td>
                                    <td><input type="text" class="cp-form-control" name="mom_name" placeholder="Mother's name"></td>
                                    <td><input type="text" class="cp-form-control" name="mom_nic" placeholder="Enter NIC"></td>
                                    <td><input type="number" class="cp-form-control" name="mom_age" placeholder="Age"></td>
                                </tr>
                                <tr>
                                    <td style="font-weight: 500; font-size: 0.85rem; color: var(--text-main);">Father</td>
                                    <td><input type="text" class="cp-form-control" name="dad_name" placeholder="Father's name"></td>
                                    <td><input type="text" class="cp-form-control" name="dad_nic" placeholder="Enter NIC"></td>
                                    <td><input type="number" class="cp-form-control" name="dad_age" placeholder="Age"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Medical Details -->
            <div class="cp-section-card">
                <div class="cp-section-card__header">
                    <div class="cp-section-card__title">
                        <i class="fas fa-stethoscope"></i> Medical &amp; Death Details
                    </div>
                </div>
                <div class="cp-section-card__body">
                    <div class="cp-form-group">
                        <label class="cp-form-label">Past Medical History</label>
                        <textarea class="cp-form-control" name="med_history" rows="2"></textarea>
                    </div>
                    <div class="cp-form-group">
                        <label class="cp-form-label">Past Surgical History</label>
                        <textarea class="cp-form-control" name="surg_history" rows="2"></textarea>
                    </div>
                    <div class="cp-form-group">
                        <label class="cp-form-label">Other Diseases</label>
                        <textarea class="cp-form-control" name="other_diseases" rows="2"></textarea>
                    </div>
                    
                    <hr style="margin: 2rem 0; border: none; border-bottom: 1px solid var(--border-light);">
                    
                    <div class="cp-form-row-2">
                        <div class="cp-form-group">
                            <label class="cp-form-label">Cause of Death</label>
                            <input type="text" class="cp-form-control" name="cause_of_death">
                        </div>
                        <div class="cp-form-group">
                            <label class="cp-form-label">Date &amp; Time of Death</label>
                            <input type="text" class="cp-form-control" name="time_of_death" placeholder="e.g. YYYY-MM-DD HH:MM">
                        </div>
                    </div>
                    <div class="cp-form-group">
                        <label class="cp-form-label">Place of Death</label>
                        <input type="text" class="cp-form-control" name="place_of_death">
                    </div>
                </div>
            </div>

            <!-- Handover Details -->
            <div class="cp-section-card">
                <div class="cp-section-card__header">
                    <div class="cp-section-card__title">
                        <i class="fas fa-handshake"></i> Details of the Person Handing Over the Cadaver
                    </div>
                </div>
                <div class="cp-section-card__body">
                    <div class="cp-form-row-2">
                        <div class="cp-form-group">
                            <label class="cp-form-label">Full Name</label>
                            <input type="text" class="cp-form-control" name="ho_name">
                        </div>
                        <div class="cp-form-group">
                            <label class="cp-form-label">Relationship to Deceased</label>
                            <input type="text" class="cp-form-control" name="ho_rel">
                        </div>
                    </div>
                    <div class="cp-form-row-2">
                        <div class="cp-form-group">
                            <label class="cp-form-label">NIC No</label>
                            <input type="text" class="cp-form-control" name="ho_nic">
                        </div>
                        <div class="cp-form-group">
                            <label class="cp-form-label">Telephone No</label>
                            <input type="text" class="cp-form-control" name="ho_phone">
                        </div>
                    </div>
                    <div class="cp-form-group">
                        <label class="cp-form-label">Address</label>
                        <textarea class="cp-form-control" name="ho_address" rows="2"></textarea>
                    </div>
                    <div class="cp-form-group">
                        <label class="cp-form-label">Occupation</label>
                        <input type="text" class="cp-form-control" name="ho_occ">
                    </div>
                </div>
            </div>

            <!-- Enhanced Action Bar -->
            <div class="cp-enhanced-action-bar">
                <div class="cp-action-group">
                    <button type="button" class="btn-ghost" disabled>
                        <i class="fas fa-floppy-disk"></i> Save Draft
                    </button>
                    <button type="button" class="btn-accent-outline" onclick="triggerPrint()">
                        <i class="fas fa-file-pdf"></i> Preview PDF Output
                    </button>
                </div>
                <div class="cp-action-group">
                    <button type="button" class="btn-accent" disabled>
                        <i class="fas fa-paper-plane"></i> Submit Form
                    </button>
                </div>
            </div>

        </form>
    </div> <!-- /.cp-form-container -->

    <!-- PDF TEMPLATE (Hidden from screen) -->
    <div class="cp-paper-sheet" id="print-template">
        <div class="cp-paper-office-box">This information is for office use only</div>
        <div class="cp-paper-title">Data Sheet for Cadaver Donation</div>

        <div class="cp-paper-h3">Details of the Deceased</div>
        <div class="cp-paper-row">
            <div class="cp-paper-label">Full Name</div><div class="cp-paper-colon">:</div><div class="cp-paper-val-full" id="p_dec_full_name"></div>
        </div>
        <div class="cp-paper-row">
            <div class="cp-paper-label">NIC No</div><div class="cp-paper-colon">:</div><div class="cp-paper-val" id="p_dec_nic"></div>
            <div class="cp-paper-label-short">Date of Birth</div><div class="cp-paper-colon">:</div><div class="cp-paper-val" id="p_dec_dob"></div>
        </div>
        <div class="cp-paper-row">
            <div class="cp-paper-label">Age</div><div class="cp-paper-colon">:</div><div class="cp-paper-val" id="p_dec_age"></div>
            <div class="cp-paper-label-short">Sex</div><div class="cp-paper-colon">:</div><div class="cp-paper-val" id="p_dec_sex"></div>
        </div>
        <div class="cp-paper-row">
            <div class="cp-paper-label">Race</div><div class="cp-paper-colon">:</div><div class="cp-paper-val" id="p_dec_race"></div>
            <div class="cp-paper-label-short">Religion</div><div class="cp-paper-colon">:</div><div class="cp-paper-val" id="p_dec_religion"></div>
        </div>
        <div class="cp-paper-row">
            <div class="cp-paper-label">Marital Status</div><div class="cp-paper-colon">:</div><div class="cp-paper-val-full" id="p_dec_marital"></div>
        </div>
        <div class="cp-paper-row">
            <div class="cp-paper-label-med">Place of Birth & District</div><div class="cp-paper-colon">:</div><div class="cp-paper-val-full" id="p_dec_birthplace"></div>
        </div>
        <div class="cp-paper-row">
            <div class="cp-paper-label-med">Occupation at the Time of Death</div><div class="cp-paper-colon">:</div><div class="cp-paper-val-full" id="p_dec_occupation"></div>
        </div>
        <div class="cp-paper-row">
            <div class="cp-paper-label-med">Address at the Time of Death</div><div class="cp-paper-colon">:</div><div class="cp-paper-val-full" id="p_dec_address"></div>
        </div>

        <div class="cp-paper-h3">Details of Immediate Relations (Spouse/Children/Siblings)</div>
        <table class="cp-paper-table" id="p_rel_table">
            <thead>
                <tr>
                    <th style="width:45%">Name</th>
                    <th style="width:30%">Relationship</th>
                    <th style="width:25%">NIC No</th>
                </tr>
            </thead>
            <tbody>
                <!-- JS populated -->
            </tbody>
        </table>

        <div class="cp-paper-h3">Parents if Less than 18 Years Old</div>
        <table class="cp-paper-table">
            <thead>
                <tr><th></th><th style="width:50%">Name</th><th style="width:25%">NIC No</th><th style="width:20%">Age</th></tr>
            </thead>
            <tbody>
                <tr><td style="font-weight:bold;">Mother</td><td id="p_mom_name"></td><td id="p_mom_nic"></td><td id="p_mom_age"></td></tr>
                <tr><td style="font-weight:bold;">Father</td><td id="p_dad_name"></td><td id="p_dad_nic"></td><td id="p_dad_age"></td></tr>
            </tbody>
        </table>

        <div class="cp-paper-row" style="margin-top:20px;">
            <div class="cp-paper-label">Past Medical History</div><div class="cp-paper-colon">:</div><div class="cp-paper-val-full" id="p_med_history"></div>
        </div>
        <div class="cp-paper-row">
            <div class="cp-paper-label">Past Surgical History</div><div class="cp-paper-colon">:</div><div class="cp-paper-val-full" id="p_surg_history"></div>
        </div>
        <div class="cp-paper-row">
            <div class="cp-paper-label">Other Diseases</div><div class="cp-paper-colon">:</div><div class="cp-paper-val-full" id="p_other_diseases"></div>
        </div>
        <div class="cp-paper-row" style="margin-top:20px;">
            <div class="cp-paper-label">Cause of Death</div><div class="cp-paper-colon">:</div><div class="cp-paper-val-full" id="p_cause_of_death"></div>
        </div>
        <div class="cp-paper-row">
            <div class="cp-paper-label">Date & Time of Death</div><div class="cp-paper-colon">:</div><div class="cp-paper-val-full" id="p_time_of_death"></div>
        </div>
        <div class="cp-paper-row">
            <div class="cp-paper-label">Place of Death</div><div class="cp-paper-colon">:</div><div class="cp-paper-val-full" id="p_place_of_death"></div>
        </div>

        <div class="cp-paper-consent-text">Herewith I Give Consent to Use the Cadaver of the Above Named Person for Medical Research and Educational Purposes.</div>
        <div class="cp-paper-signatures">
            <div class="cp-paper-sig-block"><span class="cp-paper-sig-line"></span><div>Date</div></div>
            <div class="cp-paper-sig-block"><span class="cp-paper-sig-line"></span><div>Signature</div></div>
        </div>

        <div class="cp-paper-h3">Details of the Person Handing Over the Cadaver</div>
        <div class="cp-paper-row">
            <div class="cp-paper-label">Full Name</div><div class="cp-paper-colon">:</div><div class="cp-paper-val-full" id="p_ho_name"></div>
        </div>
        <div class="cp-paper-row">
            <div class="cp-paper-label">Relationship</div><div class="cp-paper-colon">:</div><div class="cp-paper-val-full" id="p_ho_rel"></div>
        </div>
        <div class="cp-paper-row">
            <div class="cp-paper-label">NIC No</div><div class="cp-paper-colon">:</div><div class="cp-paper-val-full" id="p_ho_nic"></div>
        </div>
        <div class="cp-paper-row">
            <div class="cp-paper-label">Telephone No</div><div class="cp-paper-colon">:</div><div class="cp-paper-val-full" id="p_ho_phone"></div>
        </div>
        <div class="cp-paper-row">
            <div class="cp-paper-label">Address</div><div class="cp-paper-colon">:</div><div class="cp-paper-val-full" id="p_ho_address"></div>
        </div>
        <div class="cp-paper-row">
            <div class="cp-paper-label">Occupation</div><div class="cp-paper-colon">:</div><div class="cp-paper-val-full" id="p_ho_occ"></div>
        </div>
    </div>
</div>

<script>
function addRelRow() {
    const tbody = document.getElementById('rel_table_body');
    const tr = document.createElement('tr');
    tr.className = 'rel-row';
    tr.innerHTML = `
        <td><input type="text" class="cp-form-control" name="rel_name[]" placeholder="Enter name"></td>
        <td>
            <select class="cp-form-control" name="rel_rel[]">
                <option value="">Select...</option>
                <option>Spouse</option>
                <option>Child</option>
                <option>Sibling</option>
                <option>Other</option>
            </select>
        </td>
        <td><input type="text" class="cp-form-control" name="rel_nic[]" placeholder="Enter NIC"></td>
        <td style="text-align: center;">
            <button type="button" class="btn-remove" onclick="this.closest('tr').remove()" title="Remove row">
                <i class="fas fa-trash-alt"></i>
            </button>
        </td>
    `;
    tbody.appendChild(tr);
}

function triggerPrint() {
    const fields = [
        'dec_full_name', 'dec_nic', 'dec_dob', 'dec_age', 'dec_sex', 'dec_race', 
        'dec_religion', 'dec_marital', 'dec_birthplace', 'dec_occupation', 'dec_address',
        'mom_name', 'mom_nic', 'mom_age', 'dad_name', 'dad_nic', 'dad_age',
        'med_history', 'surg_history', 'other_diseases', 
        'cause_of_death', 'time_of_death', 'place_of_death',
        'ho_name', 'ho_rel', 'ho_nic', 'ho_phone', 'ho_address', 'ho_occ'
    ];
    
    fields.forEach(f => {
        let el = document.querySelector(`[name="${f}"]`);
        let val = el ? el.value.trim() : '';
        document.getElementById(`p_${f}`).innerText = val || '\u00A0'; 
    });

    const names = document.querySelectorAll('input[name="rel_name[]"]');
    const rels = document.querySelectorAll('select[name="rel_rel[]"]');
    const nics = document.querySelectorAll('input[name="rel_nic[]"]');
    
    let trHtml = '';
    let rowCount = Math.max(5, names.length); 
    
    for(let i=0; i<rowCount; i++) {
        let nameVal = names[i] ? names[i].value.trim() : '';
        let relVal = rels[i] ? rels[i].value.trim() : '';
        let nicVal = nics[i] ? nics[i].value.trim() : '';
        
        trHtml += `<tr>
            <td>${nameVal || '&nbsp;'}</td>
            <td>${relVal || '&nbsp;'}</td>
            <td>${nicVal || '&nbsp;'}</td>
        </tr>`;
    }
    document.querySelector('#p_rel_table tbody').innerHTML = trHtml;

    window.print();
}
</script>

<?php
$page_content = ob_get_clean();
require dirname(__DIR__) . '/layouts/custodian.layout.php';
?>
