<?php
function sessie_verlopen_redirect()
{
    $ci =& get_instance();
    $ci->session->set_flashdata('message',
        array('message' => 'Uw sessie is verlopen. Log opnieuw in.',
            'level' => 'error'));
    redirect('login');
}

function is_ingelogd()
{
    $ci =& get_instance();
    return $ci->authentication->is_authenticated();
}

/**
 * @return int constant die aangeeft wat de privileges van de huidige gebruiker zijn
 */
function current_privileges()
{
    $ci =& get_instance();
    return $ci->authentication->get_privileges();
}

function current_profiel()
{
    $ci =& get_instance();
    return $ci->authentication->get_current_profiel();
}
