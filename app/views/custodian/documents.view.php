<?php
/**
 * Custodian Portal — Documents View
 * Route: GET /custodian/documents
 * Active page key: documents
 */

$page_icon     = 'fa-folder-open';
$page_heading  = 'Documents';
$page_subtitle = 'Manage and upload case-related documents for the receiving institution.';

ob_start();
?>

<!-- Page Header -->
<?php include __DIR__ . '/partials/page-header.php'; ?>

<div class="cp-content__body">

    <!-- ── Upload Zone ────────────────────────────────────────────────── -->
    <?php
    $section_title = 'Upload Document';
    $section_icon  = 'fa-cloud-arrow-up';
    ob_start();
    ?>
    <div class="cp-notice cp-notice--info" style="margin-bottom:1rem;">
        <i class="fas fa-circle-info"></i>
        <div>
            <strong>Backend Integration Pending</strong>
            <p>Document uploads are disabled. Connect <code>POST /api/custodian/upload-document</code> to enable.</p>
        </div>
    </div>

    <div class="cp-upload-zone cp-upload-zone--disabled">
        <i class="fas fa-cloud-arrow-up cp-upload-zone__icon"></i>
        <div class="cp-upload-zone__text">Click to upload or drag and drop</div>
        <div class="cp-upload-zone__sub">PDF, JPG, PNG — max 10MB</div>
    </div>

    <div class="cp-form-row-2" style="margin-top:1rem;">
        <div class="cp-form-group">
            <label class="cp-form-label" for="doc_type">Document Type</label>
            <select id="doc_type" name="document_type" class="cp-form-control" disabled>
                <option value="">Select type…</option>
                <option>Death Certificate</option>
                <option>Medical Report</option>
                <option>Identity Document</option>
                <option>Consent Form</option>
                <option>Other</option>
            </select>
        </div>
        <div class="cp-form-group">
            <label class="cp-form-label" for="doc_institution">Institution</label>
            <select id="doc_institution" name="institution" class="cp-form-control" disabled>
                <option value="">Select institution…</option>
            </select>
        </div>
    </div>

    <div class="cp-form-actions">
        <button type="button" class="cp-btn cp-btn--primary" disabled title="Backend integration pending">
            <i class="fas fa-cloud-arrow-up"></i> Upload Document
        </button>
    </div>
    <?php
    $section_content = ob_get_clean();
    include __DIR__ . '/partials/section-card.php';
    ?>

    <!-- ── Documents Table ────────────────────────────────────────────── -->
    <?php
    $section_title = 'Uploaded Documents';
    $section_icon  = 'fa-folder-open';
    ob_start();
    ?>
    <div class="cp-table-wrap">
        <table class="cp-table">
            <thead>
                <tr>
                    <th>Document</th>
                    <th>Type</th>
                    <th>Institution</th>
                    <th>Uploaded</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="6">
                        <?php
                        $empty_icon = 'fa-folder-open';
                        $empty_msg  = 'No documents uploaded';
                        $empty_sub  = 'Backend integration pending';
                        include __DIR__ . '/partials/empty-state.php';
                        ?>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <?php
    $section_content = ob_get_clean();
    $section_action  = null;
    include __DIR__ . '/partials/section-card.php';
    ?>

</div><!-- /.cp-content__body -->

<?php
$page_content = ob_get_clean();
require dirname(__DIR__) . '/layouts/custodian.layout.php';
?>
