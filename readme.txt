# Auteurs
Bas van Marwijk 5836530
Jorrit Krapels 3810992

# URL
https://www.students.science.uu.nl/~5836530/data-date-deluxe/

# Browsers
Chrome 49
Firefox 45

# Extra's
- Genereer database als deze nog niet bestaat met DatabaseHook.php hook
- Laadt header en footer automatisch voor elke controller met ViewHook.php hook
- Gebruik SVG voor profiel foto placeholders,
    de placeholder voor female is 5,65kB en kan zonder kwaliteit verlies naar elke resolutie
  - Alle foto's krijgen een resolutie van 500x500px, en een thumbnail van 200x200px, waarbij de aspect ratio wordt behouden

# Structuur
assets/ bevat javascript en css sources.
db/ bevat de database en het script om de database te genereren.
upload/ bevat tijdelijke foto's die verwerkt worden door de Foto class.

# Login
email: bas@example.com
wachtwoord: *** TODO verander voor inleveren

# SQL definitie
Path: db/dml.sql
Of web URL: https://www.students.science.uu.nl/~5836530/data-date-deluxe/db/dml.sql
