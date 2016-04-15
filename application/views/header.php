<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Data Date Deluxe</title>

    <link rel="shortcut icon" type="image/x-icon" href="<?php echo base_url() . 'favicon.ico' ?>">

    <link rel="stylesheet" type="text/css" href="<?php echo asset_url() . 'css/main.css' ?>"/>
    <link rel="stylesheet" type="text/css" href="<?php echo asset_url() . 'js/jquery-ui-1.11.4/jquery-ui.min.css' ?>"/>
    <script>
        // base url ook handig in JavaScript
        var base_url = "<?php echo base_url(); ?>";
    </script>
    <script src="<?php echo asset_url() . 'js/jquery-2.2.3.min.js' ?>"></script>
    <script src="<?php echo asset_url() . 'js/jquery-ui-1.11.4/jquery-ui.min.js' ?>"></script>
    <script src="<?php echo asset_url() . 'js/main.js' ?>"></script>
    <script src="<?php echo asset_url() . 'js/models.js' ?>"></script>
</head>
<body>
<nav>
    <ul class="nav">
        <li><a href="<?php echo base_url() ?>">Home</a></li>
        <li>
            <a href="<?php echo base_url() . 'index.php/profile/lookup/page' ?>">
                <img class="svg-nav responsive" src="<?php echo asset_url() . 'img/site_images/search.svg' ?>"
                     alt="zoek profielen"/>
                &nbsp;Profielen
            </a>
        </li>
        <?php // conditioneel login/log uit knopje ?>
        <?php if ($is_auth): ?>
            <li id="logout" class="right"><a href="<?php echo base_url() . "index.php/login/logout" ?>">Log uit</a></li>
            <li id="my-profile" class="right">
                <?php echo anchor('profile/display/mijn', current_profiel()->voornaam) ?>
            </li>
        <?php else: ?>
            <li id="login" class="right"><a href="<?php echo base_url() . "index.php/login" ?>">Log in</a></li>
            <li id="registreren" class="right"><?php echo anchor('register', 'Registreren') ?></li>
        <?php endif; ?>
    </ul>
</nav>
<div class="content">