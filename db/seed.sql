BEGIN TRANSACTION;

-- required
INSERT INTO `Geslacht` (gid, geslacht)
VALUES (1, 'man'), (2, 'vrouw');

-- fid van placeholders zijn gelijk aan gid van Geslacht, hier wordt op gerekend door de applicatie
INSERT INTO `Foto` (fid, url, titel, beschrijving)
VALUES (1, 'assets/img/profiel_fotos/placeholder_male.svg', 'placeholder man', 'Ik heb nog geen profiel foto'),
  (2, 'assets/img/profiel_fotos/placeholder_female.svg', 'placeholder vrouw', 'Ik heb nog geen profiel foto');

INSERT INTO Persoonlijkheids_categorie (pcid, type, name)
VALUES (1, 'ISTJ', 'Inspector'),
  (2, 'ISFJ', 'Protector'),
  (3, 'INFJ', 'Counselor'),
  (4, 'INTJ', 'Mastermind'),
  (5, 'ISTP', 'Crafter'),
  (6, 'ISFP', 'Composer'),
  (7, 'INFP', 'Healer'),
  (8, 'INTP', 'Architect'),
  (9, 'ESTP', 'Promoter'),
  (10, 'ESFP', 'Performer'),
  (11, 'ENFP', 'Champion'),
  (12, 'ENTP', 'Inventor'),
  (13, 'ESTJ', 'Supervisor'),
  (14, 'ESFJ', 'Provider'),
  (15, 'ENFJ', 'Teacher'),
  (16, 'ENTJ', 'Fieldmarshal');

-- test data
-- OR IGNORE clause zorgt ervoor dat rows die niet aan constraints voldoen (door random) overgeslagen worden
INSERT OR IGNORE INTO `Profiel` (pid, voornaam, achternaam, email, password, is_admin, nickname, beschrijving, geboorte_datum,
                                 leeftijd_voorkeur_min, leeftijd_voorkeur_max, valt_op_man, valt_op_vrouw, geslacht_id)
VALUES (1, 'Bas', 'van Marwijk', 'bas@example.com', '$2y$10$hL0wVNcja/nS3us93I4fau/4RKiL6whA7P73Uny6HRNESEP3odRBK', 1,
           'bas1994', 'Pro admin', 782694000, 18, 25, 0, 1, 1),
  -- email: bas@example.com password: ***
  (39, 'Jaap', 'Boersma', 'fryslan@gmail.com', '$2y$10$3uiEVN51Bk5NArXR3nunQe2YKQyVZngznZQEm5iG1F5m2kgiis.CC',
       0, 'jaapfryslan', '', '631148400', 18, 40, 0, 1, 1),
  -- email: fryslan@gmail, password: jaapdeaap
  (40, 'Fauke', 'Smith', 'fauke@mensenteam.nl', '$2y$10$mdxZy67oN4ScO7xAgCPqAeeEhkM0ixPGqbnApxhgrn1m.Wb92Tt8S', 0, 'faukie', '', '797032800', 18, 30, 1, 0, 2),
  -- email: fauke@mensenteam.nl, password: faukelove
  (2, 'Henk', 'de Jong', 'henk@example.com', '$2y$10$LyzwMXTyXdRO7.9jTOlcaegIlZQuN5pqrAn4X9oNi4mV8rn0NauMq', 0,
      'henkie', '', 792694000, 18, 25, 0, 1, 1),
  (3, 'Harry', 'de Witte', 'harry@example.com', '$2y$10$LyzwMXTyXdRO7.9jTOlcaegIlZQuN5pqrAn4X9oNi4mV8rn0NauMq', 0,
      'harry_ado', '', 785694000, 18, 25, 0, 1, 1),
  (4, 'Gerrit', 'van Achter', 'gerrit@example.com', '$2y$10$LyzwMXTyXdRO7.9jTOlcaegIlZQuN5pqrAn4X9oNi4mV8rn0NauMq', 0,
      'gerrit_de_gekke', '', 682694000, 18, 25, 0, 1, 1),
  (5, 'Tanja', 'de Rooije', 'tanja@gmail.com', '$2y$10$LyzwMXTyXdRO7.9jTOlcaegIlZQuN5pqrAn4X9oNi4mV8rn0NauMq', 0,
      'tanja', 'Hello you', 782594000, 18, 30, 1, 0, 2),
  (6, 'Anita', 'Klein', 'anita1@hotmail.com', '$2y$10$LyzwMXTyXdRO7.9jTOlcaegIlZQuN5pqrAn4X9oNi4mV8rn0NauMq', 0,
      'anita_xxx', 'xxx', 752594000, 18, 55, 1, 0, 2),
  (7, 'Fred', 'De Gekke', 'freddie@hotmail.com', '$2y$10$LyzwMXTyXdRO7.9jTOlcaegIlZQuN5pqrAn4X9oNi4mV8rn0NauMq', 0,
      'freddiegek', '<3', 752594000, 23, 50, 1, 1, 1),
  (8, 'Anita', 'uit Fryslan', 'anita@hotmail.com', '$2y$10$LyzwMXTyXdRO7.9jTOlcaegIlZQuN5pqrAn4X9oNi4mV8rn0NauMq', 0,
      'anitatjee', '<3', 753594000, 20, 50, 1, 1, 1),
  (9, 'Ton', 'uit Soest', 'tonsoest@hotmail.com', '$2y$10$LyzwMXTyXdRO7.9jTOlcaegIlZQuN5pqrAn4X9oNi4mV8rn0NauMq', 0,
      'tonnie', '<3', 752694000, 18, 30, 1, 1, 1),
  (10, 'Timmie', 'Hengel', 'timmie@hotmail.com', '$2y$10$LyzwMXTyXdRO7.9jTOlcaegIlZQuN5pqrAn4X9oNi4mV8rn0NauMq', 0,
       'tim3', '<3', 732694000, ABS(RANDOM() % 5) + 18, ABS(RANDOM() % 5) + 27, ABS(RANDOM() % 1), 1,
   ABS(random() % 2) + 1),
  (11, 'Tijs', 'Hengel', 'tijs@hotmail.com', '$2y$10$LyzwMXTyXdRO7.9jTOlcaegIlZQuN5pqrAn4X9oNi4mV8rn0NauMq', 0,
       'tisjeboy', '<3', 732694000, ABS(RANDOM() % 5) + 18, ABS(RANDOM() % 5) + 27, ABS(RANDOM() % 1), 1,
   ABS(random() % 2) + 1),
  (12, 'Eva', 'Hengel', 'eva@hotmail.com', '$2y$10$LyzwMXTyXdRO7.9jTOlcaegIlZQuN5pqrAn4X9oNi4mV8rn0NauMq', 0,
       'evie', '<3', 732694000, ABS(RANDOM() % 5) + 18, ABS(RANDOM() % 5) + 27, ABS(RANDOM() % 1), 1,
   ABS(random() % 2) + 1),
  (13, 'Jarry', 'De Jong', 'jarry@hotmail.com', '$2y$10$LyzwMXTyXdRO7.9jTOlcaegIlZQuN5pqrAn4X9oNi4mV8rn0NauMq', 0,
       'jarryjong', '<3', 732694000, ABS(RANDOM() % 5) + 18, ABS(RANDOM() % 5) + 27, 1,
   1,
   ABS(random() % 2) + 1),
  (14, 'Rosanna', 'Hengel', 'rosanna@hotmail.com', '$2y$10$LyzwMXTyXdRO7.9jTOlcaegIlZQuN5pqrAn4X9oNi4mV8rn0NauMq', 0,
       'roos<3', '<3', 732694000, ABS(RANDOM() % 5) + 18, ABS(RANDOM() % 5) + 27, 1, 1,
   ABS(random() % 2) + 1),
  (15, 'Loes', 'de Poes', 'loes@hotmail.com', '$2y$10$LyzwMXTyXdRO7.9jTOlcaegIlZQuN5pqrAn4X9oNi4mV8rn0NauMq', 0,
       'LoesPoes', '<3', 732694000, ABS(RANDOM() % 5) + 18, ABS(RANDOM() % 5) + 27, 1,
   ABS(RANDOM() % 1),
   ABS(random() % 2) + 1),
  (16, 'Minoes', 'het Hengel', 'minoes@hotmail.com', '$2y$10$LyzwMXTyXdRO7.9jTOlcaegIlZQuN5pqrAn4X9oNi4mV8rn0NauMq', 0,
       'freddie', '<3', 732694000, ABS(RANDOM() % 5) + 18, ABS(RANDOM() % 5) + 27, 1, ABS(RANDOM() % 1),
   ABS(random() % 2) + 1),
  (17, 'Anna', 'uit Hogeveen', 'anna@hotmail.com', '$2y$10$LyzwMXTyXdRO7.9jTOlcaegIlZQuN5pqrAn4X9oNi4mV8rn0NauMq', 0,
       'an', '<3', 732694000, ABS(RANDOM() % 5) + 18, ABS(RANDOM() % 5) + 27, ABS(RANDOM() % 1), 1,
   ABS(random() % 2) + 1),
  (18, 'Tim', 'Hengel', 'tim@gmail.com', '$2y$10$LyzwMXTyXdRO7.9jTOlcaegIlZQuN5pqrAn4X9oNi4mV8rn0NauMq', 0,
       'timmetje', '<3', 732694000, ABS(RANDOM() % 5) + 18, ABS(RANDOM() % 5) + 27, 1,
   ABS(RANDOM() % 1),
   ABS(random() % 2) + 1),
  (19, 'Nadia', 'Iets', 'nadia@hotmail.com', '$2y$10$LyzwMXTyXdRO7.9jTOlcaegIlZQuN5pqrAn4X9oNi4mV8rn0NauMq', 0,
       'nadia', '<3', 732694000, ABS(RANDOM() % 5) + 18, ABS(RANDOM() % 5) + 27, 1, ABS(RANDOM() % 1),
   ABS(random() % 2) + 1);

INSERT INTO "Merk" (mid, naam) VALUES (1, 'Apple');
INSERT INTO "Merk" (mid, naam) VALUES (2, 'Microsoft');
INSERT INTO "Merk" (mid, naam) VALUES (3, 'Google');
INSERT INTO "Merk" (mid, naam) VALUES (4, 'Coca-Cola');
INSERT INTO "Merk" (mid, naam) VALUES (5, 'IBM');
INSERT INTO "Merk" (mid, naam) VALUES (6, 'McDonald''s');
INSERT INTO "Merk" (mid, naam) VALUES (7, 'Samsung');
INSERT INTO "Merk" (mid, naam) VALUES (8, 'Toyota');
INSERT INTO "Merk" (mid, naam) VALUES (9, 'Trojan');
INSERT INTO "Merk" (mid, naam) VALUES (10, 'Facebook');
INSERT INTO "Merk" (mid, naam) VALUES (11, 'Disney');
INSERT INTO "Merk" (mid, naam) VALUES (12, 'AT&T');
INSERT INTO "Merk" (mid, naam) VALUES (13, 'Amazon.com');
INSERT INTO "Merk" (mid, naam) VALUES (14, 'Louis Vuitton');
INSERT INTO "Merk" (mid, naam) VALUES (15, 'Cisco');
INSERT INTO "Merk" (mid, naam) VALUES (16, 'BMW');
INSERT INTO "Merk" (mid, naam) VALUES (17, 'Durex');
INSERT INTO "Merk" (mid, naam) VALUES (18, 'NIKE');
INSERT INTO "Merk" (mid, naam) VALUES (19, 'Intel');
INSERT INTO "Merk" (mid, naam) VALUES (20, 'Wal-Mart');
INSERT INTO "Merk" (mid, naam) VALUES (21, 'Verizon');
INSERT INTO "Merk" (mid, naam) VALUES (22, 'American Express');
INSERT INTO "Merk" (mid, naam) VALUES (23, 'Honda');
INSERT INTO "Merk" (mid, naam) VALUES (24, 'Mercedes-Benz');
INSERT INTO "Merk" (mid, naam) VALUES (25, 'Budweiser');
INSERT INTO "Merk" (mid, naam) VALUES (26, 'Gillette');
INSERT INTO "Merk" (mid, naam) VALUES (27, 'Marlboro');
INSERT INTO "Merk" (mid, naam) VALUES (28, 'Pepsi');
INSERT INTO "Merk" (mid, naam) VALUES (29, 'Visa');
INSERT INTO "Merk" (mid, naam) VALUES (30, 'Nescafe');
INSERT INTO "Merk" (mid, naam) VALUES (31, 'ESPN');
INSERT INTO "Merk" (mid, naam) VALUES (32, 'H&M');
INSERT INTO "Merk" (mid, naam) VALUES (33, 'L''Or├®al');
INSERT INTO "Merk" (mid, naam) VALUES (34, 'Hewlett-Packard');
INSERT INTO "Merk" (mid, naam) VALUES (35, 'HSBC');
INSERT INTO "Merk" (mid, naam) VALUES (36, 'Home Depot');
INSERT INTO "Merk" (mid, naam) VALUES (37, 'Frito-Lay');
INSERT INTO "Merk" (mid, naam) VALUES (38, 'Audi');
INSERT INTO "Merk" (mid, naam) VALUES (39, 'UPS');
INSERT INTO "Merk" (mid, naam) VALUES (40, 'Starbucks');
INSERT INTO "Merk" (mid, naam) VALUES (41, 'Ford');
INSERT INTO "Merk" (mid, naam) VALUES (42, 'Gucci');
INSERT INTO "Merk" (mid, naam) VALUES (43, 'Nestle');
INSERT INTO "Merk" (mid, naam) VALUES (44, 'Accenture');
INSERT INTO "Merk" (mid, naam) VALUES (45, 'IKEA');
INSERT INTO "Merk" (mid, naam) VALUES (46, 'Siemens');
INSERT INTO "Merk" (mid, naam) VALUES (47, 'Wells Fargo');
INSERT INTO "Merk" (mid, naam) VALUES (48, 'Fox');
INSERT INTO "Merk" (mid, naam) VALUES (49, 'Pampers');
INSERT INTO "Merk" (mid, naam) VALUES (50, 'Ebay');

-- OR IGNORE clause zorgt ervoor dat rows die niet aan constraints voldoen (door random) overgeslagen worden
INSERT OR IGNORE INTO Merk_voorkeur (merk_id, profiel_id)
VALUES (4, 5), (1, 5),
  (1, 6), (2, 6),
  (ABS(RANDOM() % 49) + 1, ABS(RANDOM() % 18) + 1),
  (ABS(RANDOM() % 49) + 1, ABS(RANDOM() % 18) + 1),
  (ABS(RANDOM() % 49) + 1, ABS(RANDOM() % 18) + 1),
  (ABS(RANDOM() % 49) + 1, ABS(RANDOM() % 18) + 1),
  (ABS(RANDOM() % 49) + 1, ABS(RANDOM() % 18) + 1),
  (ABS(RANDOM() % 49) + 1, ABS(RANDOM() % 18) + 1),
  (ABS(RANDOM() % 49) + 1, ABS(RANDOM() % 18) + 1),
  (ABS(RANDOM() % 49) + 1, ABS(RANDOM() % 18) + 1),
  (ABS(RANDOM() % 49) + 1, ABS(RANDOM() % 18) + 1),
  (ABS(RANDOM() % 49) + 1, ABS(RANDOM() % 18) + 1),
  (ABS(RANDOM() % 49) + 1, ABS(RANDOM() % 18) + 1),
  (ABS(RANDOM() % 49) + 1, ABS(RANDOM() % 18) + 1),
  (ABS(RANDOM() % 49) + 1, ABS(RANDOM() % 18) + 1),
  (ABS(RANDOM() % 49) + 1, ABS(RANDOM() % 18) + 1),
  (ABS(RANDOM() % 49) + 1, ABS(RANDOM() % 18) + 1),
  (ABS(RANDOM() % 49) + 1, ABS(RANDOM() % 18) + 1),
  (ABS(RANDOM() % 49) + 1, ABS(RANDOM() % 18) + 1),
  (ABS(RANDOM() % 49) + 1, ABS(RANDOM() % 18) + 1),
  (ABS(RANDOM() % 49) + 1, ABS(RANDOM() % 18) + 1),
  (ABS(RANDOM() % 49) + 1, ABS(RANDOM() % 18) + 1),
  (ABS(RANDOM() % 49) + 1, ABS(RANDOM() % 18) + 1);

UPDATE OR IGNORE Profiel
SET persoonlijkheids_type_id = NULL;

UPDATE OR IGNORE Profiel
SET persoonlijkheids_type_voorkeur_id = NULL;

INSERT OR IGNORE INTO Like (liker_id, liked_id)
VALUES ((ABS(RANDOM() % 18) + 1), (ABS(RANDOM() % 18) + 1)),
  ((ABS(RANDOM() % 18) + 1), (ABS(RANDOM() % 18) + 1)),
  ((ABS(RANDOM() % 18) + 1), (ABS(RANDOM() % 18) + 1)),
  ((ABS(RANDOM() % 18) + 1), (ABS(RANDOM() % 18) + 1)),
  ((ABS(RANDOM() % 18) + 1), (ABS(RANDOM() % 18) + 1)),
  ((ABS(RANDOM() % 18) + 1), (ABS(RANDOM() % 18) + 1)),
  ((ABS(RANDOM() % 18) + 1), (ABS(RANDOM() % 18) + 1)),
  ((ABS(RANDOM() % 18) + 1), (ABS(RANDOM() % 18) + 1)),
  ((ABS(RANDOM() % 18) + 1), (ABS(RANDOM() % 18) + 1)),
  ((ABS(RANDOM() % 18) + 1), (ABS(RANDOM() % 18) + 1)),
  ((ABS(RANDOM() % 18) + 1), (ABS(RANDOM() % 18) + 1)),
  ((ABS(RANDOM() % 18) + 1), (ABS(RANDOM() % 18) + 1)),
  ((ABS(RANDOM() % 18) + 1), (ABS(RANDOM() % 18) + 1));

COMMIT;
