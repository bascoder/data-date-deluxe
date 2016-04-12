<?php

/**
 *
 * @property Profiel profiel
 * @property Authentication authentication
 */
class Register extends CI_Controller
{
    public function index()
    {
        $this->load->view('register');
    }

    public function submit()
    {
        $this->load->model('profiel');
        try {
            // insert nieuw Profiel record
            $profiel_id = $this->register_profile();

            // als het profiel id FALSE is laat dan een error zien
            if ($profiel_id === FALSE) {
                $this->handle_db_error();
            } else {
                $profiel = $this->profiel->query_by_id($profiel_id);
                if ($profiel === NULL) {
                    // als het goed is komt dit nooit voor, toch loggen
                    $this->handle_db_error();
                } else {
                    $this->authentication->login($profiel);
                    redirect('/profile/create');
                }
            }
        } catch (InvalidArgumentException $e) {
            // input error, laat de gebruiker weten hoe te corrigeren
            show_error($e->getMessage(), 500);
        }

    }

    /**
     * @return bool|int
     */
    private function register_profile()
    {
        $fields = $this->profiel->make_fields($this->input->post());
        $profiel_id = $this->profiel->insert_entry($fields);
        return $profiel_id;
    }

    private function handle_db_error()
    {
        log_message('error', var_dump_to_string($this->db->error()));
        show_error('Er ging iets mis met het verwerken van uw gegevens', 500, 'Database error');
    }
}
