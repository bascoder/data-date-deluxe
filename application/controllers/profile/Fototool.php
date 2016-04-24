<?php

/**
 * Controller om profile aan te maken na het registreren
 * @property CI_Upload upload
 * @property  Foto foto
 * @property Authentication authentication
 * @property CI_Session session
 * @property  Like like
 * @property Profiel profiel
 * @property CI_Image_lib image_lib
 * @property CI_Config config
 * @property CI_Input input
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

    /**
     * Overlay foto op basis van Like status
     * @param int $pid
     */
    public function overlay($pid)
    {
        $is_thumbnail = $this->input->get('thumb');
        $this->load->library('image_lib');
        if (!isset($pid) || intval($pid) === 0) {
            show_error('Parameter pid is verplicht', 400);
        }

        $profiel = $this->profiel->query_by_id($pid);
        $foto = $this->foto->query_by_profiel($profiel);
        try {

            $like_status = $this->like->get_like_status($pid);

            $url = $foto->url;
            if ($is_thumbnail) {
                $url = $this->small_url($url);
            }

            $config['source_image'] = $url;
            $config['wm_text'] = $this->like->get_status_overlay_text($like_status);
            $config['wm_type'] = 'text';
            $config['wm_font_size'] = '32';
            $config['wm_font_path'] = 'assets/fonts/roboto/roboto-black.ttf';
            $config['wm_font_color'] = 'fa0000';
            $config['wm_shadow_color'] = 'ff9933';
            $config['wm_vrt_alignment'] = 'bottom';
            $config['wm_hor_alignment'] = 'center';
            $config['wm_padding'] = '0';
            $config['dynamic_output'] = TRUE;

            $this->image_lib->initialize($config);
            //log_message('debug', 'Start watermerk: ' . var_dump_to_string($config));
            if (!$this->image_lib->watermark()) {
                log_message('debug', 'Image lib errors: ' . $this->image_lib->display_errors());
            }

        } catch (Exception $e) {
            // bij een exception return de profiel foto
            log_message('error', 'Exception in fototool/overlay so fallback on profiel foto: ' . var_dump_to_string($e));
            $config['dynamic_output'] = TRUE;
            $this->image_lib->initialize($config);
            $this->image_lib->resize();
            $this->foto->query_by_profiel($profiel);
        }
        //log_message('debug', 'Image lib errors: ' . $this->image_lib->display_errors());
    }

    private function small_url($url)
    {
        $orig_url = $url;
        $exploded = explode($url, '.');
        if (count($exploded) > 0) {
            $extension = $exploded[count($exploded) - 1];
            $url = str_replace($extension, '_small' . $extension, $url);
        }

        if (is_string($url))
            return $url;
        else {
            return $orig_url;
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
