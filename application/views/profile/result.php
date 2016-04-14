<div>
    <?php
    if (current_privileges() === Authentication::ANONYMOUS):

        foreach ($profielen as $profiel):
            echo "<div class='profiel'>";
            echo img($profiel->profiel_foto->url, FALSE, 'class="profiel-foto"');
            echo '<table>';
            echo "<tr><td>Nickname</td><td>$profiel->nickname</td></tr>";
            $geslacht = $profiel->geslacht->geslacht;
            echo "<tr><td>Geslacht</td><td>$geslacht</td></tr>";
            $leeftijd = floor((time() - ($profiel->geboorte_datum)) / 31556926);
            echo "<tr><td>Leeftijd</td><td>$leeftijd</td></tr>";
            echo "<tr><td>Beschrijving</td><td>$profiel->beschrijving</td></tr>";
            echo "<tr><td>Persoonlijkheids type</td><td></td></tr>";
            echo '</table>';
            echo '</div>';
        endforeach;

    endif;
    ?>
</div>
