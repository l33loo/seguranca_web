USE booking;

-- Tabela user
CREATE TABLE IF NOT EXISTS user (
  id INT NOT NULL AUTO_INCREMENT,
  firstname VARCHAR(45) NOT NULL,
  lastname VARCHAR(45) NOT NULL,
  email VARCHAR(45) NOT NULL UNIQUE,
  passwordhash VARCHAR(215) NOT NULL,
  isvendor BOOLEAN NOT NULL,
  PRIMARY KEY (id)
);

-- Tabela creditcard
CREATE TABLE IF NOT EXISTS creditcard (
  id INT NOT NULL AUTO_INCREMENT,
  number TEXT NOT NULL,
  cvv TEXT DEFAULT NULL,
  expiry TEXT NOT NULL,
  user_id INT DEFAULT NULL,
  PRIMARY KEY (id),
  CONSTRAINT fk_user_creditcard
    FOREIGN KEY (user_id)
    REFERENCES user (id)
);

-- Tabela activity
CREATE TABLE IF NOT EXISTS activity (
  id INT NOT NULL AUTO_INCREMENT,
  name VARCHAR(45) NOT NULL,
  description VARCHAR(215) NOT NULL,
  date DATE NOT NULL,
  time TIME NOT NULL,
  cost FLOAT NOT NULL,
  vendoruser_id INT NOT NULL,
  isarchived BOOLEAN NOT NULL DEFAULT FALSE,
  PRIMARY KEY (id),
  CONSTRAINT fk_activity_vendoruser
    FOREIGN KEY (vendoruser_id)
    REFERENCES user (id)
);

-- Tabela comment
CREATE TABLE IF NOT EXISTS comment (
  id INT NOT NULL AUTO_INCREMENT,
  comment VARCHAR(215) NOT NULL,
  activity_id INT NOT NULL,
  user_id INT NOT NULL,
  postedon DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  CONSTRAINT fk_comment_activity
    FOREIGN KEY (activity_id)
    REFERENCES activity (id),
  CONSTRAINT fk_comment_user
    FOREIGN KEY (user_id)
    REFERENCES user (id)
);

-- Tabela reservation_status
CREATE TABLE IF NOT EXISTS reservation_status (
  id INT NOT NULL AUTO_INCREMENT,
  name VARCHAR(15) NOT NULL UNIQUE,
  PRIMARY KEY (id)
);

INSERT INTO reservation_status(id, name)
VALUES (1, 'Pending'),
(2, 'Scheduled'),
(3, 'Realized'),
(4, 'Postponed'),
(5, 'Cancelled');

-- Tabela reservation
CREATE TABLE IF NOT EXISTS reservation (
  id INT NOT NULL AUTO_INCREMENT,
  reservedbyuser_id INT NOT NULL,
  reservedon DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  activity_id INT NOT NULL,
  creditcard_id INT NOT NULL,
  reservationstatus_id INT NOT NULL DEFAULT 1,
  PRIMARY KEY (id),
  CONSTRAINT fk_reservation_user
    FOREIGN KEY (reservedbyuser_id)
    REFERENCES user (id),
  CONSTRAINT fk_reservation_activity
    FOREIGN KEY (activity_id)
    REFERENCES activity (id),
  CONSTRAINT fk_reservation_creditcard
    FOREIGN KEY (creditcard_id)
    REFERENCES creditcard (id),
  CONSTRAINT fk_reservation_status
    FOREIGN KEY (reservationstatus_id)
    REFERENCES reservation_status (id)
);

-- Tabela reservation_activity
CREATE TABLE IF NOT EXISTS reservation_activity (
  reservation_id INT NOT NULL,
  activity_id INT NOT NULL,
  CONSTRAINT fk_reservation_activity_reservation
    FOREIGN KEY (reservation_id)
    REFERENCES reservation (id),
  CONSTRAINT fk_reservation_activity_activity
    FOREIGN KEY (activity_id)
    REFERENCES activity (id)
);