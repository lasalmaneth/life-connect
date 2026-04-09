-- Adding organ_id to consent_withdrawals to track per-organ revocations
ALTER TABLE consent_withdrawals ADD COLUMN organ_id INT DEFAULT NULL;
ALTER TABLE consent_withdrawals ALTER COLUMN status SET DEFAULT 'PENDING_UPLOAD';
