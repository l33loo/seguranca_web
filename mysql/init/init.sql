USE booking;

-- Tabela Comment
CREATE TABLE Comment (
  id INT NOT NULL AUTO_INCREMENT,
  comment VARCHAR(215) NOT NULL,
  activity_id INT NOT NULL,
  user_id INT NOT NULL,
  postedon DATETIME NOT NULL,
  PRIMARY KEY (id),
  CONSTRAINT fk_comment_activity
    FOREIGN KEY (activity_id)
    REFERENCES Activity (id),
  CONSTRAINT fk_comment_user
    FOREIGN KEY (user_id)
    REFERENCES User (id)
);

-- Tabela User
CREATE TABLE User (
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
    REFERENCES CreditCard (id)
);

-- Tabela CreditCard
CREATE TABLE CreditCard (
  id INT NOT NULL AUTO_INCREMENT,
  number VARCHAR(16) NOT NULL,
  cvv VARCHAR(3) NOT NULL,
  expiry DATETIME NOT NULL,
  PRIMARY KEY (id)
);

-- Tabela Reservation
CREATE TABLE Reservation (
  id INT NOT NULL AUTO_INCREMENT,
  reservedbyuser_id INT NOT NULL,
  reservedon DATETIME NOT NULL,
  activity_id INT NOT NULL,
  creditcard_id INT NOT NULL,
  reservationstatus_id INT NOT NULL,
  PRIMARY KEY (id),
  CONSTRAINT fk_reservation_user
    FOREIGN KEY (reservedbyuser_id)
    REFERENCES User (id),
  CONSTRAINT fk_reservation_activity
    FOREIGN KEY (activity_id)
    REFERENCES Activity (id),
  CONSTRAINT fk_reservation_creditcard
    FOREIGN KEY (creditcard_id)
    REFERENCES CreditCard (id),
  CONSTRAINT fk_reservation_status
    FOREIGN KEY (reservationstatus_id)
    REFERENCES ReservationStatus (id)
);

-- Tabela Activity
CREATE TABLE Activity (
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
    REFERENCES User (id)
);

-- Tabela ReservationStatus
CREATE TABLE ReservationStatus (
  id INT NOT NULL AUTO_INCREMENT,
  name VARCHAR(15) NOT NULL UNIQUE,
  PRIMARY KEY (id)
);

-- Tabela UserCreditCard
CREATE TABLE UserCreditCard{
    user_id INT NOT NULL,
    creditcard_id INT NOT NULL,
    CONSTRAINT fk_UserCreditCard_creditcard
    FOREIGN KEY (creditcard_id)
    REFERENCES CreditCard (id),
    CONSTRAINT fk_UserCreditCard_user
    FOREIGN KEY (user_id)
    REFERENCES User (id)
}

-- Tabela ReservationActivity
CREATE TABLE Reservation_Activity{
    reservation_id: INT NOT NULL
    activity_id: INT NOT NULL
    CONSTRAINT fk_reservation_activity
    FOREIGN KEY (reservation_id)
    REFERENCES Reservation (id),
    CONSTRAINT fk_reservation_activity_activity
    FOREIGN KEY (activity_id)
    REFERENCES Activity (id)
}