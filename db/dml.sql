BEGIN TRANSACTION;

-- table definitions
DROP TABLE IF EXISTS Profiel;
CREATE TABLE Profiel (
  pid                   INTEGER PRIMARY KEY   AUTOINCREMENT,
  voornaam              VARCHAR(255) NOT NULL,
  achternaam            VARCHAR(255) NOT NULL,
  email                 VARCHAR(255) NOT NULL UNIQUE,
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
  CHECK (valt_op_man = 1 OR valt_op_vrouw = 1),
  CONSTRAINT chk_leeftijd_voorkeur
  CHECK (leeftijd_voorkeur_min >= 18 AND leeftijd_voorkeur_max >= leeftijd_voorkeur_min)
);

DROP TABLE IF EXISTS Merk;
CREATE TABLE Merk (
  mid  INTEGER PRIMARY KEY AUTOINCREMENT,
  naam VARCHAR(255) NOT NULL UNIQUE
);

DROP TABLE IF EXISTS Persoonlijkheids_categorie;
CREATE TABLE Persoonlijkheids_categorie (
  pcid INTEGER PRIMARY KEY AUTOINCREMENT,
  type CHARACTER(4) NOT NULL UNIQUE,
  name VARCHAR(50)  NOT  NULL UNIQUE
);

DROP TABLE IF EXISTS Persoonlijkheids_type;
CREATE TABLE Persoonlijkheids_type (
  ptid INTEGER PRIMARY KEY AUTOINCREMENT,
  pcid INTEGER DEFAULT NULL,
  eType INTEGER DEFAULT 50,
  nType INTEGER DEFAULT 50,
  tType INTEGER DEFAULT 50,
  jType INTEGER DEFAULT 50,
  FOREIGN KEY (pcid) REFERENCES Persoonlijkheids_categorie (pcid)
);

DROP TABLE IF EXISTS Foto;
CREATE TABLE Foto (
  fid          INTEGER PRIMARY KEY AUTOINCREMENT,
  url          VARCHAR(255) NOT NULL,
  titel        VARCHAR(255) NOT NULL,
  beschrijving VARCHAR(1024)       DEFAULT NULL,
  profiel_id   INTEGER             DEFAULT NULL,
  FOREIGN KEY (profiel_id) REFERENCES Profiel (pid)
);

DROP TABLE IF EXISTS Geslacht;
CREATE TABLE Geslacht (
  gid      INTEGER PRIMARY KEY AUTOINCREMENT,
  geslacht CHARACTER(50) NOT NULL UNIQUE
);

DROP TABLE IF EXISTS `Like`;
CREATE TABLE `Like` (
  liker_id INTEGER NOT NULL,
  liked_id INTEGER NOT NULL,
  PRIMARY KEY (liker_id, liked_id),
  FOREIGN KEY (liker_id) REFERENCES Profiel (pid),
  FOREIGN KEY (liked_id) REFERENCES Profiel (pid)
);

DROP TABLE IF EXISTS Merk_voorkeur;
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

ALTER TABLE Profiel
ADD COLUMN persoonlijkheids_type_id INTEGER REFERENCES Persoonlijkheids_type (ptid);

ALTER TABLE Profiel
ADD COLUMN persoonlijkheids_type_voorkeur_id INTEGER REFERENCES Persoonlijkheids_type (ptid);

-- triggers
DROP TRIGGER IF EXISTS assign_profile_picture;
CREATE TRIGGER IF NOT EXISTS assign_profile_picture
AFTER INSERT ON `Profiel`
FOR EACH ROW
BEGIN
  -- geslacht id 1 is man, foto id 1 is man placeholder
  UPDATE Profiel
  SET profiel_foto_id = 1
  WHERE profiel_foto_id IS NULL AND geslacht_id = 1;

  -- geslacht id 2 is vrouw, foto id 2 is vrouw placeholder
  UPDATE Profiel
  SET profiel_foto_id = 2
  WHERE profiel_foto_id IS NULL AND geslacht_id = 2;
END;

COMMIT;
