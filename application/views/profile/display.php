<div>
    <?php /** @var stdClass $profiel */
    /** @var string|bool $like_status */
    $geslacht = $profiel->geslacht->geslacht;
    if (is_ingelogd()):
        echo img(array(
            'src' => $profiel->profiel_foto->url,
            'alt' => 'Profiel foto: ' . $profiel->nickname . ' ' . $profiel->profiel_foto->titel,
            'class' => 'profiel-foto responsive'
        ));
        if ($like_status === Like::GEEN_LIKE || $like_status === Like::ONTVANGEN_LIKE):
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
    $isOwn = is_ingelogd() && $this->authentication->get_current_profiel()->pid == $profiel->pid;
    $pref = seksuele_voorkeur_display($profiel->valt_op_man, $profiel->valt_op_vrouw);
    $prefNum = $profiel->valt_op_man + (2 * $profiel->valt_op_vrouw);
    ?>
    <table class="table-profiel">
        <tbody>
        <?php if ($like_status === Like::WEDERZIJDSE_LIKE): ?>
            <tr>
                <td>Naam</td>
                <th><?php echo $profiel->voornaam . ' ' . $profiel->achternaam; ?></th>
            </tr>
            <tr>
                <td>Email adres</td>
                <th>
                    <?php echo "<a href='mailto:$profiel->email'>$profiel->email</a>"; ?>
                </th>
            </tr>
        <?php endif; ?>
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
            <?php if ($isOwn): ?>
                <td>
                    <button class="edit-button" id="editDescription">Edit</button>
                </td><?php endif; ?>
        </tr>
        <tr>
            <th>Seksuele voorkeur</th>
            <td id="SexPref" editVal="<?php echo $prefNum; ?>"><?php echo $pref ?></td>
            <?php if ($isOwn): ?>
                <td>
                    <button class="edit-button" id="editSexPref">Edit</button>
                </td><?php endif; ?>
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
            <td id="merken-td" data-merken='<?php echo json_encode($profiel->merken); ?>'>
                <span id="merken-spans">
                <?php
                if (isset($profiel->merken)):
                    foreach ($profiel->merken as $merk):
                        echo ' <span class="merk-label"> ' . $merk->naam . '</span>';
                    endforeach;
                endif;
                echo '</span>';
                if ($isOwn): ?>
                    <input type="text" id="new-merk" placeholder="een nieuw merk" style="display: none;"/>
                    <button type="button" id="new-merk-button" style="display: none;">Voeg toe</button>
                <?php endif; ?>
            </td>
            <?php if ($isOwn): ?>
                <td>
                    <button class="edit-button" id="editBrands">Edit</button>
                </td><?php endif; ?>
        </tr>
        <?php if ($like_status !== FALSE): ?>
            <tr>
                <th>Like status</th>
                <td><?php echo htmlentities($like_status); ?></td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>
    <?php if ($isOwn && is_ingelogd()):
        echo form_open(base_url() . 'index.php/profile/edit/delete/' . $profiel->pid, array('class' => 'form-delete'));
        echo '<button type="submit" id="delete-profiel">Verwijder profiel</button>';
        echo form_close();
    endif;
    ?>
</div>
<script src="<?php echo asset_url() . 'js/merkenUtil.js' ?>"></script>
<script src="<?php echo asset_url() . 'js/profiel.js' ?>"></script>