<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Data Date Deluxe <3</title>

    <link rel="shortcut icon" type="image/x-icon" href="<?php echo base_url() . 'favicon.ico' ?>">

    <link rel="stylesheet" type="text/css" href="<?php echo asset_url() . 'css/main.css' ?>"/>
    <link rel="stylesheet" type="text/css" href="<?php echo asset_url() . 'js/jquery-ui-1.11.4/jquery-ui.min.css' ?>"/>
    <script>
        // base url ook handig in JavaScript
        var base_url = "<?php echo base_url(); ?>";
        // is ingelogd, natuurlijk niet als om gevoelige informatie te tonen maar om overbodige ui elementen te hiden
        var is_ingelogd = "<?php echo is_ingelogd() ? 'true' : 'false'; ?>";
    </script>
    <script src="<?php echo asset_url() . 'js/jquery-2.2.3.min.js' ?>"></script>
    <script src="<?php echo asset_url() . 'js/jquery-ui-1.11.4/jquery-ui.min.js' ?>"></script>
    <script src="<?php echo asset_url() . 'js/main.js' ?>"></script>
    <script src="<?php echo asset_url() . 'js/models.js' ?>"></script>
</head>
<body>
<nav>
    <div id="progressbar-ajax"></div>
    <ul class="nav">
        <li><a href="<?php echo base_url() ?>">Home</a></li>
        <li>
            <a href="<?php echo base_url() . 'index.php/profile/lookup/page' ?>">
                <img class="svg-nav responsive" src="<?php echo asset_url() . 'img/site_images/search.svg' ?>"
                     alt="zoek profielen"/>
                &nbsp;Profielen
            </a>
        </li>
        <?php if ($is_auth): ?>
            <li>
                <?php echo anchor('/profile/lookup/auto_match/0',
                    'Magische search &#x1f47b'); ?>
            </li>
            <li>
                <?php echo anchor('/profile/lookup/like_relatie/0?like_relatie_type=' . urlencode(Like::GEGEVEN_LIKE),
                    'Crushes &#x1f60d;'); ?>
            </li>
            <li>
                <?php echo anchor('/profile/lookup/like_relatie/0?like_relatie_type=' . urlencode(Like::ONTVANGEN_LIKE),
                    'Volgers &#x1f490'); ?>
            </li>
            <li>
                <?php echo anchor('/profile/lookup/like_relatie/0?like_relatie_type=' . urlencode(Like::WEDERZIJDSE_LIKE),
                    'Matches &#x1f491'); ?>
            </li>
            <?php // conditioneel login/log uit knopje ?>
            <li id="logout" class="right"><a href="<?php echo base_url() . "index.php/login/logout" ?>">Log uit</a></li>
            <li id="my-profile" class="right">
                <?php echo anchor('profile/display/mijn', current_profiel()->voornaam) ?>
            </li>
            <?php if (current_privileges() === Authentication::ADMIN): ?>
                <li id="back-office" class="right">
                    <?php echo anchor('admin/backoffice', 'Configuratie') ?>
                </li>
            <?php endif; ?>
        <?php else: ?>
            <li id="login" class="right"><a href="<?php echo base_url() . "index.php/login" ?>">Log in</a></li>
            <li id="registreren" class="right"><?php echo anchor('register', 'Registreren') ?></li>
        <?php endif; ?>
    </ul>
</nav>
<div class="content">