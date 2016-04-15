<?php
echo form_open(base_url() . 'index.php/profile/lookup/search', array(
    'method' => 'get'
));
?>
<label for="geslacht-voorkeur">Geslacht voorkeur</label>
<select id="geslacht-voorkeur" name="geslacht_voorkeur" required>
    <option>Mannen</option>
    <option>Vrouwen</option>
    <option>Beide</option>
</select>
<br/>
<label>
    Leeftijd voorkeur
    <input type="number" name="leeftijd_min" id="leeftijd-min" min="18" max="99" required placeholder="min"/>
    <input type="number" name="leeftijd_max" id="leeftijd-max" min="18" max="99" required placeholder="max"/>
</label>
<br/>
<label>
    Persoonlijkheids voorkeur
    <select name="persoonlijkheids voorkeur" required>
        <!--            TODO insert voorkeuren-->
        <option>Geen</option>
    </select>
</label>
<br/>
<label id="merk-voorkeuren">
    Merk voorkeuren
    <input type="text" placeholder="Apple" id="merk-voorkeur"/>
    <button type="button" id="btn-add-merk">Voeg toe</button>
    <input type="hidden" id="merken" name="merken"/>
</label>
<br/>
<?php
echo form_submit('', 'Zoek');
echo form_close();
?>

<script src="<?php echo asset_url() . 'js/profiel-search.js' ?>"></script>
