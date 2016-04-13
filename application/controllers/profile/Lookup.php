<?php

/**
 *
 * @property Profiel profiel
 * @property CI_Output output
 */
class Lookup extends CI_Controller
{
    public function index()
    {
        show_404();
    }

    /**
     * Json response met 6 random profielen
     */
    public function random()
    {
        $this->load->model('profiel');

        $this->output->set_content_type('application/json');
        try {
            $profielen = $this->profiel->query_random_profielen();

            if ($profielen !== NULL && is_array($profielen) && !empty($profielen)) {
                // wel profielen return profielen als json
                $this->output->set_output(json_encode($profielen));
            } else {
                // geen profielen return 404 not found
                $this->handle_404_json();
            }
        } catch (Exception $e) {
            // onverwachte exceptie loggen en returnen als error
            $this->handle_exception_json($e);
        }
    }

    /**
     * @param Exception $e
     */
    private function handle_exception_json($e)
    {
        log_message('error', $e->getMessage());
        $this->output
            ->set_status_header(500)
            ->set_output(json_encode(array('error' => 'Een exception trad op: ' . $e->getMessage())));
    }

    private function handle_404_json()
    {
        $this->output
            ->set_status_header(404)
            ->set_output(json_encode(array('error' => 'geen profielen gevonden')));
    }
}
