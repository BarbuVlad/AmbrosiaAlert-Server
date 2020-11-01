-- For volunteers:
INSERT INTO volunteers(phone, email) VALUES ("12345", "vol@ab.com");
INSERT INTO volunteers(phone, email) VALUES ("12346", "vol@bc.com");
INSERT INTO volunteers(phone, email) VALUES ("12347", "vol@cd.com");
INSERT INTO volunteers(phone, email) VALUES ("12348", "vol@ea.com");

-- insert data for 2 of them to be blocked
INSERT INTO red_markers(latitude, longitude, uid_volunteer, time) VALUES ("1.123","1.123","6", "2020-08-04-16-00-00");
INSERT INTO red_markers(latitude, longitude, uid_volunteer, time) VALUES ("1.124","1.124","6", "2020-08-04-16-00-01");
INSERT INTO red_markers(latitude, longitude, uid_volunteer, time) VALUES ("1.125","1.125","6", "2020-08-04-16-00-02");
INSERT INTO red_markers(latitude, longitude, uid_volunteer, time) VALUES ("1.126","1.126","6", "2020-08-04-16-00-02");
INSERT INTO red_markers(latitude, longitude, uid_volunteer, time) VALUES ("1.127","1.127","6", "2020-08-04-16-00-00");
INSERT INTO red_markers(latitude, longitude, uid_volunteer, time) VALUES ("1.128","1.128","6", "2020-08-04-16-00-06");

INSERT INTO red_markers(latitude, longitude, uid_volunteer, time) VALUES ("2.123","2.123","8", "2020-08-04-16-00-01");
INSERT INTO red_markers(latitude, longitude, uid_volunteer, time) VALUES ("2.124","2.124","8", "2020-08-04-16-00-02");
INSERT INTO red_markers(latitude, longitude, uid_volunteer, time) VALUES ("2.125","2.125","8", "2020-08-04-16-00-03");
INSERT INTO red_markers(latitude, longitude, uid_volunteer, time) VALUES ("2.126","2.126","8", "2020-08-04-16-00-04");
INSERT INTO red_markers(latitude, longitude, uid_volunteer, time) VALUES ("2.127","2.127","8", "2020-08-04-16-00-05");
INSERT INTO red_markers(latitude, longitude, uid_volunteer, time) VALUES ("2.128","2.128","8", "2020-08-04-16-00-06");

-- test executat cu succes ( php -f ban_volunteer )

-- For new_volunteers

INSERT INTO new_volunteers(phone, email) VALUES ("12345", "vol@ab.com");
INSERT INTO new_volunteers(phone, email) VALUES ("12346", "vol@bc.com");
INSERT INTO new_volunteers(phone, email) VALUES ("12347", "vol@cd.com");
INSERT INTO new_volunteers(phone, email) VALUES ("12348", "vol@ea.com");
 -- yellow Markers
 INSERT INTO yellow_markers(latitude, longitude, uid_volunteer, time) VALUES ("1.123","1.123","2", "2020-08-04-16-00-00");
 INSERT INTO yellow_markers(latitude, longitude, uid_volunteer, time) VALUES ("1.124","1.124","2", "2020-08-04-16-00-01");
 INSERT INTO yellow_markers(latitude, longitude, uid_volunteer, time) VALUES ("1.125","1.125","2", "2020-08-04-16-00-02");
 INSERT INTO yellow_markers(latitude, longitude, uid_volunteer, time) VALUES ("1.126","1.126","2", "2020-08-04-16-00-02");
 INSERT INTO yellow_markers(latitude, longitude, uid_volunteer, time) VALUES ("1.127","1.127","2", "2020-08-04-16-00-00");
 INSERT INTO yellow_markers(latitude, longitude, uid_volunteer, time) VALUES ("1.128","1.128","2", "2020-08-04-16-00-06");

 INSERT INTO yellow_markers(latitude, longitude, uid_volunteer, time) VALUES ("2.123","2.123","4", "2020-08-04-16-00-01");
 INSERT INTO yellow_markers(latitude, longitude, uid_volunteer, time) VALUES ("2.124","2.124","4", "2020-08-04-16-00-02");
 INSERT INTO yellow_markers(latitude, longitude, uid_volunteer, time) VALUES ("2.125","2.125","4", "2020-08-04-16-00-03");
 INSERT INTO yellow_markers(latitude, longitude, uid_volunteer, time) VALUES ("2.126","2.126","4", "2020-08-04-16-00-04");
 INSERT INTO yellow_markers(latitude, longitude, uid_volunteer, time) VALUES ("2.127","2.127","4", "2020-08-04-16-00-05");
 INSERT INTO yellow_markers(latitude, longitude, uid_volunteer, time) VALUES ("2.128","2.128","4", "2020-08-04-16-00-06");

 INSERT INTO yellow_markers(latitude, longitude, uid_volunteer, time) VALUES ("3.127","3.127","1", "2020-08-04-16-00-05");
 INSERT INTO yellow_markers(latitude, longitude, uid_volunteer, time) VALUES ("3.128","3.128","1", "2020-08-04-16-00-06");

 INSERT INTO yellow_markers(latitude, longitude, uid_volunteer, time) VALUES ("4.127","4.127","5", "2020-08-04-16-00-05");


 -- FAIL a fost blocat vol. 1 ( php -f ban_new_volunteer.php )
-- UPDATE new_volunteers SET blocked = NULL
