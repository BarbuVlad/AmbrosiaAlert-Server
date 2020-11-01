START TRANSACTION;

  CREATE TABLE admins (
    name VARCHAR(50) PRIMARY KEY,
    password VARCHAR(50) NOT NULL
  );

  CREATE TABLE users (
    UID INT UNSIGNED AUTO_INCREMENT PRIMARY KEY
  );

  CREATE TABLE volunteers (
    UID INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    Phone VARCHAR(14) NOT NULL,
    email VARCHAR(50) NOT NULL,
    First_name VARCHAR(50),
    Last_name VARCHAR(50)
  );

  CREATE TABLE blue_Marker (
    UID INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    latitude FLOAT(12,10) NOT NULL,
    longitude FLOAT(12,10) NOT NULL,
    time VARCHAR(25) NOT NULL
  );

  CREATE TABLE bridge_user_marker (
    UID_user INT UNSIGNED NOT NULL,
    UID_marker INT UNSIGNED NOT NULL,
    FOREIGN KEY (UID_user) REFERENCES users(UID),
    FOREIGN KEY (UID_marker) REFERENCES blue_Marker(UID),
    PRIMARY KEY (UID_user, UID_marker)
  );

  CREATE TABLE red_marker (
    latitude FLOAT(12,10) NOT NULL,
    longitude FLOAT(12,10) NOT NULL,
    UID_volunteer INT UNSIGNED,
    FOREIGN KEY (UID_volunteer) REFERENCES volunteers(UID),
    PRIMARY KEY (latitude, longitude)
  );

  CREATE TABLE grey_marker (
    latitude FLOAT(12,10) NOT NULL,
    longitude FLOAT(12,10) NOT NULL,
    UID_volunteer INT UNSIGNED,
    Time_of_delete VARCHAR(25),
    PRIMARY KEY (latitude, longitude)
  );
COMMIT;
