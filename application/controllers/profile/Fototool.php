<?php

/**
 * Controller om profile aan te maken na het registreren
 * @property CI_Upload upload
 * @property  Foto foto
 * @property Authentication authentication
 * @property CI_Session session
 */
class FotoTool extends CI_Controller
{

    public function index()
    {
        $this->load->view('profile/fototool');

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
            $this->load->view('profile/fototool');
        } else {
            $data = array('profile/fototool' => $this->upload->data());

            $this->load->model('foto');
            $profiel = $this->authentication->get_current_profiel();
            if ($profiel === NULL) {
                $this->sessie_verlopen();
            }
            $profiel_id = $profiel->pid;
            $this->process_foto($data, $profiel_id);
        }
    }

    public function delete()
    {
        $profiel = $this->authentication->get_current_profiel();
        if ($profiel === NULL) {
            $this->sessie_verlopen();
        } else {
            $this->try_delete_profiel_foto($profiel);
            redirect('profile/display/mijn');
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
            $this->foto->insert_profiel_foto($data['profile/fototool'], $profiel_id);

            $this->session->set_flashdata('message',
                array('message' => 'Uw foto is succesvol geüpload',
                    'level' => 'success'));
            redirect('profile/display/mijn');
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

    /**
     * @param $profiel
     */
    public function try_delete_profiel_foto($profiel)
    {
        try {
            $this->foto->delete_profiel_foto($profiel);
            $this->foto->set_to_placeholder($profiel);
        } catch (InvalidArgumentException $e) {
            // een exceptie met gebruikersvriendelijke message
            $this->session->set_flashdata('error',
                array('message' => $e->getMessage(),
                    'level' => 'error'));
        } catch (Exception $ex) {
            // andere errors
            $this->session->set_flashdata('error',
                array('message' => 'Er ging iets mis.',
                    'level' => 'error'));
        }

        // toon melding
        $this->session->set_flashdata('message',
            array('message' => 'Uw foto is succesvol verwijderd',
                'level' => 'success'));
    }
}
