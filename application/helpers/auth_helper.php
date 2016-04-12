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