<div>
    <?php /** @var stdClass $profiel */
    /** @var bool $mag_liken */
    $geslacht = $profiel->geslacht->geslacht;
    if (is_ingelogd()):
        echo img(array(
            'src' => $profiel->profiel_foto->url,
            'alt' => 'Profiel foto: ' . $profiel->nickname . ' ' . $profiel->profiel_foto->titel,
            'class' => 'profiel-foto responsive'
        ));
        if ($mag_liken):
            echo form_open(base_url() . 'index.php/profile/edit/like/' . $profiel->pid, 'class="form-inline"');
            ?>
            <button type="submit" class="button-like">
                <img class="responsive"
                     src="<?php echo asset_url() . 'img/site_images/hearth.svg' ?>" alt="like"/>
            </button>
            <?php
            echo form_close();
        endif;
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
    $pref = seksuele_voorkeur_display($profiel->valt_op_man, $profiel->valt_op_vrouw);
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
            <td>
                <button class="edit-button" id="editDescription">Edit</button>
            </td>
        </tr>
        <tr>
            <th>Seksuele voorkeur</th>
            <td><?php echo $pref ?></td>
            <td>
                <button class="edit-button" id="editSexPref">Edit</button>
            </td>
        </tr>
        <tr>
            <th>Persoonlijkheids type</th>
            <td>
                <?php
                if (isset($profiel->persoonlijkheids_type))
                    echo $profiel->persoonlijkheids_type->name;
                ?>
            </td>
        </tr>
        <tr>
            <th>Persoonlijkheids voorkeur</th>
            <td>
                <?php
                if (isset($profiel->persoonlijkheids_type_voorkeur)):
                    echo $profiel->persoonlijkheids_type_voorkeur->name;
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
            <td>
                <button class="edit-button" id="editBrands">Edit</button>
            </td>
        </tr>
        </tbody>
    </table>
</div>

<script src="<?php echo asset_url() . 'js/profiel.js' ?>"></script>
