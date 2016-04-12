<?php

/**
 * Controller om profile aan te maken na het registreren
 * @property CI_Upload upload
 * @property  Foto foto
 * @property Authentication authentication
 */
class Create extends CI_Controller
{

    public function index()
    {
        $this->load->view('profile/create');

        $profiel = $this->authentication->get_current_profiel();
        if ($profiel === NULL) {
            $this->sessie_verlopen();
        }
    }

    public function profiel_foto()
    {
        $this->init_upload_settings();

        if (!$this->upload->do_upload('profiel_foto')) {
            show_error($this->upload->display_errors(), 500);
            $this->load->view('profile/create');
        } else {
            $data = array('profile/create' => $this->upload->data());

            $this->load->model('foto');
            $profiel = $this->authentication->get_current_profiel();
            if ($profiel === NULL) {
                $this->sessie_verlopen();
            }
            $profiel_id = $profiel->pid;
            $this->process_foto($data, $profiel_id);
        }
    }

    private function init_upload_settings()
    {
        $config['upload_path'] = 'upload';
        $config['allowed_types'] = 'gif|jpg|png|svg';
        $config['max_size'] = 5120; // 5MB
        $config['max_width'] = 5000;
        $config['max_height'] = 5000;

        $this->upload->initialize($config);
    }

    /**
     * @param $data
     * @param $profiel_id
     */
    private function process_foto($data, $profiel_id)
    {
        try {
            $this->foto->insert_profiel_foto($data['profile/create'], $profiel_id);

            $message = array('message' => 'Upload succesvol');
            $this->load->view('profile/create', $message);
        } catch (InvalidArgumentException $e) {
            show_error($e->getMessage(), 500);
        } catch (Exception $ex) {
            show_error('Er ging iets mis met het verwerken van uw profiel foto', 500);
        }
    }

    private function sessie_verlopen()
    {
        sessie_verlopen_redirect();
    }
}
