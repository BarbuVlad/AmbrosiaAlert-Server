-- General commands
/*
-- Create 4 users(all relative to ambrosia DB):
1. minimal_user = anyone than wants to acces the api (i don't konw anything about this user)
  - select: red_markers, grey_markers, blue_markers

2. basic_user = a normal user (mobile app - is in users table with: UID, MAC_user)
  - insert: blue_markers, users, bridge_user_marker
  - select: red_markers, users

3. volunteer = a user with some privileges added
  - insert: red_markers, volunteer
  - select: blue_markers, red_markers, grey_markers
  - update: red_markers

4. admin = all privileges (almost)
  -> all except : create and drop;

select * from mysql.user; -- get all users

Create user:
CREATE USER 'newuser'@'localhost' IDENTIFIED BY 'user_password';
CREATE USER 'newuser'@'%' IDENTIFIED BY 'user_password'; -- % wildcard user can access from any host

Grant:
GRANT ALL PRIVILEGES ON database_name.* TO 'database_user'@'localhost';

GRANT ALL PRIVILEGES ON database_name.table_name TO 'database_user'@'localhost';

GRANT SELECT, INSERT, DELETE ON database_name.* TO database_user@'localhost';

Show all privileges of user:
SHOW GRANTS FOR 'database_user'@'localhost';

Drop user:
DROP USER 'user'@'localhost'

-- See documentation for more: https://dev.mysql.com/doc/refman/5.7/en/grant.html
*/
