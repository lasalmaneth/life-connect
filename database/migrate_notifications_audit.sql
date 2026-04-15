-- ============================================================
-- Migration: Notifications + Admin Audit Logs Separation
-- Run this script once in phpMyAdmin or MySQL CLI
-- ============================================================

-- 1. Add sender_id to notifications table
--    user_id  = the RECIPIENT (who gets the message)
--    sender_id = who sent it (admin user_id, NULL = system)
ALTER TABLE `notifications`
  ADD COLUMN `sender_id` int(11) DEFAULT NULL AFTER `user_id`,
  ADD CONSTRAINT `fk_notifications_sender` FOREIGN KEY (`sender_id`) REFERENCES `users`(`id`) ON DELETE SET NULL;

-- 2. Create admin_audit_logs table
--    A clean, permanent record of every admin action.
--    Never deleted. Not visible to end users.
CREATE TABLE IF NOT EXISTS `admin_audit_logs` (
  `id`          int(11)      NOT NULL AUTO_INCREMENT,
  `admin_id`    int(11)      DEFAULT NULL COMMENT 'The admin who performed the action',
  `target_user_id` int(11)   DEFAULT NULL COMMENT 'The user who was affected',
  `action`      varchar(100) NOT NULL COMMENT 'e.g. STATUS_CHANGE, REVIEW, SUSPEND',
  `old_value`   varchar(255) DEFAULT NULL,
  `new_value`   varchar(255) DEFAULT NULL,
  `notes`       text         DEFAULT NULL COMMENT 'Review message or reason',
  `created_at`  timestamp    NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_audit_admin`  (`admin_id`),
  KEY `idx_audit_target` (`target_user_id`),
  CONSTRAINT `fk_audit_admin`  FOREIGN KEY (`admin_id`)       REFERENCES `users`(`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_audit_target` FOREIGN KEY (`target_user_id`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
