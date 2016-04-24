<div id="LoginForm" class="auth-container center">
    <form class="auth" action="login/submit" method="post">
        <label for="email">Email adres</label> <br>
        <input id="email" class="email" type="email" required name="email"> <br>
        <label for="password">Password</label> <br>
        <input id="password" type="password" required name="password"> <br>
        <input id="hash" type="hidden" required name="hash">
        <input type="submit">
    </form>
    <a href="<?php echo base_url() . "index.php/register" ?>">Nog geen lid? Klik hier.</a>
</div>
<script src="<?php echo asset_url() . 'js/sha1.js' ?>"></script>
<script src="<?php echo asset_url() . 'js/auth.js' ?>"></script>
