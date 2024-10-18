USE booking;

-- Tabela comment
CREATE TABLE comment (
  id INT NOT NULL AUTO_INCREMENT,
  comment VARCHAR(215) NOT NULL,
  activity_id INT NOT NULL,
  user_id INT NOT NULL,
  postedon DATETIME NOT NULL,
  PRIMARY KEY (id),
  CONSTRAINT fk_comment_activity
    FOREIGN KEY (activity_id)
    REFERENCES activity (id),
  CONSTRAINT fk_comment_user
    FOREIGN KEY (user_id)
    REFERENCES user (id)
);

-- Tabela user
CREATE TABLE user (
  id INT NOT NULL AUTO_INCREMENT,
  firstname VARCHAR(45) NOT NULL,
  lastname VARCHAR(45) NOT NULL,
  email VARCHAR(45) NOT NULL UNIQUE,
  password VARCHAR(215) NOT NULL,
  isvendor BOOLEAN NOT NULL,
  creditcard_id INT NOT NULL,
  PRIMARY KEY (id),
  CONSTRAINT fk_user_creditcard
    FOREIGN KEY (creditcard_id)
    REFERENCES creditcard (id)
);

-- Tabela creditcard
CREATE TABLE creditcard (
  id INT NOT NULL AUTO_INCREMENT,
  number VARCHAR(16) NOT NULL,
  cvv VARCHAR(3) NOT NULL,
  expiry DATETIME NOT NULL,
  PRIMARY KEY (id)
);

-- Tabela reservation
CREATE TABLE reservation (
  id INT NOT NULL AUTO_INCREMENT,
  reservedbyuser_id INT NOT NULL,
  reservedon DATETIME NOT NULL,
  activity_id INT NOT NULL,
  creditcard_id INT NOT NULL,
  reservationstatus_id INT NOT NULL,
  PRIMARY KEY (id),
  CONSTRAINT fk_reservation_user
    FOREIGN KEY (reservedbyuser_id)
    REFERENCES user (id),
  CONSTRAINT fk_reservationactivity
    FOREIGN KEY (activity_id)
    REFERENCES activity (id),
  CONSTRAINT fk_reservation_creditcard
    FOREIGN KEY (creditcard_id)
    REFERENCES creditcard (id),
  CONSTRAINT fk_reservation_status
    FOREIGN KEY (reservationstatus_id)
    REFERENCES reservationstatus (id)
);

-- Tabela activity
CREATE TABLE activity (
  id INT NOT NULL AUTO_INCREMENT,
  name VARCHAR(45) NOT NULL,
  description VARCHAR(215) NOT NULL,
  date DATETIME NOT NULL,
  cost FLOAT NOT NULL,
  vendoruser_id INT NOT NULL,
  isarchived BOOLEAN NOT NULL,
  PRIMARY KEY (id),
  CONSTRAINT fk_activity_vendoruser
    FOREIGN KEY (vendoruser_id)
    REFERENCES user (id)
);

-- Tabela reservationstatus
CREATE TABLE reservationstatus (
  id INT NOT NULL AUTO_INCREMENT,
  name VARCHAR(15) NOT NULL UNIQUE,
  PRIMARY KEY (id)
);

-- Tabela usercreditcard
CREATE TABLE usercreditcard(
    user_id INT NOT NULL,
    creditcard_id INT NOT NULL,
    CONSTRAINT fk_usercreditcard_creditcard
    FOREIGN KEY (creditcard_id)
    REFERENCES creditcard (id),
    CONSTRAINT fk_usercreditcard_user
    FOREIGN KEY (user_id)
    REFERENCES user (id)
)

-- Tabela reservationactivity
CREATE TABLE reservationactivity(
    reservation_id INT NOT NULL,
    activity_id INT NOT NULL,
    CONSTRAINT fk_reservationactivity
    FOREIGN KEY (reservation_id)
    REFERENCES reservation (id),
    CONSTRAINT fk_reservationactivity_activity
    FOREIGN KEY (activity_id)
    REFERENCES activity (id)
)