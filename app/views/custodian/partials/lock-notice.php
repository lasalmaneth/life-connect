<?php
/**
 * Custodian Portal — Lock Notice Partial
 * Displays a high-visibility notice when the donation process is being
 * managed by another custodian (the 'Process Leader').
 */

if (isset($isLeader) && !$isLeader && !empty($leaderInfo)): ?>
    <div class="cp-lock-notice flipInX-animation">
        <div class="cp-lock-notice__glass"></div>
        <div class="cp-lock-notice__icon-wrapper">
            <div class="cp-lock-notice__icon">
                <i class="fas fa-lock"></i>
            </div>
            <div class="cp-lock-notice__icon-glow"></div>
        </div>
        
        <div class="cp-lock-notice__body">
            <h4 class="cp-lock-notice__title">Action Restricted: Managed by Coordinator</h4>
            <p class="cp-lock-notice__text">
                <span class="cp-lock-notice__author"><?= htmlspecialchars($leaderInfo->declared_by_name ?? 'The reporting custodian') ?></span> 
                initiated this report. Access is restricted to the process leader to ensure institutional compliance.
            </p>
            
            <div class="cp-lock-notice__actions">
                <?php if (!empty($leaderInfo->declared_by_phone)): ?>
                    <a href="tel:<?= htmlspecialchars($leaderInfo->declared_by_phone) ?>" class="cp-lock-action-btn">
                        <span class="btn-icon"><i class="fas fa-phone-alt"></i></span>
                        <span class="btn-text">Call Coordinator</span>
                    </a>
                <?php endif; ?>
                
                <?php if (!empty($leaderInfo->declared_by_email)): ?>
                    <a href="mailto:<?= htmlspecialchars($leaderInfo->declared_by_email) ?>" class="cp-lock-action-btn">
                        <span class="btn-icon"><i class="fas fa-envelope"></i></span>
                        <span class="btn-text">Send Email</span>
                    </a>
                <?php endif; ?>
                
                <?php if (empty($leaderInfo->declared_by_phone) && empty($leaderInfo->declared_by_email)): ?>
                    <div class="cp-lock-notice__info">
                        <i class="fas fa-exclamation-circle"></i> Contact info unavailable
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <style>
    @keyframes flipInX {
        from { transform: perspective(400px) rotate3d(1, 0, 0, 30deg); opacity: 0; }
        to { transform: perspective(400px); opacity: 1; }
    }

    .flipInX-animation {
        animation: flipInX 0.6s cubic-bezier(0.16, 1, 0.3, 1) both;
    }

    .cp-lock-notice {
        position: relative;
        display: flex;
        gap: 1.5rem;
        padding: 1.75rem;
        border-radius: 20px;
        background: linear-gradient(135deg, #fffcf0 0%, #fff9e6 100%);
        border: 1px solid rgba(251, 191, 36, 0.3);
        box-shadow: 
            0 10px 25px -5px rgba(217, 119, 6, 0.08),
            0 8px 10px -6px rgba(217, 119, 6, 0.05);
        margin-bottom: 2rem;
        overflow: hidden;
        align-items: center;
    }

    .cp-lock-notice__glass {
        position: absolute;
        top: 0; left: 0; right: 0; bottom: 0;
        background: radial-gradient(circle at top right, rgba(255,255,255,0.7) 0%, transparent 60%);
        pointer-events: none;
    }

    .cp-lock-notice__icon-wrapper {
        position: relative;
        flex-shrink: 0;
        z-index: 1;
    }

    .cp-lock-notice__icon {
        width: 60px;
        height: 60px;
        background: #ffffff;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #d97706;
        font-size: 1.5rem;
        box-shadow: 0 4px 12px rgba(217, 119, 6, 0.12);
        border: 1px solid rgba(251, 191, 36, 0.2);
        position: relative;
        z-index: 2;
    }

    .cp-lock-notice__icon-glow {
        position: absolute;
        top: 50%; left: 50%;
        width: 80%; height: 80%;
        background: #fbbf24;
        filter: blur(20px);
        opacity: 0.2;
        transform: translate(-50%, -50%);
        z-index: 1;
    }

    .cp-lock-notice__body {
        position: relative;
        z-index: 2;
        flex: 1;
    }

    .cp-lock-notice__title {
        font-size: 1.15rem;
        font-weight: 800;
        color: #92400e;
        margin: 0 0 0.5rem 0;
        letter-spacing: -0.01em;
    }

    .cp-lock-notice__text {
        font-size: 0.95rem;
        color: #b45309;
        line-height: 1.6;
        margin-bottom: 1.25rem;
        max-width: 600px;
    }

    .cp-lock-notice__author {
        font-weight: 800;
        color: #92400e;
        text-decoration: underline decoration-thickness(2px) underline-offset(2px) rgba(146, 64, 14, 0.2);
    }

    .cp-lock-notice__actions {
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .cp-lock-action-btn {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        padding: 0.6rem 1.25rem;
        background: #ffffff;
        border: 1px solid rgba(251, 191, 36, 0.4);
        border-radius: 12px;
        text-decoration: none;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 2px 4px rgba(217, 119, 6, 0.04);
    }

    .btn-icon {
        width: 28px;
        height: 28px;
        background: #fffcf0;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #d97706;
        font-size: 0.85rem;
        transition: all 0.3s;
    }

    .btn-text {
        font-size: 0.875rem;
        font-weight: 700;
        color: #92400e;
    }

    .cp-lock-action-btn:hover {
        transform: translateY(-2px);
        background: #ffffff;
        border-color: #fbbf24;
        box-shadow: 0 6px 15px rgba(217, 119, 6, 0.1);
    }

    .cp-lock-action-btn:hover .btn-icon {
        background: #fbbf24;
        color: white;
    }

    .cp-lock-notice__info {
        font-size: 0.85rem;
        color: #b45309;
        display: flex;
        align-items: center;
        gap: 6px;
        font-weight: 600;
        opacity: 0.7;
    }

    @media (max-width: 640px) {
        .cp-lock-notice {
            flex-direction: column;
            text-align: center;
            padding: 2rem 1.5rem;
        }
        .cp-lock-notice__text {
            margin-left: auto;
            margin-right: auto;
        }
        .cp-lock-notice__actions {
            justify-content: center;
        }
    }
    </style>
<?php endif; ?>
