<?php

function asset_url()
{
    // return base url zonder protocol zodat de browser kan kiezen tussen HTTP en HTTPS
    $baseProtocolNeutral = str_replace(['http:', 'https:'], '', base_url());
    return $baseProtocolNeutral . 'assets/';
}

/**
 * Return var_dump van $var
 * @param $var mixed var to dump
 * @return string output of var dump
 */
function var_dump_to_string($var) {
    ob_start();
    var_dump($var);
    $result = ob_get_clean();
    return $result;
}
