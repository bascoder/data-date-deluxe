<div id="LoginForm">
    <form>
        Username: <br>
         <input type="text" required="True" name="username"> <br>
        Pasword: <br>
         <input type="password" required="True" name="password"> <br>
         <input type="submit">
    </form>
    <a href= "<?php echo base_url() . "index.php/login/register"?>">Nog geen lid? Klik hier.</a>
</div>