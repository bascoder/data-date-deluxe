<div>
    <?php
    /** @var array $profielen */
    /** @var string $page_links */

    if (isset($profielen) && is_array($profielen) && !empty($profielen)):
        foreach ($profielen as $profiel):
            echo "<div class='profiel clickable' data-pid='$profiel->pid'>";
            $geslacht = $profiel->geslacht->geslacht;
            if (current_privileges() === Authentication::ANONYMOUS):
                echo img(array('src' => placeholder_url($geslacht) . '?thumb=1', 'class' => "profiel-foto-thumb", 'alt' => "log in om een profiel foto te bekijken"));
            else:
                echo img(array('src' => $profiel->profiel_foto->url . '?thumb=1', 'class' => "profiel-foto-thumb", 'alt' => "profiel foto"));
            endif;
            echo '<table>';
            echo "<tr><td>Nickname</td><td>$profiel->nickname</td></tr>";
            echo "<tr><td>Geslacht</td><td>$geslacht</td></tr>";
            $leeftijd = floor((time() - ($profiel->geboorte_datum)) / 31556926);
            echo "<tr><td>Leeftijd</td><td>$leeftijd</td></tr>";
            $beschrijving = htmlentities($profiel->beschrijving, ENT_QUOTES);
            echo "<tr><td>Beschrijving</td><td>$beschrijving</td></tr>";
            if (isset($profiel->persoonlijkheids_type)):
                $type = $profiel->persoonlijkheids_type->name;
            else:
                $type = 'Niet bekend';
            endif;
            echo "<tr><td>Persoonlijkheids type</td><td>$type</td></tr>";
            if (isset($profiel->aantrekkelijkheid)):
                echo "<tr><td>Aantrekkelijkheid</td><td>$profiel->aantrekkelijkheid&percnt;</td></tr>";
            endif;
            if (isset($profiel->like_status)):
                echo "<tr><td>Like status</td>";
                $like_status = htmlentities($profiel->like_status);
                echo "<td>$like_status</td></tr>";
            endif;
            echo '</table>';
            echo '</div>';
        endforeach;

        echo $page_links;
    endif;

    ?>
</div>
<script src="<?php echo asset_url() . 'js/result.js' ?>"></script>
