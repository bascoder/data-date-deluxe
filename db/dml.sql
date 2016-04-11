BEGIN TRANSACTION;

-- table definitions
CREATE TABLE Profiel (
  pid                   INTEGER PRIMARY KEY   AUTOINCREMENT,
  voornaam              VARCHAR(255) NOT NULL,
  achternaam            VARCHAR(255) NOT NULL,
  email                 VARCHAR(255) NOT NULL,
  password              VARCHAR(255) NOT NULL,
  is_admin              BOOLEAN      NOT NULL DEFAULT 0,
  nickname              VARCHAR(255) NOT NULL UNIQUE,
  beschrijving          TEXT         NOT NULL DEFAULT '',
  geboorte_datum        DATE         NOT NULL,
  leeftijd_voorkeur_min INTEGER      NOT NULL,
  leeftijd_voorkeur_max INTEGER      NOT NULL,
  valt_op_man           BOOLEAN               DEFAULT 0,
  valt_op_vrouw         BOOLEAN               DEFAULT 0,

  CONSTRAINT chk_ouder_dan_18
  CHECK (geboorte_datum <= (strftime('%s', 'now') - 568024668)),
  CONSTRAINT chk_valt_op
  CHECK (valt_op_man = 1 OR valt_op_vrouw = 1)
);

CREATE TABLE Merk (
  mid  INTEGER PRIMARY KEY AUTOINCREMENT,
  naam VARCHAR(255) NOT NULL UNIQUE
);

CREATE TABLE Persoonlijkheids_type (
  ptid INTEGER PRIMARY KEY AUTOINCREMENT,
  type CHARACTER(4) NOT NULL UNIQUE
);

CREATE TABLE Foto (
  fid          INTEGER PRIMARY KEY AUTOINCREMENT,
  url          VARCHAR(255) NOT NULL,
  titel        VARCHAR(255) NOT NULL,
  beschrijving VARCHAR(1024)       DEFAULT NULL,
  profiel_id   INTEGER             DEFAULT NULL,
  FOREIGN KEY (profiel_id) REFERENCES Profiel (pid)
);

CREATE TABLE Geslacht (
  gid      INTEGER PRIMARY KEY AUTOINCREMENT,
  geslacht CHARACTER(50) UNIQUE
);

CREATE TABLE `Like` (
  liker_id INTEGER NOT NULL,
  liked_id INTEGER NOT NULL,
  PRIMARY KEY (liker_id, liked_id),
  FOREIGN KEY (liker_id) REFERENCES Profiel (pid),
  FOREIGN KEY (liked_id) REFERENCES Profiel (pid)
);

CREATE TABLE Persoonlijkheids_type_voorkeur (
  profiel_id               INTEGER NOT NULL,
  persoonlijkheids_type_id INTEGER NOT NULL,
  PRIMARY KEY (profiel_id, persoonlijkheids_type_id),
  FOREIGN KEY (profiel_id) REFERENCES Profiel (profiel_id),
  FOREIGN KEY (persoonlijkheids_type_id) REFERENCES Persoonlijkheids_type (ptid)
);

CREATE TABLE Merk_voorkeur (
  merk_id    INTEGER NOT NULL,
  profiel_id INTEGER NOT NULL,
  PRIMARY KEY (merk_id, profiel_id),
  FOREIGN KEY (merk_id) REFERENCES Merk (mid),
  FOREIGN KEY (profiel_id) REFERENCES Profiel (pid)
);

-- extra foreign keys
ALTER TABLE Profiel
ADD COLUMN geslacht_id INTEGER REFERENCES Geslacht (gid);

ALTER TABLE Profiel
ADD COLUMN profiel_foto_id INTEGER REFERENCES Foto (fid);

COMMIT;
