# Auteurs
Bas van Marwijk 5836530
Jorrit Krapels 3810992

# URL
https://www.students.science.uu.nl/~5836530/data-date-deluxe/

# Browsers
- Chrome 49, Windows
- Firefox 45, Windows
- Microsoft Edge, Windows 10
- Internet Explorer 11, Windows 10

# Extra's
- Genereer database als deze nog niet bestaat met DatabaseHook.php hook
- Laadt header en footer automatisch voor elke controller met ViewHook.php hook
- Gebruik SVG voor profiel foto placeholders,
    de placeholder voor female is 5,65kB en kan zonder kwaliteit verlies naar elke resolutie geschaald worden
  - Alle foto's krijgen een resolutie van 500x500px, en een thumbnail van 200x200px, waarbij de aspect ratio wordt behouden
- Het navigatie menu is responsive op devices kleiner dan 1080px breed.

# Structuur
- assets/ bevat javascript en css sources. Daarnaast ook fonts voor de Image_lib van CodeIgniter, en plaatjes staan in /img
- db/ bevat de database en het script om de database te genereren.
- upload/ bevat tijdelijke foto's die verwerkt worden door de Foto class.
- core/DD_Security extend de Security class van CodeIgniter.

# Login
## Admin

email: bas@example.com
wachtwoord: *** TODO verander voor inleveren

## Users

email: fryslan@gmail
password: jaapdeaap

email: fauke@mensenteam.nl
password: faukelove

# SQL definitie
Path: db/dml.sql
Of web URL: https://www.students.science.uu.nl/~5836530/data-date-deluxe/db/dml.sql
Model: db/model.png
