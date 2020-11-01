-- INSERT 5 USERS
INSERT INTO users(mac_user) VALUES('00:0u:95:2e:88:99');
INSERT INTO users(mac_user) VALUES('00:0o:95:1s:98:33');
INSERT INTO users(mac_user) VALUES('00:0a:95:3a:45:47');
INSERT INTO users(mac_user) VALUES('00:0a:95:5d:21:87');
INSERT INTO users(mac_user) VALUES('00:0a:95:6d:34:45');

-- INSERT SOME blue_Marker S ->TOPOLOVENI
INSERT INTO blue_markers (latitude, longitude, uid_user, time) VALUES(44.822319,25.074388, 1, '2020-04-29-17-56-33');
INSERT INTO blue_markers (latitude, longitude, uid_user, time) VALUES(44.822437,25.075392, 2,'2020-04-29-17-46-23');
INSERT INTO blue_markers (latitude, longitude, uid_user, time) VALUES(44.822756,25.074892, 1,'2020-04-29-17-57-44');
INSERT INTO blue_markers (latitude, longitude, uid_user, time) VALUES(44.822243,25.074271, 3,'2020-04-29-17-50-13');

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
