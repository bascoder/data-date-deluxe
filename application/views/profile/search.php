<?php
echo form_open(base_url() . 'index.php/profile/lookup/search', array(
    'method' => 'get'
));
?>
<label for="geslacht-voorkeur">Geslacht voorkeur</label>
<select id="geslacht-voorkeur" name="geslacht_voorkeur" required>
    <option label="empty"></option>
    <option>Mannen</option>
    <option>Vrouwen</option>
    <option>Beide</option>
</select>
<br/>
<label>
    Leeftijd voorkeur
</label>
<input type="number" name="leeftijd_min" id="leeftijd-min" min="18" max="99" required placeholder="min"/>
<input type="number" name="leeftijd_max" id="leeftijd-max" min="18" max="99" required placeholder="max"/>

<br/>
<label>
    Persoonlijkheids voorkeur
    <input type="text" id="persoonlijkheids-voorkeur"
           name="persoonlijkheids_voorkeur"
           placeholder="Architect"
           required/>
</label>
<br/>
<div id="merk-voorkeuren">
    <label>Merk voorkeuren</label>
    <input type="text" placeholder="Apple" id="merk-voorkeur"/>
    <button type="button" id="btn-add-merk">Voeg toe</button>
    <input type="hidden" id="merken" name="merken"/>
</div>
<br/>
<?php
echo form_submit('', 'Zoek');
echo form_close();
?>

<script src="<?php echo asset_url() . 'js/profiel-search.js' ?>"></script>
