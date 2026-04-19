<div id="organ-testing" class="content-section" style="<?php echo (isset($initialSection) && $initialSection === 'organ-testing') ? 'display:block' : 'display:none'; ?>">
    <div class="content-header" style="background: white; border-bottom: 1px solid #e2e8f0; padding: 25px 30px; border-radius: 16px 16px 0 0;">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div style="display: flex; align-items: center; gap: 15px;">
                <div style="width: 45px; height: 45px; background: rgba(59, 130, 246, 0.1); border-radius: 10px; display: flex; align-items: center; justify-content: center; color: #2563eb;">
                    <i class="fas fa-microscope" style="font-size: 1.2rem;"></i>
                </div>
                <div>
                    <h2 style="margin: 0; font-size: 1.5rem; color: #0f172a; font-weight: 800;">Basic Organ Testing</h2>
                    <p style="margin: 4px 0 0; color: #64748b; font-size: 0.85rem; font-weight: 500;">Preliminary biological screening and donor compatibility results.</p>
                </div>
            </div>
            <div>
                <span class="status-badge" style="background: #f1f5f9; color: #475569; padding: 6px 14px; border-radius: 8px; font-size: 0.75rem; font-weight: 700; border: 1px solid #e2e8f0;">
                    <i class="fas fa-sync fa-spin" style="margin-right: 6px; font-size: 0.7rem;"></i> LIVE SCREENING MODULE
                </span>
            </div>
        </div>
    </div>

    <div class="content-body" style="background: white; padding: 40px; border-radius: 0 0 16px 16px; border: 1px solid #e2e8f0; border-top: none; min-height: 400px; display: flex; flex-direction: column; align-items: center; justify-content: center; text-align: center;">
        <div style="max-width: 500px;">
            <div style="width: 80px; height: 80px; background: #f8fafc; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 24px; color: #cbd5e1; border: 2px dashed #e2e8f0;">
                <i class="fas fa-flask-vial" style="font-size: 2rem;"></i>
            </div>
            <h3 style="color: #1e293b; font-size: 1.25rem; font-weight: 700; margin-bottom: 12px;">Laboratory Integration in Progress</h3>
            <p style="color: #64748b; font-size: 0.95rem; line-height: 1.6; margin-bottom: 30px;">
                The basic organ testing module is currently being synchronized with the central laboratory system. Once complete, you will be able to review specific HLA typing and tissue compatibility results here.
            </p>
            <div style="display: flex; gap: 12px; justify-content: center;">
                <button onclick="window.location.href='hospital/test-results'" style="background: white; border: 1px solid #e2e8f0; color: #475569; padding: 10px 20px; border-radius: 8px; font-weight: 700; font-size: 0.85rem; cursor: pointer; transition: all 0.2s;">View All Test Results</button>
                <button onclick="showContent('overview')" style="background: #2563eb; color: white; border: none; padding: 10px 20px; border-radius: 8px; font-weight: 700; font-size: 0.85rem; cursor: pointer; transition: all 0.2s; box-shadow: 0 4px 12px rgba(37, 99, 235, 0.2);">Return to Dashboard</button>
            </div>
        </div>
    </div>
</div>

<style>
    #organ-testing .content-body button:hover {
        transform: translateY(-1px);
        filter: brightness(0.98);
    }
</style>
