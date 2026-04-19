<div id="matching" class="content-section" style="<?php echo (isset($initialSection) && $initialSection === 'matching') ? 'display:block' : 'display:none'; ?>">
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
                <?php if (!empty($surgery_matches)): ?>
                <table style="width: 100%; border-collapse: separate; border-spacing: 0; background: #fff; border-radius: 12px; overflow: hidden;">
                    <thead>
                        <tr style="background: #f8fafc;">
                            <th style="padding: 12px; text-align: left; color: #64748b; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.05em; font-weight: 700;">Organ</th>
                            <th style="padding: 12px; text-align: left; color: #64748b; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.05em; font-weight: 700;">Surgery Date</th>
                            <th style="padding: 12px; text-align: left; color: #64748b; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.05em; font-weight: 700;">Hospital Match Status</th>
                            <th style="padding: 12px; text-align: left; color: #64748b; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.05em; font-weight: 700;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($surgery_matches as $match): ?>
                        <tr style="border-bottom: 1px solid #f1f5f9;">
                            <td style="padding: 12px; font-weight: 700; color: #0f172a;"> <?= htmlspecialchars($match->organ_name) ?> </td>
                            <td style="padding: 12px; color: #1e293b; font-size: 0.95rem; font-weight: 600;"> <?= htmlspecialchars(!empty($match->surgery_date) ? date('d/m/Y H:i', strtotime($match->surgery_date)) : '-') ?> </td>
                            <td style="padding: 12px; color: #2563eb; font-weight: 700;"> <?= htmlspecialchars($match->hospital_match_status ?? 'PENDING') ?> </td>
                            <td style="padding: 12px;">
                                <button onclick="viewSurgeryMatchDetails(<?= $match->match_id ?>)" style="padding: 7px 18px; background: #2563eb; border: 1px solid #2563eb; color: #fff; border-radius: 8px; font-weight: 700; font-size: 0.8rem; cursor: pointer; transition: all 0.2s;">View Details</button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
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
