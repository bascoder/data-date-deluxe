<div>
    <?php /** @var stdClass $profiel */
    $geslacht = $profiel->geslacht->geslacht;
    if (is_ingelogd()):
        echo img(array(
            'src' => $profiel->profiel_foto->url,
            'alt' => 'Profiel foto: ' . $profiel->nickname . ' ' . $profiel->profiel_foto->titel,
            'class' => 'profiel-foto responsive'
        ));
    else:
        // placeholder foto van bijhorend geslacht
        echo img(array(
            'src' => 'assets/img/profiel_fotos/placeholder_' . (strtolower($geslacht) === 'man' ? 'male' : 'female') . '.svg',
            'alt' => 'Placeholder profiel foto, log in om meer te zien',
            'class' => 'profiel-foto responsive'
        ));
    endif;
    ?>
    <?php
    $pref = '';
    if ($profiel->valt_op_man && $profiel->valt_op_vrouw) {
        $pref = 'biseksueel';
    } else {
        $pref = $profiel->valt_op_man ? 'mannen' : 'vrouwen';
    }
    ?>
    <table class="table-profiel">
        <tbody>
        <tr>
            <th>Nickname</th>
            <td><?php echo $profiel->nickname; ?></td>
        </tr>
        <tr>
            <th>Geslacht</th>
            <td><?php echo $profiel->geslacht->geslacht; ?></td>
        </tr>
        <tr>
            <th>Geboortedatum</th>
            <td><?php echo date('d F Y', $profiel->geboorte_datum); ?></td>
        </tr>
        <tr>
            <th>Beschrijving</th>
            <td><?php echo $profiel->beschrijving; ?></td>
        </tr>
        <tr>
            <th>Seksuele voorkeur</th>
            <td><?php echo $pref ?></td>
        </tr>
        <tr>
            <th>Leeftijd voorkeur</th>
            <td><?php echo $profiel->leeftijd_voorkeur_min . ' - ' . $profiel->leeftijd_voorkeur_max; ?></td>
        </tr>
        </tbody>
    </table>
</div>
