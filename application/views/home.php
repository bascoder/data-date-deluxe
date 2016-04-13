<h1>Data Date Deluxe</h1>
<div>
    <article class="readable">
        <?php if (!is_ingelogd()): ?>
            <p>Toe aan een relatie? Een vaste? Of juist
                casual? <strong><?php echo anchor('register', 'Meldt u nu aan voor Data Date Deluxe!') ?></strong></p>
            <p>
                Dankzij deze website hebben al duizenden stelletjes hun geluk gevonden. Na het aanmelden voor een
                profiel
                zullen onze algoritmes de beste matches voor uw specifieke persoonlijkheids type uitzoeken.
                Zodra een wederzijdse like geeft kunt u elkaars contact informatie zien, en kan het
                <strong>daten</strong>
                beginnen!
            </p>
        <?php endif; ?>
    </article>
</div>
<?php if (!is_ingelogd()): ?>
    <div id="random-profielen">

    </div>
<?php endif; ?>

<script src="<?php echo asset_url() . 'js/home.js' ?>"></script>
