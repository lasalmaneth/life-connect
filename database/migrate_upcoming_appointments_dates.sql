-- Migration: Add multi-day scheduling columns to upcoming_appointments
-- Task: Store test dates for multi-day clinical protocols (Day 1, Day 2, Day 3)

ALTER TABLE `upcoming_appointments` 
ADD COLUMN `scheduled_date_1` DATE DEFAULT NULL AFTER `test_date`,
ADD COLUMN `scheduled_date_2` DATE DEFAULT NULL AFTER `scheduled_date_1`,
ADD COLUMN `scheduled_date_3` DATE DEFAULT NULL AFTER `scheduled_date_2`;
