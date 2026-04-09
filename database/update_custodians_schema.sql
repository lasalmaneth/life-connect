-- migration to refactor custodians table
ALTER TABLE custodians DROP COLUMN custodian_number;
ALTER TABLE custodians ADD COLUMN status VARCHAR(20) DEFAULT 'PENDING';

-- existing custodians are active
UPDATE custodians SET status = 'ACTIVE' WHERE status IS NULL;
