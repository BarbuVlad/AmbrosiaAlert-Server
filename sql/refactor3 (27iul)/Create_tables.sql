CREATE TABLE new_volunteers (
  uid INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  phone VARCHAR(14) NOT NULL,
  email VARCHAR(50) NOT NULL,
  password VARCHAR(50) NOT NULL,
  first_name VARCHAR(50),
  last_name VARCHAR(50),
  address VARCHAR(255),
  blocked VARCHAR(10),
  confirmations INT DEFAULT 0
);

CREATE TABLE yellow_markers (
  latitude DECIMAL(12,10) NOT NULL,
  longitude DECIMAL(12,10) NOT NULL,
  uid_volunteer INT UNSIGNED,
  time VARCHAR(25),
  FOREIGN KEY (uid_volunteer) REFERENCES new_volunteers(uid),
  PRIMARY KEY (latitude, longitude)
);
