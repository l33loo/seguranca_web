USE booking;

-- Tabela creditcard
CREATE TABLE IF NOT EXISTS creditcard (
  id INT NOT NULL AUTO_INCREMENT,
  number VARCHAR(16) NOT NULL,
  cvv VARCHAR(3) NOT NULL,
  expiry DATE NOT NULL,
  PRIMARY KEY (id)
);

INSERT INTO creditcard(id, number, cvv, expiry)
VALUES (1, '1234567891234567', '123', '2026-12-01'),
(2, '1234567891234568', '124', '2025-10-11');

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

-- Pedro's passwordhash: 123
-- Lila's passwordhash: 456
-- bcrypt without salt for now
INSERT INTO user(id, firstname, lastname, email, passwordhash, isvendor)
VALUES (1, 'Pedro','Paula','pedropaula@uac.pt','$2y$10$HkCd5wTbke4wG4wWh9t0pOLsDsYaDPAxS8JwW5cyZoUmQ4WiaJ9Y6', true),
(2, 'Lila','Karpowicz','lilakarpowicz@uac.pt','$2y$10$4GFUqTp1GUTlQxMeO4QCceqXLA75nr7g9k01MHJ6ZdnGS6K8gXgwq', false);

-- Tabela activity
CREATE TABLE IF NOT EXISTS activity (
  id INT NOT NULL AUTO_INCREMENT,
  name VARCHAR(45) NOT NULL,
  description VARCHAR(215) NOT NULL,
  date DATE NOT NULL,
  time TIME NOT NULL,
  cost FLOAT NOT NULL,
  vendoruser_id INT NOT NULL,
  isarchived BOOLEAN NOT NULL,
  PRIMARY KEY (id),
  CONSTRAINT fk_activity_vendoruser
    FOREIGN KEY (vendoruser_id)
    REFERENCES user (id)
);

INSERT INTO activity(id, name, description, date, time, cost, vendoruser_id, isarchived)
VALUES (1, 'Surfing at Santa Barbara Beach', 'Enjoy a surfing session with renowed local surf instructor. Duration 2 hours.', '2025-01-01', '15:00', 15, 1, true),
(2, 'Hike Lagoa do Fogo', "Half day hike around one of Sao Miguel's most beautiful lakes", '2024-10-04', '19:30', 10, 1, false),
(3, 'Learn to cook Cozido from Furnas', 'Chef Alberto will teach you how to cook Cozido Azorean style, in geothermal pits', '2024-12-13', '12:30', 20, 2, false),
(4, 'Learn to cook Cozido from Furnas', 'Chef Alberto will teach you how to cook Cozido Azorean style, in geothermal pits', '2024-11-13', '12:30', 20, 2, false),
(5, 'Surfing at Monte Verde Beach', 'Enjoy a surfing session with renowed local surf instructor. Duration 3 hours.', '2025-01-01', '15:00', 15, 1, false);

-- Tabela comment
CREATE TABLE IF NOT EXISTS comment (
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

INSERT INTO comment(id, comment, activity_id, user_id, postedon)
VALUES (1, 'Muito Bom', 2, 1, '2024-10-05 15:45:34'),
(2, 'Muito Mau', 2, 1, '2024-10-06 11:35:14'),
(3, 'Bom', 2, 2, '2024-10-05 07:05:01');

-- Tabela reservation_status
CREATE TABLE IF NOT EXISTS reservation_status (
  id INT NOT NULL AUTO_INCREMENT,
  name VARCHAR(15) NOT NULL UNIQUE,
  PRIMARY KEY (id)
);

INSERT INTO reservation_status(id, name)
VALUES (1, 'Scheduled'),
(2, 'Realized'),
(3, 'Postponed'),
(4, 'Cancelled');

-- Tabela reservation
CREATE TABLE IF NOT EXISTS reservation (
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

INSERT INTO reservation(reservedbyuser_id, reservedon, activity_id, creditcard_id, reservationstatus_id)
VALUES (1, '2024-10-15 20:55:04', 1, 1, 1),
(1, '2024-10-20 07:05:22', 2, 1, 3),
(2, '2024-09-22 17:44:31', 1, 2, 2);

-- Tabela user_creditcard
CREATE TABLE IF NOT EXISTS user_creditcard (
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

INSERT INTO reservation_activity(reservation_id, activity_id)
VALUES (1,1),
(1,2),
(2,1);