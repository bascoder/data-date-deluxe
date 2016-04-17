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
            <img class="responsive" src="<?php echo asset_url() . 'img/site_images/happy.jpg' ?>"
        <?php endif; ?>
    </article>
</div>

<h2>Profielen</h2>
<button id="meer-random-profielen">Meer profielen</button>
<div id="random-profielen"></div>

<script src="<?php echo asset_url() . 'js/home.js' ?>"></script>
