-- Add blocked columns for volunteer and user
-- Add like column to red_marker

ALTER TABLE volunteers
MODIFY COLUMN blocked VARCHAR(10);

ALTER TABLE users
MODIFY COLUMN blocked VARCHAR(10);

ALTER TABLE red_markers
MODIFY COLUMN like INT(10);
