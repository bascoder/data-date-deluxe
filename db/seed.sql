BEGIN TRANSACTION;

-- required
INSERT INTO `Geslacht` (gid, geslacht)
VALUES (1, 'man'), (2, 'vrouw');

INSERT INTO `Foto` (fid, url, titel, beschrijving)
VALUES (1, 'assets/img/profiel_fotos/placeholder_male.svg', 'placeholder man', 'Ik heb nog geen profiel foto'),
  (2, 'assets/img/profiel_fotos/placeholder_female.svg', 'placeholder vrouw', 'Ik heb nog geen profiel foto');

INSERT INTO Persoonlijkheids_type (type)
VALUES ('Inspector'),
  ('Protector'),
  ('Counselor'),
  ('Mastermind'),
  ('Crafter'),
  ('Composer'),
  ('Healer'),
  ('Architect'),
  ('Promoter'),
  ('Performer'),
  ('Champion'),
  ('Inventor'),
  ('Supervisor'),
  ('Provider'),
  ('Teacher'),
  ('Fieldmarshal');

-- test data
INSERT INTO `Profiel` (voornaam, achternaam, email, password, is_admin, nickname, beschrijving, geboorte_datum,
                       leeftijd_voorkeur_min, leeftijd_voorkeur_max, valt_op_man, valt_op_vrouw, geslacht_id)
VALUES ('Bas', 'van Marwijk', 'bas@example.com', '$2y$10$LyzwMXTyXdRO7.9jTOlcaegIlZQuN5pqrAn4X9oNi4mV8rn0NauMq', 1,
               'bas1994', 'Pro admin', 782694000, 18, 25, 0, 1, 1),
  ('Henk', 'de Jong', 'henk@example.com', '$2y$10$LyzwMXTyXdRO7.9jTOlcaegIlZQuN5pqrAn4X9oNi4mV8rn0NauMq', 0,
           'henkie', '', 792694000, 18, 25, 0, 1, 1),
  ('Harry', 'de Witte', 'harry@example.com', '$2y$10$LyzwMXTyXdRO7.9jTOlcaegIlZQuN5pqrAn4X9oNi4mV8rn0NauMq', 0,
            'harry_ado', '', 785694000, 18, 25, 0, 1, 1),
  ('Gerrit', 'van Achter', 'gerrit@example.com', '$2y$10$LyzwMXTyXdRO7.9jTOlcaegIlZQuN5pqrAn4X9oNi4mV8rn0NauMq', 0,
             'gerrit_de_gekke', '', 682694000, 18, 25, 0, 1, 1),
  ('Tanja', 'de Rooije', 'tanja@gangster.com', '$2y$10$LyzwMXTyXdRO7.9jTOlcaegIlZQuN5pqrAn4X9oNi4mV8rn0NauMq', 0,
            'tanja', 'Hello you', 782594000, 18, 30, 1, 0, 2),
  ('Anita', 'Klein', 'anita@hotmail.com', '$2y$10$LyzwMXTyXdRO7.9jTOlcaegIlZQuN5pqrAn4X9oNi4mV8rn0NauMq', 0,
            'anita_xxx', 'xxx', 752594000, 18, 50, 1, 0, 2),
  ('Fred', 'De Gekke', 'freddie@hotmail.com', '$2y$10$LyzwMXTyXdRO7.9jTOlcaegIlZQuN5pqrAn4X9oNi4mV8rn0NauMq', 0,
           'freddie', '<3', 752594000, 18, 50, 1, 1, 1);

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
  (1, 6), (2, 6);

COMMIT;
