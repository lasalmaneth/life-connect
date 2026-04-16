<?php
/**
 * Custodian Portal — Lock Notice Partial
 * Displays a premium, non-intrusive notice when the process is managed by another coordinator.
 */

if (isset($isLeader) && !$isLeader && !empty($leaderInfo)): ?>
    <div class="cp-lock-notice modernized-notice flipInX-animation">
        <div class="cp-lock-notice__accent"></div>
        
        <div class="cp-lock-notice__main">
            <div class="cp-lock-notice__header">
                <div class="cp-lock-notice__badge">
                    <i class="fas fa-lock"></i>
                    <span>READ-ONLY ACCESS</span>
                </div>
                <h4 class="cp-lock-notice__title">Process Managed by Coordinator</h4>
            </div>

            <div class="cp-lock-notice__content">
                <p class="cp-lock-notice__description">
                    <strong class="cp-lock-notice__leader-name"><?= htmlspecialchars($leaderInfo->declared_by_name ?? 'The reporting custodian') ?></strong> 
                    is currently leading this donation case. You have full visibility but management actions are restricted to ensure institutional compliance.
                </p>

                <div class="cp-lock-notice__contact-info">
                    <?php if (!empty($leaderInfo->declared_by_phone)): ?>
                        <a href="tel:<?= htmlspecialchars($leaderInfo->declared_by_phone) ?>" class="cp-lock-contact-item">
                            <i class="fas fa-phone-alt"></i>
                            <span><?= htmlspecialchars($leaderInfo->declared_by_phone) ?></span>
                        </a>
                    <?php endif; ?>
                    
                    <?php if (!empty($leaderInfo->declared_by_email)): ?>
                        <a href="mailto:<?= htmlspecialchars($leaderInfo->declared_by_email) ?>" class="cp-lock-contact-item">
                            <i class="fas fa-envelope"></i>
                            <span><?= htmlspecialchars($leaderInfo->declared_by_email) ?></span>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <style>
    .modernized-notice {
        position: relative;
        background: #ffffff;
        border-radius: 16px;
        padding: 1.5rem 1.75rem;
        margin-bottom: 2.5rem;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 20px -2px rgba(0, 0, 0, 0.03);
        display: flex;
        overflow: hidden;
        transition: transform 0.3s ease;
    }

    .modernized-notice:hover {
        transform: translateY(-2px);
    }

    .cp-lock-notice__accent {
        position: absolute;
        top: 0; left: 0; bottom: 0;
        width: 5px;
        background: linear-gradient(to bottom, #4f46e5, #06b6d4);
    }

    .cp-lock-notice__main {
        width: 100%;
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }

    .cp-lock-notice__header {
        display: flex;
        align-items: center;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .cp-lock-notice__badge {
        display: flex;
        align-items: center;
        gap: 6px;
        background: #f1f5f9;
        color: #475569;
        padding: 4px 10px;
        border-radius: 99px;
        font-size: 0.65rem;
        font-weight: 800;
        letter-spacing: 0.05em;
        text-transform: uppercase;
    }

    .cp-lock-notice__badge i {
        font-size: 0.7rem;
        color: #64748b;
    }

    .cp-lock-notice__title {
        font-size: 1.05rem;
        font-weight: 800;
        color: #1e293b;
        margin: 0;
        letter-spacing: -0.01em;
    }

    .cp-lock-notice__content {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 2rem;
    }

    .cp-lock-notice__description {
        font-size: 0.9rem;
        color: #64748b;
        line-height: 1.6;
        margin: 0;
        max-width: 650px;
    }

    .cp-lock-notice__leader-name {
        color: #1e293b;
        font-weight: 700;
    }

    .cp-lock-notice__contact-info {
        display: flex;
        gap: 1.5rem;
        margin: 0.25rem 0 1.25rem 0;
    }

    .cp-lock-contact-item {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 0.85rem;
        color: #475569;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.2s ease;
    }

    .cp-lock-contact-item:hover {
        color: #4f46e5;
        transform: translateX(2px);
    }

    .cp-lock-contact-item i {
        color: #64748b;
        font-size: 0.8rem;
        transition: color 0.2s ease;
    }

    .cp-lock-contact-item:hover i {
        color: #4f46e5;
    }

    .cp-lock-notice__actions {
        display: flex;
        gap: 0.75rem;
        flex-shrink: 0;
    }

    .cp-lock-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 0.6rem 1.2rem;
        border-radius: 12px;
        text-decoration: none;
        font-size: 0.85rem;
        font-weight: 700;
        transition: all 0.25s ease;
        border: 1px solid transparent;
    }

    .cp-lock-btn--call {
        background: #f8fafc;
        color: #1e293b;
        border-color: #e2e8f0;
    }

    .cp-lock-btn--call:hover {
        background: #f1f5f9;
        border-color: #cbd5e1;
        transform: translateY(-1px);
    }

    .cp-lock-btn--mail {
        background: #4f46e5;
        color: white;
        box-shadow: 0 4px 12px rgba(79, 70, 229, 0.2);
    }

    .cp-lock-btn--mail:hover {
        background: #4338ca;
        box-shadow: 0 6px 16px rgba(79, 70, 229, 0.3);
        transform: translateY(-1px);
    }

    @media (max-width: 900px) {
        .cp-lock-notice__content {
            flex-direction: column;
            gap: 1.25rem;
        }
        .cp-lock-notice__actions {
            width: 100%;
        }
        .cp-lock-btn {
            flex: 1;
            justify-content: center;
        }
    }

    @keyframes flipInX {
        from { transform: perspective(400px) rotate3d(1, 0, 0, 10deg); opacity: 0; }
        to { transform: perspective(400px); opacity: 1; }
    }

    .flipInX-animation {
        animation: flipInX 0.6s cubic-bezier(0.16, 1, 0.3, 1) both;
    }
    </style>
<?php endif; ?>
