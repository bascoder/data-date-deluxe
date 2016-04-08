<?php

function asset_url()
{
    // return base url zonder protocol zodat de browser kan kiezen tussen HTTP en HTTPS
    $baseProtocolNeutral = str_replace(['http:', 'https:'], '', base_url());
    return $baseProtocolNeutral . 'assets/';
}
