<?php if (!empty($requests)): ?>
    <?php foreach ($requests as $request): ?>
        <div class="table-row" data-id="<?= $request->id ?>">
            <div class="table-cell">
                <strong>SUP<?= str_pad($request->id, 3, '0', STR_PAD_LEFT) ?></strong>
            </div>
            <div class="table-cell">
                <div><?= htmlspecialchars($request->patient_name) ?></div>
                <div style="font-size: 0.8rem; color: #64748b;"><?= htmlspecialchars($request->patient_nic) ?></div>
            </div>
            <div class="table-cell">
                <div style="max-width: 200px; overflow: hidden; text-overflow: ellipsis;" title="<?= htmlspecialchars($request->description) ?>">
                    <?= htmlspecialchars($request->reason) ?>
                </div>
            </div>
            <div class="table-cell">
                <strong>LKR 0.00</strong> <!-- Amount not in DB table support_requests -->
            </div>
            <div class="table-cell">
                <?= date('M d, Y', strtotime($request->submitted_date)) ?>
            </div>
            <div class="table-cell">
                <span class="status-badge status-<?= strtolower($request->status) ?>">
                    <?= ucfirst(strtolower($request->status)) ?>
                </span>
            </div>
            <div class="table-cell">
                <div style="display: flex; gap: 0.5rem; flex-wrap: nowrap;">
                    <button class="btn btn-small btn-success" onclick="openDetails(<?= htmlspecialchars(json_encode($request)) ?>)">
                        <i class="fa-solid fa-eye"></i>
                    </button>
                    
                    <?php if (strtolower($request->status) === 'pending'): ?>
                        <form action="<?= ROOT ?>/aftercare-admin/handle-action" method="POST" style="display: inline;">
                            <input type="hidden" name="request_id" value="<?= $request->id ?>">
                            <input type="hidden" name="action" value="approved">
                            <button type="submit" class="btn btn-small btn-primary" title="Approve">
                                <i class="fa-solid fa-check"></i>
                            </button>
                        </form>
                        <form action="<?= ROOT ?>/aftercare-admin/handle-action" method="POST" style="display: inline;">
                            <input type="hidden" name="request_id" value="<?= $request->id ?>">
                            <input type="hidden" name="action" value="rejected">
                            <button type="submit" class="btn btn-small btn-danger" title="Reject">
                                <i class="fa-solid fa-times"></i>
                            </button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
<?php else: ?>
    <div class="table-row" style="justify-content: center; padding: 2rem; color: #64748b;">
        No support requests found in the database.
    </div>
<?php endif; ?>
