<?php

/**
 *
 * @property Authentication authentication
 * @property Profiel profiel
 * @property Like like
 * @property CI_DB_pdo_driver|CI_DB_query_builder db
 * @property CI_Session session
 * @property CI_Loader load
 */
class Backoffice extends CI_Controller
{
    public function index()
    {
        if ($this->authentication->get_privileges() !== Authentication::ADMIN) {
            // log security audit met email adres
            $profiel = $this->authentication->get_current_profiel(FALSE);
            log_message('error', 'Security audit: poging tot openen admin page door niet-admin: '
                . $_SERVER['REMOTE_ADDR'] . ' ' . isset($profiel) ? var_dump_to_string($profiel->email) : '');

            $this->session->set_flashdata('message',
                array('message' => 'Je moet ingelogd zijn als administrator om deze pagina te openen',
                    'level' => 'error'));
            redirect('login');
        } else {
            log_message('info', 'Security audit: admin page geopend door IP: ' . $_SERVER['REMOTE_ADDR']);
            $this->load->view('admin/backoffice.php');
        }
    }
}
