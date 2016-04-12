<?
function sessie_verlopen_redirect()
{
    $ci =& get_instance();
    $ci->session->set_flashdata('message',
        array('message' => 'Uw sessie is verlopen. Log opnieuw in.',
            'level' => 'error'));
    redirect('login');
}
