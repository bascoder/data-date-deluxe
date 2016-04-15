BEGIN TRANSACTION;

-- required
INSERT INTO `Geslacht` (gid, geslacht)
VALUES (1, 'man'), (2, 'vrouw');

INSERT INTO `Foto` (fid, url, titel, beschrijving)
VALUES (1, 'assets/img/profiel_fotos/placeholder_male.svg', 'placeholder man', 'Ik heb nog geen profiel foto'),
  (2, 'assets/img/profiel_fotos/placeholder_female.svg', 'placeholder vrouw', 'Ik heb nog geen profiel foto');

INSERT INTO Persoonlijkheids_type (ptid, type, name)
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
INSERT INTO `Profiel` (pid, voornaam, achternaam, email, password, is_admin, nickname, beschrijving, geboorte_datum,
                       leeftijd_voorkeur_min, leeftijd_voorkeur_max, valt_op_man, valt_op_vrouw, geslacht_id)
VALUES (1, 'Bas', 'van Marwijk', 'bas@example.com', '$2y$10$LyzwMXTyXdRO7.9jTOlcaegIlZQuN5pqrAn4X9oNi4mV8rn0NauMq', 1,
           'bas1994', 'Pro admin', 782694000, 18, 25, 0, 1, 1),
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
       'tim3', '<3', 732694000, ABS(RANDOM() % 5) + 18, ABS(RANDOM() % 5) + 27, ABS(RANDOM() % 1), 1, ABS(
       RANDOM() % 1)),
  (11, 'Tijs', 'Hengel', 'tijs@hotmail.com', '$2y$10$LyzwMXTyXdRO7.9jTOlcaegIlZQuN5pqrAn4X9oNi4mV8rn0NauMq', 0,
       'tisjeboy', '<3', 732694000, ABS(RANDOM() % 5) + 18, ABS(RANDOM() % 5) + 27, ABS(RANDOM() % 1), 1, ABS(
       RANDOM() % 1)),
  (12, 'Eva', 'Hengel', 'eva@hotmail.com', '$2y$10$LyzwMXTyXdRO7.9jTOlcaegIlZQuN5pqrAn4X9oNi4mV8rn0NauMq', 0,
       'evie', '<3', 732694000, ABS(RANDOM() % 5) + 18, ABS(RANDOM() % 5) + 27, ABS(RANDOM() % 1), 1,
   ABS(RANDOM() % 1)),
  (13, 'Jarry', 'De Jong', 'jarry@hotmail.com', '$2y$10$LyzwMXTyXdRO7.9jTOlcaegIlZQuN5pqrAn4X9oNi4mV8rn0NauMq', 0,
       'jarryjong', '<3', 732694000, ABS(RANDOM() % 5) + 18, ABS(RANDOM() % 5) + 27, 1,
   1,
   ABS(RANDOM() % 1)),
  (14, 'Rosanna', 'Hengel', 'rosanna@hotmail.com', '$2y$10$LyzwMXTyXdRO7.9jTOlcaegIlZQuN5pqrAn4X9oNi4mV8rn0NauMq', 0,
       'roos<3', '<3', 732694000, ABS(RANDOM() % 5) + 18, ABS(RANDOM() % 5) + 27, 1, 1,
   ABS(RANDOM() % 1)),
  (15, 'Loes', 'de Poes', 'loes@hotmail.com', '$2y$10$LyzwMXTyXdRO7.9jTOlcaegIlZQuN5pqrAn4X9oNi4mV8rn0NauMq', 0,
       'LoesPoes', '<3', 732694000, ABS(RANDOM() % 5) + 18, ABS(RANDOM() % 5) + 27, 1,
   ABS(RANDOM() % 1),
   ABS(RANDOM() % 1)),
  (16, 'Minoes', 'het Hengel', 'minoes@hotmail.com', '$2y$10$LyzwMXTyXdRO7.9jTOlcaegIlZQuN5pqrAn4X9oNi4mV8rn0NauMq', 0,
       'freddie', '<3', 732694000, ABS(RANDOM() % 5) + 18, ABS(RANDOM() % 5) + 27, 1, ABS(RANDOM() % 1),
   ABS(RANDOM() % 1)),
  (17, 'Anna', 'uit Hogeveen', 'anna@hotmail.com', '$2y$10$LyzwMXTyXdRO7.9jTOlcaegIlZQuN5pqrAn4X9oNi4mV8rn0NauMq', 0,
       'an', '<3', 732694000, ABS(RANDOM() % 5) + 18, ABS(RANDOM() % 5) + 27, ABS(RANDOM() % 1), 1,
   ABS(RANDOM() % 1)),
  (18, 'Tim', 'Hengel', 'tim@gmail.com', '$2y$10$LyzwMXTyXdRO7.9jTOlcaegIlZQuN5pqrAn4X9oNi4mV8rn0NauMq', 0,
       'timmetje', '<3', 732694000, ABS(RANDOM() % 5) + 18, ABS(RANDOM() % 5) + 27, 1,
   ABS(RANDOM() % 1),
   ABS(RANDOM() % 1)),
  (19, 'Nadia', 'Iets', 'nadia@hotmail.com', '$2y$10$LyzwMXTyXdRO7.9jTOlcaegIlZQuN5pqrAn4X9oNi4mV8rn0NauMq', 0,
       'nadia', '<3', 732694000, ABS(RANDOM() % 5) + 18, ABS(RANDOM() % 5) + 27, 1, ABS(RANDOM() % 1),
   ABS(RANDOM() % 1));

INSERT INTO `Merk` (naam)
VALUES
  ('Apple'),
  ('Microsoft'),
  ('Google'),
  ('Coca-Cola'),
  ('IBM'),
  ('McDonald''s'),
  ('Samsung'),
  ('Toyota'),
  ('General Electric'),
  ('Facebook'),
  ('Disney'),
  ('AT&T'),
  ('Amazon.com'),
  ('Louis Vuitton'),
  ('Cisco'),
  ('BMW'),
  ('Oracle'),
  ('NIKE'),
  ('Intel'),
  ('Wal-Mart'),
  ('Verizon'),
  ('American Express'),
  ('Honda'),
  ('Mercedes-Benz'),
  ('Budweiser'),
  ('Gillette'),
  ('Marlboro'),
  ('Pepsi'),
  ('Visa'),
  ('Nescafe'),
  ('ESPN'),
  ('H&M'),
  ('L''Oréal'),
  ('Hewlett-Packard'),
  ('HSBC'),
  ('Home Depot'),
  ('Frito-Lay'),
  ('Audi'),
  ('UPS'),
  ('Starbucks'),
  ('Ford'),
  ('Gucci'),
  ('Nestle'),
  ('Accenture'),
  ('IKEA'),
  ('Siemens'),
  ('Wells Fargo'),
  ('Fox'),
  ('Pampers'),
  ('Ebay');

INSERT INTO Merk_voorkeur (merk_id, profiel_id)
VALUES (4, 5), (1, 5),
  (1, 6), (2, 6),
  (ABS(RANDOM() % 50), ABS(RANDOM() % 7));

UPDATE Profiel
SET persoonlijkheids_type_id = ABS(RANDOM() % 16);

COMMIT;
