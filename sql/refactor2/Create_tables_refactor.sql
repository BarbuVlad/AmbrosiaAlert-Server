  CREATE TABLE admins (
    name VARCHAR(50) PRIMARY KEY,
    password VARCHAR(50) NOT NULL
  );

  CREATE TABLE users (
    uid INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    mac_user VARCHAR(30)
  );

  CREATE TABLE volunteers (
    uid INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    phone VARCHAR(14) NOT NULL,
    email VARCHAR(50) NOT NULL,
    password VARCHAR(50) NOT NULL,
    first_name VARCHAR(50),
    last_name VARCHAR(50),
    address VARCHAR(255)
  );

  CREATE TABLE blue_markers (
    latitude FLOAT(12,10) NOT NULL,
    longitude FLOAT(12,10) NOT NULL,
    uid_user INT UNSIGNED,
    time VARCHAR(25),
    FOREIGN KEY (uid_user) REFERENCES users(uid),
    PRIMARY KEY (latitude, longitude)
  );

  CREATE TABLE red_markers (
    latitude FLOAT(12,10) NOT NULL,
    longitude FLOAT(12,10) NOT NULL,
    uid_volunteer INT UNSIGNED,
    time VARCHAR(25),
    FOREIGN KEY (uid_volunteer) REFERENCES volunteers(uid),
    PRIMARY KEY (latitude, longitude)
  );

  CREATE TABLE grey_markers (
    latitude FLOAT(12,10) NOT NULL,
    longitude FLOAT(12,10) NOT NULL,
    uid_volunteer INT UNSIGNED,
    time_of_delete VARCHAR(25),
    PRIMARY KEY (latitude, longitude)
  );
-- COMMIT;
