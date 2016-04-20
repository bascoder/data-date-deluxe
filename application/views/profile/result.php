<div>
    <?php
    /** @var array $profielen */
    /** @var string $page_links */

    if (isset($profielen) && is_array($profielen) && !empty($profielen)):
        foreach ($profielen as $profiel):
            echo "<div class='profiel'>";
            $geslacht = $profiel->geslacht->geslacht;
            if (current_privileges() === Authentication::ANONYMOUS):
                echo img(placeholder_url($geslacht), FALSE, 'class="profiel-foto-thumb" alt="log in om een profiel foto te bekijken"');
            else:
                echo img($profiel->profiel_foto->url, FALSE, 'class="profiel-foto-thumb" alt="profiel foto"');
            endif;
            echo '<table>';
            echo "<tr><td>Nickname</td><td>$profiel->nickname</td></tr>";
            echo "<tr><td>Geslacht</td><td>$geslacht</td></tr>";
            $leeftijd = floor((time() - ($profiel->geboorte_datum)) / 31556926);
            echo "<tr><td>Leeftijd</td><td>$leeftijd</td></tr>";
            echo "<tr><td>Beschrijving</td><td>$profiel->beschrijving</td></tr>";
            $type = $profiel->persoonlijkheids_type->name;
            echo "<tr><td>Persoonlijkheids type</td><td>$type</td></tr>";
            echo '</table>';
            echo '</div>';
        endforeach;

        echo $page_links;
    endif;

    ?>
</div>
