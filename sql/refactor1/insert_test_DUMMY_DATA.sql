-- INSERT 5 USERS
INSERT INTO users(mac_user) VALUES('00:0u:95:2e:88:99');
INSERT INTO users(mac_user) VALUES('00:0o:95:1s:98:33');
INSERT INTO users(mac_user) VALUES('00:0a:95:3a:45:47');
INSERT INTO users(mac_user) VALUES('00:0a:95:5d:21:87');
INSERT INTO users(mac_user) VALUES('00:0a:95:6d:34:45');

-- INSERT SOME blue_Marker S ->TOPOLOVENI
INSERT INTO blue_markers (latitude, longitude) VALUES(44.822319,25.074388);
INSERT INTO blue_markers (latitude, longitude) VALUES(44.822437,25.075392);
INSERT INTO blue_markers (latitude, longitude) VALUES(44.822756,25.074892);
INSERT INTO blue_markers (latitude, longitude) VALUES(44.822243,25.074271);

-- INSERT SOME bridge_user_marker S
INSERT INTO bridge_user_marker (UID_user, latitude, longitude, time) VALUES(1,44.822319,25.074388, '2020-04-28-20-05-23');
INSERT INTO bridge_user_marker (UID_user, latitude, longitude, time) VALUES(1,44.822437,25.075392,'2020-04-28-20-05-53');
INSERT INTO bridge_user_marker (UID_user, latitude, longitude, time) VALUES(2,44.822756,25.074892,'2020-04-28-20-06-36');
INSERT INTO bridge_user_marker (UID_user, latitude, longitude, time) VALUES(3,44.822243,25.074271,'2020-04-28-20-09-13');
-- CONVERT(SYSDATE(), CHAR)
-- INSERT SOME volunteers
INSERT INTO volunteers (Phone, email, First_name, Last_name, password)
VALUES("+400723567339", "jasgh.yur@gmail.com", "Andrei", "Ionecu", 'abcd');

INSERT INTO volunteers (Phone, email, First_name, Last_name, password)
VALUES("+400773367685", "opyrvm@gmail.com", "Maria", "popescu", '1234');

-- INSERT SOME red_marker S
INSERT INTO red_markers (longitude,latitude) VALUES(44.823232,25.075171);
INSERT INTO red_markers (longitude,latitude) VALUES(44.823034,25.075869);
INSERT INTO red_markers (longitude,latitude, UID_volunteer) VALUES(44.825165,25.078394, 1);

-- INSERT grey_marker S
INSERT INTO grey_markers (latitude, longitude, UID_volunteer ,Time_of_delete)
VALUES(44.822638,25.078130, 2, CONVERT(SYSDATE()-5, CHAR));

INSERT INTO grey_markers (latitude, longitude, Time_of_delete)
VALUES(44.820051,25.076407, CONVERT(SYSDATE()-7, CHAR));
