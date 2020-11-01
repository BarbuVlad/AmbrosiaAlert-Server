ALTER TABLE blue_markers
MODIFY COLUMN latitude DECIMAL(20,18);

ALTER TABLE blue_markers
MODIFY COLUMN longitude DECIMAL(20,18);

ALTER TABLE red_markers
MODIFY COLUMN latitude DECIMAL(20,18);

ALTER TABLE red_markers
MODIFY COLUMN longitude DECIMAL(20,18);

ALTER TABLE grey_markers
MODIFY COLUMN latitude DECIMAL(20,18);

ALTER TABLE grey_markers
MODIFY COLUMN longitude DECIMAL(20,18);

-- MySQL prefers DECIMAL; FLOAT is a hard-to-compare value (see: https://bitbashing.io/comparing-floats.html)

/*
ALTER TABLE blue_markers
MODIFY COLUMN latitude DECIMAL(12,10);

ALTER TABLE blue_markers
MODIFY COLUMN longitude DECIMAL(12,10);

ALTER TABLE red_markers
MODIFY COLUMN latitude DECIMAL(12,10);

ALTER TABLE red_markers
MODIFY COLUMN longitude DECIMAL(12,10);

ALTER TABLE grey_markers
MODIFY COLUMN latitude DECIMAL(12,10);

ALTER TABLE grey_markers
MODIFY COLUMN longitude DECIMAL(12,10);
*/
