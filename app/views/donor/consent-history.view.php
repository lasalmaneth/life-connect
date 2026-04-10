<?php
/**
 * Donor Portal — Donation Consent History Page
 */

include __DIR__ . '/inc/header.view.php';
include __DIR__ . '/inc/sidebar.view.php';
?>

<main class="d-content">
    <div class="d-content__header">
        <h2><i class="fas fa-history text-accent"></i> Donation Consent History</h2>
        <p>Complete record of all your organ and body donation consents.</p>
    </div>
    
    <div class="d-content__body">
        <div class="d-widget">
            <div class="d-widget__header">
                <div class="d-widget__title"><i class="fas fa-clipboard-list text-accent"></i> Consent Logs</div>
            </div>
            <div class="d-widget__body">
                <?php if (empty($consent_history)): ?>
                    <div style="text-align: center; padding: 3rem 1rem;">
                        <i class="fas fa-scroll" style="font-size: 4rem; color: var(--g200); margin-bottom: 1.5rem; display: block;"></i>
                        <h3 style="color: var(--slate); font-size: 1.1rem; margin-bottom: 0.5rem;">No History Found</h3>
                        <p style="color: var(--g500); font-size: 0.95rem;">You haven't made any donation pledges or consents yet.</p>
                        <a href="<?= ROOT ?>/donor/donations" class="d-btn d-btn--primary" style="margin-top: 1.5rem;">Make a Pledge</a>
                    </div>
                <?php else: ?>
                    <div class="d-table-wrapper">
                        <table class="d-table">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Consent Type</th>
                                    <th>Details</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($consent_history as $item): ?>
                                    <tr>
                                        <td><?= date('M d, Y', strtotime($item['date'])) ?></td>
                                        <td>
                                            <?php if ($item['type'] === 'ORGAN'): ?>
                                                <span class="d-status" style="background: var(--blue-50); color: var(--blue-700);"><i class="fas fa-heart"></i> Organ</span>
                                            <?php else: ?>
                                                <span class="d-status" style="background: var(--purple-50); color: var(--purple-700);"><i class="fas fa-user-alt"></i> Full Body</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div style="font-weight: 600; color: var(--slate);"><?= htmlspecialchars($item['name']) ?></div>
                                            <div style="font-size: 0.75rem; color: var(--g500);"><?= htmlspecialchars($item['details'] ?? '') ?></div>
                                        </td>
                                        <td>
                                            <?php 
                                            $status = strtoupper($item['status']);
                                            $class = 'd-status--pending';
                                            if ($status === 'APPROVED' || $status === 'ACTIVE' || $status === 'UPLOADED') $class = 'd-status--success';
                                            elseif ($status === 'WITHDRAWN' || $status === 'REJECTED') $class = 'd-status--danger';
                                            ?>
                                            <span class="d-status <?= $class ?>" 
                                                  <?php if ($status === 'WITHDRAWN' && !empty($item['withdrawal_form_path'])): ?>
                                                    onclick="viewWithdrawalPdf('<?= ROOT ?>/<?= $item['withdrawal_form_path'] ?>')" 
                                                    style="cursor: pointer;" 
                                                    title="Click to view signed withdrawal document"
                                                  <?php endif; ?>>
                                                <?= $status ?>
                                                <?php if ($status === 'WITHDRAWN' && !empty($item['withdrawal_form_path'])): ?>
                                                    <i class="fas fa-file-pdf" style="margin-left: 5px;"></i>
                                                <?php endif; ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php if (!empty($item['signed_form_path'])): ?>
                                                <a href="<?= ROOT ?>/<?= $item['signed_form_path'] ?>" target="_blank" class="d-btn d-btn--icon" title="View Document"><i class="fas fa-file-pdf"></i></a>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</main>

<?php include __DIR__ . '/inc/footer.view.php'; ?>

<!-- PDF Viewer Modal -->
<div id="pdfViewerModal" class="d-modal">
    <div class="d-modal__body" style="max-width: 90%; width: 1000px; height: 90vh; padding: 0; display: flex; flex-direction: column;">
        <div class="d-modal__header" style="background: var(--slate); color: white; padding: 15px 25px;">
            <h3 style="margin:0; font-size: 1.1rem;"><i class="fas fa-file-pdf"></i> Signed Withdrawal Document</h3>
            <button class="d-modal__close" onclick="closePdfModal()" style="color: white; font-size: 1.5rem;">&times;</button>
        </div>
        <div class="d-modal__content" style="flex: 1; padding: 0; overflow: hidden; background: #525659;">
            <iframe id="pdfFrame" src="" style="width: 100%; height: 100%; border: none;"></iframe>
        </div>
        <div class="d-modal__footer" style="padding: 10px 25px; background: var(--g50); display: flex; justify-content: flex-end;">
            <button class="d-btn d-btn--outline" onclick="closePdfModal()">Close Preview</button>
        </div>
    </div>
</div>

<script>
function viewWithdrawalPdf(path) {
    const frame = document.getElementById('pdfFrame');
    const modal = document.getElementById('pdfViewerModal');
    
    frame.src = path;
    modal.classList.add('active');
    document.body.style.overflow = 'hidden';
}

function closePdfModal() {
    const modal = document.getElementById('pdfViewerModal');
    const frame = document.getElementById('pdfFrame');
    
    modal.classList.remove('active');
    frame.src = "";
    document.body.style.overflow = 'auto';
}

// Close on outside click
window.onclick = function(event) {
    const modal = document.getElementById('pdfViewerModal');
    if (event.target == modal) {
        closePdfModal();
    }
}
</script>

<style>
.d-modal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.6);
    display: none;
    align-items: center;
    justify-content: center;
    z-index: 9999;
    backdrop-filter: blur(4px);
}
.d-modal.active {
    display: flex;
}
.d-modal__body {
    background: white;
    border-radius: 12px;
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
    overflow: hidden;
    animation: modalSpring 0.3s cubic-bezier(0.68, -0.55, 0.265, 1.55);
}
@keyframes modalSpring {
    from { transform: scale(0.8); opacity: 0; }
    to { transform: scale(1); opacity: 1; }
}
.d-modal__header {
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.d-modal__close {
    background: none;
    border: none;
    cursor: pointer;
}
</style>
