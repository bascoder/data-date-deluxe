<h1>Creëer uw profiel</h1>
<div class="center">
    <?php echo form_open_multipart('profile/fototool/profiel_foto'); ?>

    <label for="profiel_foto">Kies een profiel foto</label>
    <input id="profiel_foto" type="file" name="profiel_foto" size="20" required accept="image/*"/>
    <br/>
    <label for="titel">Titel</label>
    <input type="text" id="titel" name="titel" required/>
    <br/>
    <label for="beschrijving">Beschrijving</label>
    <input type="text" id="beschrijving" name="beschrijving"/>
    <br/>
    <input type="submit" value="Verzend"/>

    <?php echo form_close(); ?>
    <?php
    echo form_open('profile/fototool/delete');
    echo form_submit('delete_foto', 'Delete profiel foto');
    echo form_close();
    ?>
</div>
