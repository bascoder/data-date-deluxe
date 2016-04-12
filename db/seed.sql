BEGIN TRANSACTION;

-- required
INSERT INTO `Geslacht` (gid, geslacht)
VALUES (1, 'man'), (2, 'vrouw');

INSERT INTO `Foto`(fid, url, titel, beschrijving)
VALUES (1, 'assets/img/profiel_fotos/placeholder_female.svg', 'placeholder man', 'Ik heb nog geen profiel foto'),
  (2, 'assets/img/profiel_fotos/placeholder_male.svg', 'placeholder vrouw', 'Ik heb nog geen profiel foto');

COMMIT;
