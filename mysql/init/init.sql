USE booking;

-- Tabela creditcard
CREATE TABLE creditcard (
  id INT NOT NULL AUTO_INCREMENT,
  number VARCHAR(16) NOT NULL,
  cvv VARCHAR(3) NOT NULL,
  expiry DATE NOT NULL,
  PRIMARY KEY (id)
);

INSERT INTO creditcard(number, cvv, expiry)
VALUES ('1234567891234567', '123', '10-10-2024'),
('1234567891234568', '124', '11-10-2024');

-- Tabela user
CREATE TABLE user (
  id INT NOT NULL AUTO_INCREMENT,
  firstname VARCHAR(45) NOT NULL,
  lastname VARCHAR(45) NOT NULL,
  email VARCHAR(45) NOT NULL UNIQUE,
  password VARCHAR(215) NOT NULL,
  isvendor BOOLEAN NOT NULL,
  PRIMARY KEY (id)
);

INSERT INTO user(firstname, lastname, email, password, isvendor)
VALUES ('Pedro','Paula','pedropaula@uac.pt','123', true),
('Lila','Karpowicz','lilakarpowicz@uac.pt','246', false);

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

INSERT INTO activity(name, description, date, cost, vendoruser_id, isarchived)
VALUES ('Bananas', 'Descascar', '01-01-2025', 15, 1, true),
('Maças', 'Descascar', '02-01-2025', 10, 1, false),
('Abacaxi', 'Cortar', '03-01-2025', 20, 2, false);

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

INSERT INTO comment(comment, activity_id, user_id, postedon)
VALUES ('Muito Bom', 1, 1, '05-02-2024'),
('Muito Mau', 1, 1, '05-02-2024'),
('Bom', 2, 2, '05-02-2024');

-- Tabela reservation_status
CREATE TABLE reservation_status (
  id INT NOT NULL AUTO_INCREMENT,
  name VARCHAR(15) NOT NULL UNIQUE,
  PRIMARY KEY (id)
);

INSERT INTO reservation_status(name)
VALUES ('Realized'),
('Posponed'),
('Cancel');

-- Tabela reservation
CREATE TABLE reservation (
  id INT NOT NULL AUTO_INCREMENT,
  reservedbyuser_id INT NOT NULL,
  reservedon DATETIME NOT NULL,
  activity_id INT NOT NULL,
  creditcard_id INT NOT NULL,
  reservationtatus_id INT NOT NULL,
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
    FOREIGN KEY (reservationtatus_id)
    REFERENCES reservation_status (id)
);

INSERT INTO reservation(reservedbyuser_id, reservedon, activity_id, creditcard_id, reservationtatus_id)
VALUES (1, '10-11-2024', 1, 1, 1),
(1, '10-11-2024', 2, 1, 3),
(2, '10-11-2024', 1, 2, 2);

-- Tabela user_creditcard
CREATE TABLE user_creditcard (
  user_id INT NOT NULL,
  creditcard_id INT NOT NULL,
  CONSTRAINT fk_user_creditcard_creditcard
    FOREIGN KEY (creditcard_id)
    REFERENCES creditcard (id),
  CONSTRAINT fk_user_creditcard_user
    FOREIGN KEY (user_id)
    REFERENCES user (id)
);

INSERT INTO user_creditcard(user_id, creditcard_id)
VALUES (1,1),
(2,2);

-- Tabela reservation_activity
CREATE TABLE reservation_activity (
  reservation_id INT NOT NULL,
  activity_id INT NOT NULL,
  CONSTRAINT fk_reservation_activity_reservation
    FOREIGN KEY (reservation_id)
    REFERENCES reservation (id),
  CONSTRAINT fk_reservation_activity_activity
    FOREIGN KEY (activity_id)
    REFERENCES activity (id)
);

INSERT INTO reservation_activity(reservation_id, activity_id)
VALUES (1,1),
(1,2),
(2,1);