<?php

/**
 * Placeholder voor geslacht
 * @param $geslacht
 * @return string url naar placeholder
 */
function placeholder_url($geslacht)
{
    return 'assets/img/profiel_fotos/placeholder_' . (strtolower($geslacht) === 'man' ? 'male' : 'female') . '.svg';
}

/**
 * Return seksuele voorkeur als string
 * @param bool $valt_op_man
 * @param bool $valt_op_vrouw
 * @return string seksuele voorkeur als string
 */
function seksuele_voorkeur_display($valt_op_man, $valt_op_vrouw)
{
    if ($valt_op_man && $valt_op_vrouw) {
        $pref = 'biseksueel';
    } else {
        $pref = $valt_op_man ? 'mannen' : 'vrouwen';
    }
    return $pref;
}
