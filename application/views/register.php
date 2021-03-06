<div style="display: none" class="message message-error" id="error"></div>
<form id="register-form" class="center auth auth-container" action="register/submit" method="post">
    <label for="voornaam">Voornaam</label><br/>
    <input required id="voornaam" name="voornaam" placeholder="Jan" type="text"/><br/>
    <label for="achternaam">Achternaam</label><br/>
    <input required id="achternaam" name="achternaam" placeholder="De Jong" type="text"/><br/>
    <label for="mail">Email adres</label><br/>
    <input required id="mail" class="email" name="mail" placeholder="jan@example.com" type="email"/><br/>
    <label>Geslacht<br/>
        <input required type="radio" name="gender" value="1" title="man"/> Man
        <input required type="radio" name="gender" value="2" title="vrouw"/> Vrouw
    </label>
    <br/>
    <label for="geboorte-datum">Geboortedatum</label>
    <input type="date" value="<?php echo date("Y") - 17; ?>-01-01" id="geboorte-datum" name="geboorte_datum" aria-valuemin="De gebruiker moet ouder zijn dan 18"/>
    <br/>
    <hr/>
    <label>
        Leeftijd voorkeur
        <input type="number" name="leeftijd_voorkeur_min" min="18"
               max="99" class="short-field" placeholder="min" required/>
        <input type="number" name="leeftijd_voorkeur_max" min="18"
               max="99" class="short-field" placeholder="max" required/>
    </label>
    <br/>
    <label for="sex-preference">Geïnteresseerd in</label>
    <select id="sex-preference" name="sex_preference" required>
        <option value ="m">mannen</option>
        <option value ="v">vrouwen</option>
        <option value ="bi">mannen en vrouwen</option>
    </select>
    <br />
    <hr/>
    <label for="nickname">Nickname</label><br/>
    <input type="text" name="nickname" id="nickname" placeholder="Make it sexy..." required/><br/>
    <label for="password">Wachtwoord</label><br/>
    <input type="password" name="password" id="password" placeholder="St3rKW@cHTW0oRD" minlength="8" required/><br/>
    <input type="hidden" name="hash" />
    <input type="submit" value="Registreer"/>
</form>
<script src="<?php echo asset_url() . 'js/register.js' ?>"></script>
<script src="<?php echo asset_url() . 'js/sha1.js' ?>"></script>
<script src="<?php echo asset_url() . 'js/auth.js' ?>"></script>
