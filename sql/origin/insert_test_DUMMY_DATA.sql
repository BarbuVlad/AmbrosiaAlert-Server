-- INSERT 5 USERS
INSERT INTO users VALUES();
INSERT INTO users VALUES();
INSERT INTO users VALUES();
INSERT INTO users VALUES();
INSERT INTO users VALUES();

-- INSERT SOME blue_Marker S ->TOPOLOVENI
INSERT INTO blue_Marker (latitude, longitude, time) VALUES(44.822319,25.074388);
INSERT INTO blue_Marker (latitude, longitude, time) VALUES(44.822437,25.075392);
INSERT INTO blue_Marker (latitude, longitude, time) VALUES(44.822756,25.074892);
INSERT INTO blue_Marker (latitude, longitude, time) VALUES(44.822243,25.074271);

-- INSERT SOME bridge_user_marker S
INSERT INTO bridge_user_marker (UID_user, UID_marker) VALUES(1,1);
INSERT INTO bridge_user_marker (UID_user, UID_marker) VALUES(1,2);
INSERT INTO bridge_user_marker (UID_user, UID_marker) VALUES(2,3);
INSERT INTO bridge_user_marker (UID_user, UID_marker) VALUES(3,4);
-- CONVERT(SYSDATE(), CHAR)
-- INSERT SOME volunteers
INSERT INTO volunteers (Phone, email, First_name, Last_name)
VALUES("+400723567339", "jasgh.yur@gmail.com", "Andrei", "Ionecu");

INSERT INTO volunteers (Phone, email, First_name, Last_name)
VALUES("+400773367685", "opyrvm@gmail.com", "Maria", "popescu");

-- INSERT SOME red_marker S
INSERT INTO red_marker (longitude,latitude) VALUES(44.823232,25.075171);
INSERT INTO red_marker (longitude,latitude) VALUES(44.823034,25.075869);
INSERT INTO red_marker (longitude,latitude, UID_volunteer) VALUES(44.825165,25.078394, 1);

-- INSERT grey_marker S
INSERT INTO grey_marker (latitude, longitude, UID_volunteer ,Time_of_delete)
VALUES(44.822638,25.078130, 2, CONVERT(SYSDATE()-5, CHAR));

INSERT INTO grey_marker (latitude, longitude, Time_of_delete)
VALUES(44.820051,25.076407, CONVERT(SYSDATE()-7, CHAR));
