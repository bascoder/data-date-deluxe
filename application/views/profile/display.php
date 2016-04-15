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
            'src' => placeholder_url($geslacht),
            'alt' => 'Placeholder profiel foto, log in om meer te zien',
            'class' => 'profiel-foto responsive'
        ));
    endif;
    ?>
    <?php
    $isOwn = is_ingelogd() && $this->authentication->get_current_profiel()->pid == $profiel->pid;
    $pref = seksuele_voorkeur_display($profiel->valt_op_man, $profiel->valt_op_vrouw);
    $prefNum = $profiel->valt_op_man + (2* $profiel->valt_op_vrouw);
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
            <td id="Description"><?php echo $profiel->beschrijving; ?></td>
            <?php if ($isOwn): ?><td><button id="editDescription">Edit</button></td><?php endif; ?>
        </tr>
        <tr>
            <th>Seksuele voorkeur</th>
            <td id="SexPref" editVal="<?php echo $prefNum;?>" ><?php echo $pref ?></td>
            <?php if ($isOwn): ?><td><button id ="editSexPref">Edit</button></td><?php endif; ?>
        </tr>
        <tr>
            <th>Persoonlijkheids type</th>
            <td>
                <?php
                if (isset($profiel->persoonlijkheids_type))
                    echo $profiel->persoonlijkheids_type->type
                ?>
            </td>
        </tr>
        <tr>
            <th>Persoonlijkheids voorkeuren</th>
            <td>
                <?php
                if (isset($profiel->persoonlijks_voorkeuren)):
                    foreach ($profiel->persoonlijks_voorkeuren as $voorkeur):
                        echo ' ' . $voorkeur->type;
                    endforeach;
                endif;
                ?>
            </td>
        </tr>
        <tr>
            <th>Merk voorkeuren</th>
            <td>
                <?php
                if (isset($profiel->merken)):
                    foreach ($profiel->merken as $merk):
                        echo ' ' . $merk->naam;
                    endforeach;
                endif;
                ?>
            </td>
            <?php if ($isOwn): ?><td><button id="editBrands">Edit</button></td><?php endif; ?>
        </tr>
        </tbody>
    </table>
</div>

<script src="<?php echo asset_url() . 'js/profiel.js' ?>"></script>
