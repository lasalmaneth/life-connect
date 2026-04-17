<div id="matching" class="content-section" style="display: none;">
    <div class="content-header" style="background: white; border-bottom: 1px solid #e2e8f0; padding: 25px 30px; border-radius: 16px 16px 0 0;">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div style="display: flex; align-items: center; gap: 15px;">
                <div style="width: 45px; height: 45px; background: rgba(37, 99, 235, 0.1); border-radius: 12px; display: flex; align-items: center; justify-content: center; color: #2563eb;">
                    <i class="fas fa-dna" style="font-size: 1.25rem;"></i>
                </div>
                <div>
                    <h2 style="margin: 0; font-size: 1.5rem; color: #0f172a; font-weight: 800;">Matching Results</h2>
                    <p style="margin: 4px 0 0; color: #64748b; font-size: 0.85rem; font-weight: 500;">AI-driven cross-matching results between donors and recipients.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="content-body" style="padding: 30px; background: #f8fafc; border-radius: 0 0 16px 16px; border: 1px solid #e2e8f0; border-top: none;">
        <div style="background: white; border-radius: 14px; border: 1px solid #e2e8f0; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,0.03);">
            <div style="padding: 20px 25px; border-bottom: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center; background: #fff;">
                <h3 style="margin: 0; font-size: 1rem; color: #1e293b; font-weight: 700;">Potential Compatibility Pairings</h3>
                <div style="display: flex; gap: 10px;">
                    <span style="background: #ecfdf5; color: #059669; padding: 4px 10px; border-radius: 6px; font-size: 0.7rem; font-weight: 700;">
                        <?= count($surgery_matches ?? []) ?> ACTIVE MATCHES
                    </span>
                </div>
            </div>
            
            <div style="padding: 20px;">
                <div style="display: flex; flex-direction: column; gap: 15px;">
                    <?php if (!empty($surgery_matches)): ?>
                        <?php foreach ($surgery_matches as $match): ?>
                            <div style="display: grid; grid-template-columns: 2fr 1fr 2fr 1.5fr; align-items: center; padding: 15px; background: #fff; border: 1px solid #f1f5f9; border-radius: 12px; transition: all 0.2s;">
                                <div style="display: flex; align-items: center; gap: 12px;">
                                    <div style="width: 40px; height: 40px; background: #eff6ff; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #2563eb; font-weight: 800; font-size: 0.8rem;">D</div>
                                    <div>
                                        <div style="font-weight: 700; color: #0f172a; font-size: 0.9rem;">Donor: <?= htmlspecialchars($match->donor_first_name . ' ' . $match->donor_last_name) ?></div>
                                        <div style="font-size: 0.75rem; color: #64748b;">Organ: <?= htmlspecialchars($match->organ_name) ?></div>
                                    </div>
                                </div>
                                <div style="text-align: center;">
                                    <div style="font-size: 0.75rem; color: #64748b; margin-bottom: 4px;">Status</div>
                                    <div style="font-weight: 800; color: #2563eb; font-size: 0.9rem;"><?= strtoupper($match->status) ?></div>
                                </div>
                                <div style="display: flex; align-items: center; gap: 12px; justify-content: flex-end;">
                                    <div style="text-align: right;">
                                        <div style="font-weight: 700; color: #0f172a; font-size: 0.9rem;">Match ID: #MAT-<?= str_pad($match->match_id, 4, '0', STR_PAD_LEFT) ?></div>
                                        <div style="font-size: 0.75rem; color: #64748b;">Date: <?= date('M d, Y', strtotime($match->match_date)) ?></div>
                                    </div>
                                    <div style="width: 40px; height: 40px; background: #fdf2f8; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #db2777; font-weight: 800; font-size: 0.8rem;">M</div>
                                </div>
                                <div style="display: flex; justify-content: flex-end; gap: 10px;">
                                    <button onclick="viewSurgeryMatchDetails(<?= $match->match_id ?>)" style="padding: 8px 14px; background: #f8fafc; border: 1px solid #e2e8f0; color: #475569; border-radius: 8px; font-weight: 700; font-size: 0.75rem; cursor: pointer;">Details</button>
                                    <button onclick="showSection('surgery-prep')" style="padding: 8px 14px; background: #ecfdf5; border: 1px solid #d1fae5; color: #059669; border-radius: 8px; font-weight: 700; font-size: 0.75rem; cursor: pointer;">Go to Approval</button>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div style="padding: 40px; text-align: center; color: #64748b;">
                            <i class="fas fa-search" style="font-size: 2rem; margin-bottom: 10px; opacity: 0.5;"></i>
                            <p>No active matches pending review.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
