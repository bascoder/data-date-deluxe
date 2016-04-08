<div id="LoginForm">
    <form>
        Username: <br>
         <input type="text" required="True" name="username"> <br>
        Pasword: <br>
         <input type="password" required="True" name="password"> <br>
         <input type="submit">
    </form>
    <a href= "<?php echo base_url() . "index.php/register"?>">Nog geen lid? Klik hier.</a>
</div>
<script src="<?php echo asset_url() . 'js/sha1.js' ?>"></script>
<script src="<?php echo asset_url() . 'js/login.js' ?>"></script>