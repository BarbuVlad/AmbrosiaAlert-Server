-- Add to users MAC_user
ALTER TABLE users
ADD MAC_user VARCHAR(30);

ALTER TABLE bridge_user_marker
ADD time VARCHAR(30);
-- ALTER TABLE bridge_user_marker
-- ALTER time SET DEFAULT CONVERT(SYSDATE(), CHAR);

ALTER TABLE red_marker
ADD time VARCHAR(30);
-- ALTER TABLE red_marker
-- ALTER time SET DEFAULT CONVERT(SYSDATE(), CHAR);

ALTER TABLE volunteers
ADD address VARCHAR(255);

ALTER TABLE blue_Marker
DROP COLUMN time;

/*

*/
