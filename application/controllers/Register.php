<?php

/**
 *
 * @property Profiel profiel
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
            $fields = $this->profiel->make_fields($this->input->post());
            $status = $this->profiel->insert_entry($fields);

            if (!$status) {
                log_message('error', var_dump_to_string($this->db->error()));
                show_error('Er ging iets mis met het verwerken van uw gegevens', 500, 'Database error');
            } else {
                redirect('/profile/create');
            }
        } catch (InvalidArgumentException $e) {
            show_error($e->getMessage(), 500);
        }

    }
}
